<?php

namespace Tests\Feature\Admin;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Mail\CertificateIssuedMail;
use App\Models\Division;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $adminOpd1;
    protected User $adminOpd2;
    protected Opd $opd1;
    protected Opd $opd2;
    protected Division $divisi1;
    protected Division $divisi2;
    protected Position $posisi1;
    protected Position $posisi2;
    protected User $intern1;
    protected User $intern2;
    protected Registration $regEligible;
    protected Registration $regNotEligible;
    protected Registration $regOpd2;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');

        $this->superAdmin = User::factory()->create([
            'name'   => 'Super Admin Tuban',
            'email'  => 'superadmin@tubankab.go.id',
            'role'   => UserRole::SuperAdmin,
            'opd_id' => null,
        ]);

        $this->opd1 = Opd::create([
            'name'      => 'Dinas Komunikasi dan Informatika Tuban',
            'code'      => 'DISKOMINFO',
            'slug'      => 'diskominfo',
            'is_active' => true,
        ]);

        $this->opd2 = Opd::create([
            'name'      => 'Dinas Kesehatan Tuban',
            'code'      => 'DINKES',
            'slug'      => 'dinkes',
            'is_active' => true,
        ]);

        $this->adminOpd1 = User::factory()->create([
            'name'   => 'Admin OPD Kominfo',
            'email'  => 'adminkominfo@tubankab.go.id',
            'role'   => UserRole::Admin,
            'opd_id' => $this->opd1->id,
        ]);

        $this->adminOpd2 = User::factory()->create([
            'name'   => 'Admin OPD Dinkes',
            'email'  => 'admindinkes@tubankab.go.id',
            'role'   => UserRole::Admin,
            'opd_id' => $this->opd2->id,
        ]);

        $this->divisi1 = Division::create([
            'nama_divisi' => 'Aplikasi dan Informatika',
            'opd_id'      => $this->opd1->id,
        ]);

        $this->divisi2 = Division::create([
            'nama_divisi' => 'Pelayanan Medis',
            'opd_id'      => $this->opd2->id,
        ]);

        $this->posisi1 = Position::create([
            'nama_posisi'  => 'Software Engineer Intern',
            'slug'         => 'software-engineer-intern',
            'deskripsi'    => 'Pengembangan aplikasi',
            'kuota'        => 5,
            'status'       => \App\Enums\PositionStatus::Aktif,
            'opd_id'       => $this->opd1->id,
            'division_id'  => $this->divisi1->id,
        ]);

        $this->posisi2 = Position::create([
            'nama_posisi'  => 'Asisten Lab Dinkes',
            'slug'         => 'asisten-lab-dinkes',
            'deskripsi'    => 'Lab Dinkes',
            'kuota'        => 5,
            'status'       => \App\Enums\PositionStatus::Aktif,
            'opd_id'       => $this->opd2->id,
            'division_id'  => $this->divisi2->id,
        ]);

        $this->intern1 = User::factory()->create([
            'name'        => 'Budi Peserta Kominfo',
            'email'       => 'budi@example.com',
            'role'        => UserRole::Peserta,
            'opd_id'      => $this->opd1->id,
            'division_id' => $this->divisi1->id,
        ]);

        Profile::create([
            'user_id'          => $this->intern1->id,
            'nama_lengkap'     => 'Budi Peserta Kominfo',
            'participant_type' => ParticipantType::University,
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'institusi'        => 'Universitas Tuban',
            'jurusan'          => 'Teknik Informatika',
        ]);

        $this->intern2 = User::factory()->create([
            'name'        => 'Siti Peserta Dinkes',
            'email'       => 'siti@example.com',
            'role'        => UserRole::Peserta,
            'opd_id'      => $this->opd2->id,
            'division_id' => $this->divisi2->id,
        ]);

        Profile::create([
            'user_id'          => $this->intern2->id,
            'nama_lengkap'     => 'Siti Peserta Dinkes',
            'participant_type' => ParticipantType::University,
            'jenis_kelamin'    => JenisKelamin::Perempuan,
            'institusi'        => 'Poltekkes Tuban',
            'jurusan'          => 'Kesehatan Lingkungan',
        ]);

        // Eligible (periode_selesai in the past)
        $this->regEligible = Registration::create([
            'nomor_pendaftaran' => 'MAGANG-KOMINFO-001',
            'user_id'           => $this->intern1->id,
            'position_id'       => $this->posisi1->id,
            'opd_id'            => $this->opd1->id,
            'status'            => RegistrationStatus::Accepted,
            'periode_mulai'     => now()->subMonths(3),
            'periode_selesai'   => now()->subDay(),
        ]);

        // Not eligible (periode_selesai still in the future)
        $this->regNotEligible = Registration::create([
            'nomor_pendaftaran' => 'MAGANG-KOMINFO-002',
            'user_id'           => $this->intern1->id,
            'position_id'       => $this->posisi1->id,
            'opd_id'            => $this->opd1->id,
            'status'            => RegistrationStatus::Accepted,
            'periode_mulai'     => now()->subMonth(),
            'periode_selesai'   => now()->addMonth(),
        ]);

        // Reg OPD 2
        $this->regOpd2 = Registration::create([
            'nomor_pendaftaran' => 'MAGANG-DINKES-001',
            'user_id'           => $this->intern2->id,
            'position_id'       => $this->posisi2->id,
            'opd_id'            => $this->opd2->id,
            'status'            => RegistrationStatus::Accepted,
            'periode_mulai'     => now()->subMonths(3),
            'periode_selesai'   => now()->subDay(),
        ]);
    }

    public function test_sidebar_contains_sertifikat_magang_for_admin_opd(): void
    {
        $response = $this->actingAs($this->adminOpd1)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('Sertifikat Magang');
        $response->assertSee(route('admin.certificates.index'));
    }

    public function test_sidebar_contains_sertifikat_magang_for_super_admin(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('Sertifikat Magang');
        $response->assertSee(route('admin.certificates.index'));
    }

    public function test_admin_opd_can_access_certificates_index_scoped_to_their_opd(): void
    {
        $response = $this->actingAs($this->adminOpd1)->get(route('admin.certificates.index'));
        $response->assertOk();
        $response->assertSee('MAGANG-KOMINFO-001');
        $response->assertSee('MAGANG-KOMINFO-002');
        $response->assertDontSee('MAGANG-DINKES-001');

        // Eligible intern has active upload button, ongoing intern has locked/dimmed button
        $response->assertSee('Unggah Sertifikat');
        $response->assertSee('Belum Waktunya');
    }

    public function test_super_admin_requires_opd_selection_or_all(): void
    {
        // 1. Without opd_id filter -> shows prompt
        $response = $this->actingAs($this->superAdmin)->get(route('admin.certificates.index'));
        $response->assertOk();
        $response->assertSee('Pilih Wilayah OPD Terlebih Dahulu');

        // 2. With opd_id = all -> shows all
        $responseAll = $this->actingAs($this->superAdmin)->get(route('admin.certificates.index', ['opd_id' => 'all']));
        $responseAll->assertOk();
        $responseAll->assertSee('MAGANG-KOMINFO-001');
        $responseAll->assertSee('MAGANG-DINKES-001');

        // 3. With specific opd_id
        $responseOpd1 = $this->actingAs($this->superAdmin)->get(route('admin.certificates.index', ['opd_id' => $this->opd1->id]));
        $responseOpd1->assertOk();
        $responseOpd1->assertSee('MAGANG-KOMINFO-001');
        $responseOpd1->assertDontSee('MAGANG-DINKES-001');

        // Super Admin index shows View Only and does not show action buttons (Upload, Ganti, Hapus)
        $responseOpd1->assertSee('View Only');
        $responseOpd1->assertDontSee('Unggah Sertifikat');
        $responseOpd1->assertDontSee('data-bs-target="#modalReplaceCert-');
        $responseOpd1->assertDontSee('data-bs-target="#modalUploadCert-');
        $responseOpd1->assertDontSee(route('admin.certificates.delete', $this->regEligible->id));
    }

    public function test_super_admin_is_forbidden_from_uploading_or_deleting_certificates(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_superadmin.pdf', 1024, 'application/pdf');

        // Super Admin dilarang upload sertifikat
        $responseUpload = $this->actingAs($this->superAdmin)
            ->post(route('admin.certificates.upload', $this->regEligible->id), [
                'certificate' => $fakePdf,
            ]);
        $responseUpload->assertForbidden();

        // Super Admin dilarang hapus sertifikat
        $responseDelete = $this->actingAs($this->superAdmin)
            ->delete(route('admin.certificates.delete', $this->regEligible->id));
        $responseDelete->assertForbidden();
    }

    public function test_super_admin_is_forbidden_from_toggling_active_intern_status(): void
    {
        // Super Admin dilarang menonaktifkan atau mengaktifkan kembali magang
        $responseToggle = $this->actingAs($this->superAdmin)
            ->patch(route('admin.active-interns.toggle-status', $this->regEligible->id), [
                'catatan_penonaktifan' => 'Upaya nonaktif oleh Super Admin',
            ]);
        $responseToggle->assertForbidden();

        // View detail peserta untuk Super Admin tidak memuat tombol aksi apapun (View Only bersih)
        $responseShow = $this->actingAs($this->superAdmin)
            ->get(route('admin.active-interns.show', $this->regEligible->id));
        $responseShow->assertOk();
        $responseShow->assertDontSee('Nonaktifkan Magang Peserta');
        $responseShow->assertDontSee('Aktifkan Kembali Magang');
    }

    public function test_super_admin_has_monitoring_view_for_pembimbing_and_is_forbidden_from_modifications(): void
    {
        $division = \App\Models\Division::create([
            'nama_divisi' => 'Teknologi Informasi',
            'opd_id'      => $this->opd1->id,
        ]);

        $pembimbing = User::create([
            'name'        => 'Pembimbing Uji',
            'email'       => 'mentor_uji@tubankab.go.id',
            'password'    => bcrypt('password123'),
            'role'        => UserRole::Pembimbing,
            'nip'         => '198701012015011009',
            'opd_id'      => $this->opd1->id,
            'division_id' => $division->id,
        ]);

        // 1. Super Admin melihat tampilan monitoring awal (wajib pilih OPD terlebih dahulu)
        $response = $this->actingAs($this->superAdmin)->get(route('admin.pembimbing.index'));
        $response->assertOk();
        $response->assertSee('Monitoring Pembimbing Magang Seluruh Tuban');
        $response->assertSee('Pilih Wilayah OPD Terlebih Dahulu');
        $response->assertDontSee('Tambah Pembimbing');

        // 1b. Saat Super Admin memilih Wilayah OPD (atau opsi all), data tampil
        $responseFiltered = $this->actingAs($this->superAdmin)->get(route('admin.pembimbing.index', ['opd_id' => 'all']));
        $responseFiltered->assertOk();
        $responseFiltered->assertSee('Pembimbing Uji');
        $responseFiltered->assertSee('Ekspor PDF');

        // 2. Super Admin dilarang mengakses form create dan store
        $this->actingAs($this->superAdmin)->get(route('admin.pembimbing.create'))->assertForbidden();
        $this->actingAs($this->superAdmin)->post(route('admin.pembimbing.store'), [
            'name'        => 'Mentor Baru',
            'nip'         => '199901012020011001',
            'email'       => 'mentor_baru@tubankab.go.id',
            'password'    => 'password123',
            'division_id' => $division->id,
        ])->assertForbidden();

        // 3. Super Admin dilarang mengedit atau menghapus pembimbing
        $this->actingAs($this->superAdmin)->get(route('admin.pembimbing.edit', $pembimbing->id))->assertForbidden();
        $this->actingAs($this->superAdmin)->put(route('admin.pembimbing.update', $pembimbing->id), [
            'name'        => 'Mentor Diubah',
            'nip'         => '198701012015011009',
            'email'       => 'mentor_uji@tubankab.go.id',
            'division_id' => $division->id,
        ])->assertForbidden();
        $this->actingAs($this->superAdmin)->delete(route('admin.pembimbing.destroy', $pembimbing->id))->assertForbidden();

        // 4. Admin OPD dapat melihat tombol Tambah Pembimbing dan mengakses form create
        $responseOpd = $this->actingAs($this->adminOpd1)->get(route('admin.pembimbing.index'));
        $responseOpd->assertOk();
        $responseOpd->assertSee('Tambah Pembimbing');
        $responseOpd->assertDontSee('Monitoring Pembimbing Magang Seluruh Tuban');
        $this->actingAs($this->adminOpd1)->get(route('admin.pembimbing.create'))->assertOk();
    }

    public function test_admin_opd_can_upload_pdf_certificate_for_eligible_intern(): void
    {
        Mail::fake();

        $fakePdf = UploadedFile::fake()->create('sertifikat_resmi.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->adminOpd1)
            ->post(route('admin.certificates.upload', $this->regEligible->id), [
                'certificate' => $fakePdf,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->regEligible->refresh();
        $this->assertNotEmpty($this->regEligible->certificate_path);
        $this->assertNotNull($this->regEligible->certificate_issued_at);
        $this->assertTrue(Storage::disk('public')->exists($this->regEligible->certificate_path));

        Mail::assertSent(CertificateIssuedMail::class);
    }

    public function test_admin_opd_cannot_upload_certificate_before_end_date(): void
    {
        Mail::fake();

        $fakePdf = UploadedFile::fake()->create('sertifikat_belum_waktunya.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->adminOpd1)
            ->post(route('admin.certificates.upload', $this->regNotEligible->id), [
                'certificate' => $fakePdf,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->regNotEligible->refresh();
        $this->assertNull($this->regNotEligible->certificate_path);
        Mail::assertNothingSent();
    }

    public function test_admin_opd_cannot_upload_or_delete_certificate_of_other_opd(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_cross.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->adminOpd1)
            ->post(route('admin.certificates.upload', $this->regOpd2->id), [
                'certificate' => $fakePdf,
            ]);

        $response->assertForbidden();

        $responseDelete = $this->actingAs($this->adminOpd1)
            ->delete(route('admin.certificates.delete', $this->regOpd2->id));

        $responseDelete->assertForbidden();
    }

    public function test_admin_opd_can_delete_certificate(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_lama.pdf', 500, 'application/pdf');
        $stored = $fakePdf->store('certificates', 'public');
        $this->regEligible->update([
            'certificate_path' => $stored,
            'certificate_issued_at' => now(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($stored));

        $response = $this->actingAs($this->adminOpd1)
            ->delete(route('admin.certificates.delete', $this->regEligible->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->regEligible->refresh();
        $this->assertNull($this->regEligible->certificate_path);
        $this->assertFalse(Storage::disk('public')->exists($stored));
    }

    public function test_admin_opd_can_export_pdf(): void
    {
        $response = $this->actingAs($this->adminOpd1)
            ->get(route('admin.certificates.export_pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
