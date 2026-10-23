<?php

namespace Tests\Feature\Pembimbing;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Mail\CertificateIssuedMail;
use App\Models\Division;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembimbingCertificateTest extends TestCase
{
    use RefreshDatabase;

    protected User $mentorDivision1;
    protected User $mentorDivision2;
    protected User $internDivision1;
    protected User $internDivision2;
    protected Division $division1;
    protected Division $division2;
    protected Position $position1;
    protected Position $position2;
    protected Registration $regEligible;
    protected Registration $regNotEligible;
    protected Registration $regOtherDivision;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');

        // Create Admin (takes ID 1)
        User::factory()->create([
            'name'  => 'Admin SIMAGANG',
            'email' => 'admin.root@tubankab.go.id',
            'role'  => UserRole::Admin,
        ]);

        // Create Divisions
        $this->division1 = Division::create([
            'nama_divisi' => 'Aplikasi dan Informatika',
            'deskripsi'   => 'Divisi TI',
        ]);

        $this->division2 = Division::create([
            'nama_divisi' => 'Informasi dan Komunikasi Publik',
            'deskripsi'   => 'Divisi IKP',
        ]);

        // Create Mentors
        $this->mentorDivision1 = User::factory()->create([
            'name'        => 'Pembimbing Divisi TI',
            'email'       => 'mentor.ti@tubankab.go.id',
            'role'        => UserRole::Pembimbing,
            'division_id' => $this->division1->id,
            'nip'         => '198001012005011001',
        ]);

        $this->mentorDivision2 = User::factory()->create([
            'name'        => 'Pembimbing Divisi IKP',
            'email'       => 'mentor.ikp@tubankab.go.id',
            'role'        => UserRole::Pembimbing,
            'division_id' => $this->division2->id,
            'nip'         => '198502022010011002',
        ]);

        // Create Positions
        $this->position1 = Position::create([
            'nama_posisi'  => 'Web Programmer Intern',
            'slug'         => 'web-programmer-intern',
            'deskripsi'    => 'Pengembangan aplikasi',
            'persyaratan'  => 'PHP, Laravel',
            'kuota'        => 5,
            'mentor_name'  => $this->mentorDivision1->name,
            'mentor_nip'   => $this->mentorDivision1->nip,
            'status'       => \App\Enums\PositionStatus::Aktif,
        ]);

        $this->position2 = Position::create([
            'nama_posisi'  => 'Content Creator Intern',
            'slug'         => 'content-creator-intern',
            'deskripsi'    => 'Pembuatan konten',
            'persyaratan'  => 'Design, Video',
            'kuota'        => 5,
            'mentor_name'  => $this->mentorDivision2->name,
            'mentor_nip'   => $this->mentorDivision2->nip,
            'status'       => \App\Enums\PositionStatus::Aktif,
        ]);

        // Create Intern 1 in Division 1 (Eligible: end_date is past / today)
        $this->internDivision1 = User::factory()->create([
            'name'        => 'Budi Santoso TI',
            'email'       => 'budi.santoso@example.com',
            'role'        => UserRole::Peserta,
            'division_id' => $this->division1->id,
        ]);

        Profile::create([
            'user_id'          => $this->internDivision1->id,
            'nama_lengkap'     => 'Budi Santoso TI',
            'participant_type' => ParticipantType::University,
            'nis_nim'          => '12345678',
            'nik'              => '3523010101010001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-01-01',
            'no_telepon'       => '081234567890',
            'alamat'           => 'Jl. Pahlawan No. 1 Tuban',
            'tahun_angkatan'   => '2022',
            'institusi'        => 'Politeknik Negeri Tuban',
            'jurusan'          => 'Teknologi Informasi',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
        ]);

        $this->regEligible = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $this->internDivision1->id,
            'position_id'          => $this->position1->id,
            'cv_path'              => 'cv1.pdf',
            'surat_pengantar_path' => 'sp1.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now()->subMonths(3),
            'periode_mulai'        => now()->subMonths(3)->toDateString(),
            'periode_selesai'      => now()->subDay()->toDateString(), // Already ended (eligible)
        ]);

        // Intern 2 in Division 1 (NOT Eligible: end_date is in the future)
        $internOngoing = User::factory()->create([
            'name'        => 'Citra Lestari TI',
            'email'       => 'citra.lestari@example.com',
            'role'        => UserRole::Peserta,
            'division_id' => $this->division1->id,
        ]);

        Profile::create([
            'user_id'          => $internOngoing->id,
            'nama_lengkap'     => 'Citra Lestari TI',
            'participant_type' => ParticipantType::University,
            'nis_nim'          => '12345679',
            'nik'              => '3523010101010002',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2003-02-02',
            'no_telepon'       => '081234567891',
            'alamat'           => 'Jl. Mastrip No. 5 Tuban',
            'tahun_angkatan'   => '2022',
            'institusi'        => 'Universitas Brawijaya',
            'jurusan'          => 'Sistem Informasi',
            'jenis_kelamin'    => JenisKelamin::Perempuan,
        ]);

        $this->regNotEligible = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0002',
            'user_id'              => $internOngoing->id,
            'position_id'          => $this->position1->id,
            'cv_path'              => 'cv2.pdf',
            'surat_pengantar_path' => 'sp2.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now()->subMonth(),
            'periode_mulai'        => now()->subMonth()->toDateString(),
            'periode_selesai'      => now()->addMonth()->toDateString(), // 1 month left (NOT eligible)
        ]);

        // Intern 3 in Division 2 (Other division)
        $this->internDivision2 = User::factory()->create([
            'name'        => 'Dedi Kusuma IKP',
            'email'       => 'dedi.kusuma@example.com',
            'role'        => UserRole::Peserta,
            'division_id' => $this->division2->id,
        ]);

        Profile::create([
            'user_id'          => $this->internDivision2->id,
            'nama_lengkap'     => 'Dedi Kusuma IKP',
            'participant_type' => ParticipantType::Student,
            'nis_nim'          => '55555555',
            'nik'              => '3523010101010003',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2006-03-03',
            'no_telepon'       => '081234567892',
            'alamat'           => 'Jl. Veteran No. 10 Tuban',
            'tahun_angkatan'   => '2023',
            'institusi'        => 'SMK Negeri 1 Tuban',
            'jurusan'          => 'Multimedia',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
        ]);

        $this->regOtherDivision = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0003',
            'user_id'              => $this->internDivision2->id,
            'position_id'          => $this->position2->id,
            'cv_path'              => 'cv3.pdf',
            'surat_pengantar_path' => 'sp3.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now()->subMonths(2),
            'periode_mulai'        => now()->subMonths(2)->toDateString(),
            'periode_selesai'      => now()->subDay()->toDateString(),
        ]);
    }

    public function test_guest_cannot_access_pembimbing_certificates(): void
    {
        $response = $this->get(route('pembimbing.certificates.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_participant_cannot_access_pembimbing_certificates(): void
    {
        $response = $this->actingAs($this->internDivision1)
            ->get(route('pembimbing.certificates.index'));

        $response->assertForbidden();
    }

    public function test_pembimbing_can_access_certificates_index_and_sees_only_own_division_mentees(): void
    {
        $response = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.index'));

        $response->assertOk();
        $response->assertSee('Sertifikat Kelulusan Magang Digital');
        $response->assertSee('Budi Santoso TI');
        $response->assertSee('Citra Lestari TI');
        // Mentees from division 2 must NOT be seen
        $response->assertDontSee('Dedi Kusuma IKP');
    }

    public function test_sidebar_contains_sertifikat_magang_menu_for_pembimbing(): void
    {
        $response = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.dashboard'));

        $response->assertOk();
        $response->assertSee('Sertifikat Magang');
        $response->assertSee(route('pembimbing.certificates.index'));
    }

    public function test_pembimbing_can_upload_pdf_certificate_when_intern_reaches_or_passed_end_date(): void
    {
        Mail::fake();

        $fakePdf = UploadedFile::fake()->create('sertifikat_budi.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->mentorDivision1)
            ->post(route('pembimbing.certificates.upload', $this->regEligible->id), [
                'certificate' => $fakePdf,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->regEligible->refresh();
        $this->assertNotEmpty($this->regEligible->certificate_path);
        $this->assertNotNull($this->regEligible->certificate_issued_at);
        $this->assertTrue(Storage::disk('public')->exists($this->regEligible->certificate_path));

        // Mail notification must be sent
        Mail::assertSent(CertificateIssuedMail::class, function ($mail) {
            return $mail->hasTo($this->internDivision1->email)
                && $mail->application->id === $this->regEligible->id
                && $mail->isReplacing === false;
        });
    }

    public function test_pembimbing_CANNOT_upload_certificate_before_end_date_time_gated(): void
    {
        Mail::fake();

        $fakePdf = UploadedFile::fake()->create('sertifikat_citra.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->mentorDivision1)
            ->post(route('pembimbing.certificates.upload', $this->regNotEligible->id), [
                'certificate' => $fakePdf,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->regNotEligible->refresh();
        $this->assertNull($this->regNotEligible->certificate_path);
        $this->assertNull($this->regNotEligible->certificate_issued_at);

        Mail::assertNothingSent();
    }

    public function test_pembimbing_cannot_upload_certificate_for_mentee_in_different_division(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_dedi.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->mentorDivision1)
            ->post(route('pembimbing.certificates.upload', $this->regOtherDivision->id), [
                'certificate' => $fakePdf,
            ]);

        $response->assertForbidden();
    }

    public function test_upload_validates_mimes_and_max_size(): void
    {
        // 1. Non PDF file
        $fakeTxt = UploadedFile::fake()->create('dokumen.txt', 500, 'text/plain');
        $response = $this->actingAs($this->mentorDivision1)
            ->post(route('pembimbing.certificates.upload', $this->regEligible->id), [
                'certificate' => $fakeTxt,
            ]);
        $response->assertSessionHasErrors('certificate');

        // 2. Oversized PDF (> 5MB)
        $largePdf = UploadedFile::fake()->create('huge.pdf', 6000, 'application/pdf');
        $response2 = $this->actingAs($this->mentorDivision1)
            ->post(route('pembimbing.certificates.upload', $this->regEligible->id), [
                'certificate' => $largePdf,
            ]);
        $response2->assertSessionHasErrors('certificate');
    }

    public function test_pembimbing_can_replace_certificate_and_overwrites_old_file(): void
    {
        Mail::fake();

        // Initial certificate
        $oldPdf = UploadedFile::fake()->create('sertifikat_lama.pdf', 400, 'application/pdf');
        $oldPath = $oldPdf->store('certificates', 'public');
        $this->regEligible->update([
            'certificate_path'      => $oldPath,
            'certificate_issued_at' => now()->subDays(2),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($oldPath));

        // Replace with new certificate
        $newPdf = UploadedFile::fake()->create('sertifikat_revisi.pdf', 600, 'application/pdf');
        $response = $this->actingAs($this->mentorDivision1)
            ->post(route('pembimbing.certificates.upload', $this->regEligible->id), [
                'certificate' => $newPdf,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->regEligible->refresh();

        // Old file must be deleted
        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        // New file must exist
        $this->assertTrue(Storage::disk('public')->exists($this->regEligible->certificate_path));

        // Mail notification must be sent with isReplacing = true
        Mail::assertSent(CertificateIssuedMail::class, function ($mail) {
            return $mail->hasTo($this->internDivision1->email)
                && $mail->isReplacing === true;
        });
    }

    public function test_pembimbing_can_download_and_preview_certificate(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_final.pdf', 300, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->regEligible->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        // Preview (View)
        $viewResponse = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.view', $this->regEligible->id));
        $viewResponse->assertOk();
        $viewResponse->assertHeader('Content-Type', 'application/pdf');

        // Download
        $downloadResponse = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.download', $this->regEligible->id));
        $downloadResponse->assertOk();
    }

    public function test_pembimbing_view_renders_locked_indicator_for_intern_before_end_date(): void
    {
        $response = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.index'));

        $response->assertOk();

        // Eligible intern should have active Unggah Sertifikat modal/button
        $response->assertSee('Unggah Sertifikat');

        // Not yet eligible intern should display locked indicator
        $response->assertSee('Belum Waktunya Kelulusan');
    }

    public function test_pembimbing_can_filter_and_search_certificates(): void
    {
        // 1. Search by name "Citra"
        $searchResponse = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.index', ['search' => 'Citra']));

        $searchResponse->assertOk();
        $searchResponse->assertSee('Citra Lestari TI');
        $searchResponse->assertDontSee('Budi Santoso TI');

        // 2. Filter by status completed
        $completedResponse = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.index', ['status' => 'completed']));

        $completedResponse->assertOk();
        $completedResponse->assertSee('Budi Santoso TI');
        $completedResponse->assertDontSee('Citra Lestari TI');

        // 3. Filter by status active (ongoing)
        $activeResponse = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.index', ['status' => 'active']));

        $activeResponse->assertOk();
        $activeResponse->assertSee('Citra Lestari TI');
        $activeResponse->assertDontSee('Budi Santoso TI');

        // 4. Per page parameter (5, 10, 15, 25, 50)
        $perPageResponse = $this->actingAs($this->mentorDivision1)
            ->get(route('pembimbing.certificates.index', ['per_page' => 5]));

        $perPageResponse->assertOk();
        $perPageResponse->assertSee('5 baris / hal');
    }
}
