<?php

namespace Tests\Feature;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantPublicAndMentorTest extends TestCase
{
    use RefreshDatabase;

    protected Opd $opdKesehatan;
    protected Opd $opdKominfo;
    protected Division $divisi1;
    protected Division $divisi2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->opdKesehatan = Opd::create([
            'name'                => 'Dinas Kesehatan Kabupaten Tuban',
            'code'                => 'dinkes',
            'slug'                => 'dinkes',
            'address'             => 'Jl. Brawijaya No. 10 Tuban',
            'phone'               => '(0356) 321111',
            'email'               => 'dinkes@tubankab.go.id',
            'head_of_office_name' => 'dr. Bambang Priyo',
            'head_of_office_nip'  => '197501012000011001',
            'is_active'           => true,
        ]);

        $this->opdKominfo = Opd::create([
            'name'                => 'Dinas Komunikasi dan Informatika, Statistik dan Persandian',
            'code'                => 'diskominfo-sp',
            'slug'                => 'diskominfo-sp',
            'address'             => 'Jl. Mastrip No. 5 A Tuban',
            'phone'               => '(0356) 8832697',
            'email'               => 'diskominfo@tubankab.go.id',
            'head_of_office_name' => 'Arif Handoyo, S.Kom',
            'head_of_office_nip'  => '198001012005011002',
            'is_active'           => true,
        ]);

        $this->divisi1 = Division::create([
            'nama_divisi' => 'Pelayanan Medis',
            'deskripsi'   => 'Divisi Medis',
            'opd_id'      => $this->opdKesehatan->id,
        ]);

