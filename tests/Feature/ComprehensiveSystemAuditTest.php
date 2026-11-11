<?php

namespace Tests\Feature;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ComprehensiveSystemAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $adminOpd;
    protected User $pembimbing;
    protected User $peserta;
    protected Division $division;
    protected Position $position;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SettingSeeder::class);

        $this->division = Division::factory()->create([
            'nama_divisi' => 'Dinas Kominfo SP',
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Administrator',
            'role' => UserRole::SuperAdmin,
        ]);

        $this->adminOpd = User::factory()->create([
            'name' => 'Administrator OPD Diskominfo',
            'role' => UserRole::Admin,
            'division_id' => $this->division->id,
        ]);

        $this->pembimbing = User::factory()->create([
            'name' => 'Pembimbing Lapangan Diskominfo',
            'role' => UserRole::Pembimbing,
            'division_id' => $this->division->id,
        ]);

        $this->position = Position::create([
            'nama_posisi' => 'Junior Web Developer',
            'slug' => 'junior-web-developer-' . uniqid(),
            'kuota' => 5,
            'status' => 'aktif',
            'deskripsi' => 'Pengembangan portal SIMAGANG',
            'division_id' => $this->division->id,
            'kualifikasi_siswa' => 'SMK Rekayasa Perangkat Lunak',
            'kualifikasi_mahasiswa' => 'S1 Teknik Informatika / Sistem Informasi',
        ]);

        $this->peserta = User::factory()->create([
            'name' => 'Bagus Dwi Cahya',
            'role' => UserRole::Peserta,
            'division_id' => $this->division->id,
            'created_at' => Carbon::parse('2026-10-01 08:00:00'),
        ]);

        Profile::create([
            'user_id' => $this->peserta->id,
            'nama_lengkap' => $this->peserta->name,
            'participant_type' => 'mahasiswa',
            'nis_nim' => '24050974099',
            'nim' => '24050974099',
            'nik' => '3523010101019999',
            'tempat_lahir' => 'Tuban',
            'tanggal_lahir' => '2002-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'institusi' => 'Universitas Negeri Surabaya',
            'jurusan' => 'Teknik Informatika',
            'tahun_angkatan' => '2022',
            'semester' => 7,
            'alamat' => 'Kabupaten Tuban',
            'no_telepon' => '081234567899',
        ]);

        Registration::create([
            'nomor_pendaftaran' => 'REG-2026-AUDIT-001',
            'user_id' => $this->peserta->id,
            'position_id' => $this->position->id,
            'status' => RegistrationStatus::Accepted,
            'periode_mulai' => Carbon::parse('2026-10-15'),
            'periode_selesai' => Carbon::parse('2026-12-31'),
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'sp.pdf',
        ]);
    }

    public function test_guest_public_routes_are_accessible_and_healthy()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SIMAGANG');

        $responseLogin = $this->get('/login');
        $responseLogin->assertStatus(200);

        $responseRegister = $this->get('/register');
        $responseRegister->assertStatus(200);

        $responseSearch = $this->get('/search?q=kominfo');
        $responseSearch->assertStatus(200);
    }

    public function test_super_admin_can_access_all_management_modules()
    {
        $this->actingAs($this->superAdmin);

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.opds.index'))->assertStatus(200);
        $this->get(route('admin.opd-admins.index'))->assertStatus(200);
        $this->get(route('admin.pembimbing.index'))->assertStatus(200);
        $this->get(route('admin.applications.index'))->assertStatus(200);
        $this->get(route('admin.active-interns.index'))->assertStatus(200);
        $this->get(route('admin.certificates.index'))->assertStatus(200);
        $this->get(route('admin.surveys.index'))->assertStatus(200);
        $this->get(route('admin.settings.index'))->assertStatus(200);
        $this->get(route('admin.landing-contents.index'))->assertStatus(200);

        // Super Admin accessing positions redirects to OPD management (decentralized model)
        $this->get(route('admin.positions.index'))->assertRedirect(route('admin.opds.index'));
    }

    public function test_admin_opd_can_access_opd_modules_and_is_strictly_forbidden_from_superadmin_features()
    {
        $this->actingAs($this->adminOpd);

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.positions.index'))->assertStatus(200);
        $this->get(route('admin.pembimbing.index'))->assertStatus(200);
        $this->get(route('admin.applications.index'))->assertStatus(200);
        $this->get(route('admin.active-interns.index'))->assertStatus(200);
        $this->get(route('admin.certificates.index'))->assertStatus(200);

        // Security boundaries: Admin OPD cannot manage Global Settings, other OPD Admins, or Central Surveys
        $this->get(route('admin.settings.index'))->assertStatus(403);
        $this->get(route('admin.opd-admins.index'))->assertStatus(403);
        $this->get(route('admin.surveys.index'))->assertStatus(403);
    }

    public function test_pembimbing_can_access_mentorship_modules_and_cannot_access_admin_routes()
    {
        $this->actingAs($this->pembimbing);

        $this->get(route('pembimbing.dashboard'))->assertStatus(200);
        $this->get(route('pembimbing.materials.index'))->assertStatus(200);
        $this->get(route('pembimbing.materials.create'))->assertStatus(200);
        $this->get(route('pembimbing.tasks.index'))->assertStatus(200);
        $this->get(route('pembimbing.profile.index'))->assertStatus(200);

        // Security boundaries: Certificates are centralized to OPD Admin; Pembimbing is forbidden
        $this->get(route('pembimbing.certificates.index'))->assertStatus(403);

        // Security boundary: Pembimbing cannot access admin dashboard
        $this->get(route('admin.dashboard'))->assertStatus(403);
    }

    public function test_peserta_can_access_participant_portal_and_cannot_access_admin_or_mentor_routes()
    {
        $this->actingAs($this->peserta);

        $this->get(route('participant.dashboard'))->assertStatus(200);
        $this->get(route('participant.profile.index'))->assertStatus(200);
        $this->get(route('participant.registrations.index'))->assertStatus(200);
        $this->get(route('participant.materials.index'))->assertStatus(200);
        $this->get(route('participant.daily-checkins.index'))->assertStatus(200);
        $this->get(route('participant.certificates.index'))->assertStatus(200);

        // Security boundary: Peserta cannot access admin or mentor dashboards
        $this->get(route('admin.dashboard'))->assertStatus(403);
        $this->get(route('pembimbing.dashboard'))->assertStatus(403);
    }
}