        $this->divisi2 = Division::create([
            'nama_divisi' => 'Aplikasi dan Informatika',
            'deskripsi'   => 'Divisi IT',
            'opd_id'      => $this->opdKominfo->id,
        ]);
    }

    public function test_landing_page_displays_opd_name_and_filters_by_opd(): void
    {
        $pos1 = Position::create([
            'nama_posisi' => 'Tenaga Rekam Medis',
            'deskripsi'   => 'Pengelolaan rekam medis',
            'tipe'        => 'magang',
            'kuota'       => 5,
            'status'      => PositionStatus::Open,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);

        $pos2 = Position::create([
            'nama_posisi' => 'Web Developer Diskominfo',
            'deskripsi'   => 'Pengembangan aplikasi SPBE',
            'tipe'        => 'magang',
            'kuota'       => 3,
            'status'      => PositionStatus::Open,
            'opd_id'      => $this->opdKominfo->id,
            'division_id' => $this->divisi2->id,
        ]);

        // Posisi legacy tanpa OPD
        $pos3 = Position::create([
            'nama_posisi' => 'Staf Jaringan Legacy',
            'deskripsi'   => 'Jaringan kabel',
            'tipe'        => 'magang',
            'kuota'       => 2,
            'status'      => PositionStatus::Open,
            'opd_id'      => null,
            'division_id' => $this->divisi2->id,
        ]);

        // 1. Visit landing page without filter: displays both and fallback
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Tenaga Rekam Medis');
        $response->assertSee('Web Developer Diskominfo');
        $response->assertSee('Dinas Kesehatan Kabupaten Tuban');
        $response->assertSee('Dinas Komunikasi dan Informatika, Statistik dan Persandian');

        // 2. Filter by Kesehatan
        $responseFilter = $this->get('/?opd_id='.$this->opdKesehatan->id.'#positions');
        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('Tenaga Rekam Medis');
        $responseFilter->assertDontSee('Web Developer Diskominfo');
    }

    public function test_global_search_returns_opd_name_and_filters(): void
    {
        $pos = Position::create([
            'nama_posisi' => 'Apoteker Farmasi',
            'deskripsi'   => 'Layanan farmasi',
            'tipe'        => 'magang',
            'kuota'       => 4,
            'status'      => PositionStatus::Open,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);

        // Cari berdasarkan nama posisi
        $searchRes = $this->getJson('/search?q=Apoteker');
        $searchRes->assertStatus(200);
        $searchRes->assertJsonFragment([
            'title'    => 'Apoteker Farmasi',
            'opd_name' => 'Dinas Kesehatan Kabupaten Tuban',
        ]);

        // Cari berdasarkan nama OPD
        $searchOpd = $this->getJson('/search?q=Kesehatan');
        $searchOpd->assertStatus(200);
        $searchOpd->assertJsonFragment([
            'title'    => 'Apoteker Farmasi',
            'opd_name' => 'Dinas Kesehatan Kabupaten Tuban',
        ]);
    }

    public function test_participant_registration_form_displays_opd_badge(): void
    {
        $user = User::factory()->create(['role' => UserRole::Peserta]);
        Profile::create([
            'user_id'       => $user->id,
            'institusi'     => 'Universitas Airlangga',
            'jenis_kelamin' => 'L',
            'no_hp'         => '08123456789',
            'jurusan'       => 'Sistem Informasi',
        ]);

        Position::create([
            'nama_posisi' => 'Frontend Engineer',
            'deskripsi'   => 'Vue and Blade UI',
            'tipe'        => 'magang',
            'kuota'       => 3,
            'status'      => PositionStatus::Open,
            'opd_id'      => $this->opdKominfo->id,
            'division_id' => $this->divisi2->id,
        ]);

        $response = $this->actingAs($user)->get(route('participant.registrations.create'));
        $response->assertStatus(200);
        $response->assertSee('Frontend Engineer');
        $response->assertSee('Dinas Komunikasi dan Informatika, Statistik dan Persandian');
        $response->assertSee('Pilih OPD');
    }

    public function test_pembimbing_isolated_by_opd_on_dashboard(): void
    {
        // Pembimbing di Dinkes
        $mentorDinkes = User::factory()->create([
            'role'        => UserRole::Pembimbing,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);

        // Peserta di Dinkes
        $internDinkes = User::factory()->create([
            'role'        => UserRole::Peserta,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);
        $posDinkes = Position::create([
            'nama_posisi' => 'Staff Medis Magang',
            'deskripsi'   => 'Deskripsi Medis',
            'tipe'        => 'magang',
            'kuota'       => 5,
            'status'      => PositionStatus::Open,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);
        $regDinkes = Registration::create([
            'user_id'           => $internDinkes->id,
            'position_id'       => $posDinkes->id,
            'opd_id'            => $this->opdKesehatan->id,
            'nomor_pendaftaran' => 'REG-DINKES-001',
            'status'            => RegistrationStatus::Accepted,
            'periode_mulai'     => now()->subDays(5),
            'periode_selesai'   => now()->addMonths(2),
        ]);

        // Peserta di Diskominfo
        $internKominfo = User::factory()->create([
            'role'        => UserRole::Peserta,
            'opd_id'      => $this->opdKominfo->id,
            'division_id' => $this->divisi2->id,
        ]);
        $posKominfo = Position::create([
            'nama_posisi' => 'Staff IT Magang',
            'deskripsi'   => 'Deskripsi IT',
            'tipe'        => 'magang',
            'kuota'       => 5,
            'status'      => PositionStatus::Open,
            'opd_id'      => $this->opdKominfo->id,
            'division_id' => $this->divisi2->id,
        ]);
        $regKominfo = Registration::create([
            'user_id'           => $internKominfo->id,
            'position_id'       => $posKominfo->id,
            'opd_id'            => $this->opdKominfo->id,
            'nomor_pendaftaran' => 'REG-KOMINFO-002',
            'status'            => RegistrationStatus::Accepted,
            'periode_mulai'     => now()->subDays(5),
            'periode_selesai'   => now()->addMonths(2),
        ]);

        // Mentor Dinkes views dashboard
        $response = $this->actingAs($mentorDinkes)->get(route('pembimbing.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('REG-DINKES-001');
        $response->assertDontSee('REG-KOMINFO-002');
    }

    public function test_pembimbing_cross_opd_attendance_access_is_forbidden(): void
    {
        $mentorDinkes = User::factory()->create([
            'role'        => UserRole::Pembimbing,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);

        // Create an intern in Kominfo
        $internKominfo = User::factory()->create([
            'role'        => UserRole::Peserta,
            'opd_id'      => $this->opdKominfo->id,
            'division_id' => $this->divisi2->id,
        ]);
        $regKominfo = Registration::create([
            'user_id'           => $internKominfo->id,
            'opd_id'            => $this->opdKominfo->id,
            'nomor_pendaftaran' => 'REG-KOMINFO-ATTEND',
            'status'            => RegistrationStatus::Accepted,
            'periode_mulai'     => now()->subDays(5),
            'periode_selesai'   => now()->addMonths(2),
        ]);

        // Mentor Dinkes tries to view intern Kominfo's attendance
        $response = $this->actingAs($mentorDinkes)->get(route('pembimbing.attendance.show', $regKominfo));
        $response->assertStatus(403);

        // Mentor Dinkes tries to export intern Kominfo's attendance PDF
        $responsePdf = $this->actingAs($mentorDinkes)->get(route('pembimbing.attendance.pdf', $regKominfo));
        $responsePdf->assertStatus(403);
    }

    public function test_pembimbing_cross_opd_certificate_action_is_forbidden(): void
    {
        $mentorDinkes = User::factory()->create([
            'role'        => UserRole::Pembimbing,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);

        $internKominfo = User::factory()->create([
            'role'        => UserRole::Peserta,
            'opd_id'      => $this->opdKominfo->id,
            'division_id' => $this->divisi2->id,
        ]);
        $regKominfo = Registration::create([
            'user_id'           => $internKominfo->id,
            'opd_id'            => $this->opdKominfo->id,
            'nomor_pendaftaran' => 'REG-KOMINFO-CERT',
            'status'            => RegistrationStatus::Accepted,
            'periode_mulai'     => now()->subMonths(3),
            'periode_selesai'   => now()->subDays(1),
        ]);

        // Mentor Dinkes tries to download certificate for intern Kominfo
        $response = $this->actingAs($mentorDinkes)->get(route('pembimbing.certificates.download', $regKominfo->id));
        $response->assertStatus(403);
    }

    public function test_pdf_exports_render_dynamic_opd_headers_successfully(): void
    {
        $admin = User::factory()->create([
            'role'   => UserRole::SuperAdmin,
            'opd_id' => $this->opdKesehatan->id,
        ]);

        // Test 1: Application Review PDF
        $appPdf = $this->actingAs($admin)->get(route('admin.applications.pdf', ['opd_id' => $this->opdKesehatan->id]));
        $appPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $appPdf->headers->get('content-type'));

        // Test 2: Active Interns PDF
        $internPdf = $this->actingAs($admin)->get(route('admin.active-interns.pdf', ['opd_id' => $this->opdKesehatan->id]));
        $internPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $internPdf->headers->get('content-type'));

        // Test 3: Pembimbing Dashboard PDF
        $mentor = User::factory()->create([
            'role'        => UserRole::Pembimbing,
            'opd_id'      => $this->opdKesehatan->id,
            'division_id' => $this->divisi1->id,
        ]);
        $dashPdf = $this->actingAs($mentor)->get(route('pembimbing.dashboard.export-pdf'));
        $dashPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $dashPdf->headers->get('content-type'));

        // Test 4: Admin Certificate PDF (centralized to Admin OPD)
        $certPdf = $this->actingAs($admin)->get(route('admin.certificates.export-pdf', ['opd_id' => $this->opdKesehatan->id]));
        $certPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $certPdf->headers->get('content-type'));
    }
}
