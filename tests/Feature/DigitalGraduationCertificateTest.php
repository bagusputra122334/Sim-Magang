<?php

namespace Tests\Feature;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Mail\CertificateIssuedMail;
use App\Models\Application;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DigitalGraduationCertificateTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $peserta;
    protected Position $position;
    protected Registration $registration;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');

        $this->admin = User::factory()->create([
            'name'  => 'Admin Magang',
            'email' => 'admin@tubankab.go.id',
            'role'  => UserRole::Admin,
        ]);

        $this->peserta = User::factory()->create([
            'name'  => 'Intern Sukses',
            'email' => 'intern@test.com',
            'role'  => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->peserta->id,
            'nama_lengkap'     => 'Intern Sukses',
            'participant_type' => ParticipantType::University,
            'nis_nim'          => '24050101',
            'nim'              => '24050101',
            'nik'              => '3523010101010001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2002-01-01',
            'jenis_kelamin'    => JenisKelamin::LakiLaki,
            'institusi'        => 'Universitas Tuban',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'semester'         => 6,
            'no_telepon'       => '081234567890',
            'alamat'           => 'Jl. Pahlawan Tuban',
        ]);

        $this->position = Position::create([
            'nama_posisi'  => 'Web Developer Intern',
            'slug'         => 'web-developer-intern',
            'deskripsi'    => 'Pengembangan aplikasi pemerintahan',
            'persyaratan'  => 'Laravel & Vue/Tailwind',
            'kuota'        => 5,
            'status'       => \App\Enums\PositionStatus::Aktif,
        ]);

        $this->registration = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'proposal_magang_path' => 'dokumen/proposal.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->subDays(30),
            'periode_selesai'      => now()->addDays(30),
        ]);
    }

    public function test_admin_can_upload_valid_pdf_certificate(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_kelulusan.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.applications.certificate.upload', $this->registration->id), [
                'certificate' => $fakePdf,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->registration->refresh();
        $this->assertNotEmpty($this->registration->certificate_path);
        $this->assertNotNull($this->registration->certificate_issued_at);
        $this->assertTrue(Storage::disk('public')->exists($this->registration->certificate_path));
    }

    public function test_upload_fails_if_not_pdf(): void
    {
        $fakeTxt = UploadedFile::fake()->create('sertifikat.txt', 100, 'text/plain');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.applications.certificate.upload', $this->registration->id), [
                'certificate' => $fakeTxt,
            ]);

        $response->assertSessionHasErrors('certificate');
    }

    public function test_upload_fails_if_exceeds_5mb(): void
    {
        $largePdf = UploadedFile::fake()->create('huge_certificate.pdf', 6000, 'application/pdf'); // > 5MB

        $response = $this->actingAs($this->admin)
            ->post(route('admin.applications.certificate.upload', $this->registration->id), [
                'certificate' => $largePdf,
            ]);

        $response->assertSessionHasErrors('certificate');
    }

    public function test_admin_can_delete_certificate(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat.pdf', 500, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($storedPath));

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.applications.certificate.delete', $this->registration->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->registration->refresh();
        $this->assertNull($this->registration->certificate_path);
        $this->assertNull($this->registration->certificate_issued_at);
        $this->assertFalse(Storage::disk('public')->exists($storedPath));
    }

    public function test_admin_active_interns_show_view_renders_upload_form_when_empty(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.show', $this->registration->id));

        $response->assertOk();
        $response->assertSee('SERTIFIKAT KELULUSAN MAGANG');
        $response->assertSee('Belum Diunggah Pembimbing');
        $response->assertDontSee('Kirim / Upload Sertifikat');
        $response->assertDontSee('Ganti Sertifikat');
        $response->assertDontSee('Hapus Sertifikat');
    }

    public function test_admin_active_interns_show_view_renders_action_buttons_when_uploaded(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_kelulusan.pdf', 500, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.show', $this->registration->id));

        $response->assertOk();
        $response->assertSee('SERTIFIKAT KELULUSAN MAGANG');
        $response->assertSee('Preview');
        $response->assertSee('Download');
        $response->assertDontSee('Hapus Sertifikat');
        $response->assertDontSee('Ganti Sertifikat');
        $response->assertDontSee('Kirim / Upload Sertifikat');
    }

    public function test_participant_dashboard_shows_disabled_card_before_upload(): void
    {
        $response = $this->actingAs($this->peserta)
            ->get(route('participant.certificates.index'));

        $response->assertOk();
        $response->assertSee('SERTIFIKAT KELULUSAN RESMI');
        $response->assertSee('Sertifikat Belum Tersedia');
    }

    public function test_participant_dashboard_shows_active_card_and_download_button_after_upload(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_kelulusan.pdf', 500, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        $response = $this->actingAs($this->peserta)
            ->get(route('participant.certificates.index'));

        $response->assertOk();
        $response->assertSee('SERTIFIKAT KELULUSAN RESMI');
        $response->assertSee('Unduh Sertifikat Magang (PDF)');
    }

    public function test_participant_can_view_and_download_own_certificate(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_kelulusan.pdf', 500, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        // Participant can view
        $viewResponse = $this->actingAs($this->peserta)
            ->get(route('participant.applications.certificate.view', $this->registration->id));
        $viewResponse->assertOk();

        // Participant can download
        $dlResponse = $this->actingAs($this->peserta)
            ->get(route('participant.applications.certificate.download', $this->registration->id));
        $dlResponse->assertOk();
    }

    public function test_admin_can_upload_and_delete_certificate_via_active_interns_routes(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_kelulusan.pdf', 1024, 'application/pdf');

        $uploadResponse = $this->actingAs($this->admin)
            ->post(route('admin.active-interns.certificate.upload', $this->registration->id), [
                'certificate' => $fakePdf,
            ]);

        $uploadResponse->assertSessionHasNoErrors();
        $uploadResponse->assertRedirect();
        $uploadResponse->assertSessionHas('success');

        $this->registration->refresh();
        $this->assertNotEmpty($this->registration->certificate_path);

        // View via active-interns route
        $viewResponse = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.certificate.view', $this->registration->id));
        $viewResponse->assertOk();

        // Download via active-interns route
        $downloadResponse = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.certificate.download', $this->registration->id));
        $downloadResponse->assertOk();

        // Delete via active-interns route
        $deleteResponse = $this->actingAs($this->admin)
            ->delete(route('admin.active-interns.certificate.delete', $this->registration->id));
        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success');

        $this->registration->refresh();
        $this->assertNull($this->registration->certificate_path);
    }

    public function test_admin_active_interns_show_view_is_check_only_and_does_not_render_action_buttons(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat_kelulusan.pdf', 500, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.show', $this->registration->id));

        $response->assertOk();
        $response->assertSee('Preview');
        $response->assertSee('Download');
        $response->assertDontSee('Ganti Sertifikat');
        $response->assertDontSee('Hapus Sertifikat');
        $response->assertDontSee('Kirim / Upload Sertifikat');
        $response->assertDontSee('replaceCertModal');
    }

    public function test_admin_can_replace_existing_certificate_and_overwrites_old_file(): void
    {
        $oldPdf = UploadedFile::fake()->create('sertifikat_lama.pdf', 500, 'application/pdf');
        $oldPath = $oldPdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $oldPath,
            'certificate_issued_at' => now()->subDay(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($oldPath));

        $newPdf = UploadedFile::fake()->create('sertifikat_baru.pdf', 800, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.active-interns.certificate.upload', $this->registration->id), [
                'certificate' => $newPdf,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->registration->refresh();

        // Old file must be deleted from storage
        $this->assertFalse(Storage::disk('public')->exists($oldPath));

        // New file must exist
        $this->assertNotEmpty($this->registration->certificate_path);
        $this->assertNotEquals($oldPath, $this->registration->certificate_path);
        $this->assertTrue(Storage::disk('public')->exists($this->registration->certificate_path));
    }

    public function test_operational_status_badge_displays_dynamic_color_and_text_for_completed_interns(): void
    {
        // Set intern as completed (past periode_selesai)
        $this->registration->update([
            'periode_mulai'   => now()->subMonths(3)->toDateString(),
            'periode_selesai' => now()->subDay()->toDateString(),
            'certificate_path' => null,
            'certificate_issued_at' => null,
        ]);

        // 1. Kondisi A: Selesai Magang + Belum Ada Sertifikat (Netral / Menunggu Sertifikat)
        $responseA = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.index'));
        $responseA->assertOk();
        $responseA->assertSee('Selesai (Menunggu Sertifikat)');
        $responseA->assertSee('text-slate-800');

        $showResponseA = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.show', $this->registration->id));
        $showResponseA->assertOk();
        $showResponseA->assertSee('Selesai (Menunggu Sertifikat)');

        // 2. Kondisi B: Selesai Magang + Sudah Diterbitkan (Hijau Sukses Mencolok)
        $fakePdf = UploadedFile::fake()->create('sertifikat.pdf', 500, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);

        $responseB = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.index'));
        $responseB->assertOk();
        $responseB->assertSee('Selesai &amp; Sertifikat Terbit', false);
        $responseB->assertSee('bg-emerald-100');

        $showResponseB = $this->actingAs($this->admin)
            ->get(route('admin.active-interns.show', $this->registration->id));
        $showResponseB->assertOk();
        $showResponseB->assertSee('Selesai & Sertifikat Terbit', false);
    }

    public function test_upload_certificate_sends_email_notification_to_participant(): void
    {
        Mail::fake();

        $pdf = UploadedFile::fake()->create('sertifikat-kelulusan.pdf', 300, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.applications.certificate.upload', $this->registration->id), [
                'certificate' => $pdf,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(CertificateIssuedMail::class, function ($mail) {
            return $mail->hasTo($this->peserta->email)
                && $mail->application->id === $this->registration->id
                && $mail->isReplacing === false;
        });
    }

    public function test_replace_certificate_sends_email_notification_with_replacing_flag(): void
    {
        Mail::fake();

        // 1. Initial upload
        $oldPdf = UploadedFile::fake()->create('lama.pdf', 200, 'application/pdf');
        $this->actingAs($this->admin)
            ->post(route('admin.applications.certificate.upload', $this->registration->id), [
                'certificate' => $oldPdf,
            ]);

        $this->registration->refresh();
        $this->assertNotNull($this->registration->certificate_path);

        // 2. Re-upload / replace
        $newPdf = UploadedFile::fake()->create('revisi.pdf', 400, 'application/pdf');
        $replaceResponse = $this->actingAs($this->admin)
            ->post(route('admin.applications.certificate.upload', $this->registration->id), [
                'certificate' => $newPdf,
            ]);

        $replaceResponse->assertRedirect();
        $replaceResponse->assertSessionHas('success');

        Mail::assertSent(CertificateIssuedMail::class, function ($mail) {
            return $mail->hasTo($this->peserta->email)
                && $mail->application->id === $this->registration->id
                && $mail->isReplacing === true;
        });
    }

    public function test_certificate_issued_mail_renders_correctly(): void
    {
        $fakePdf = UploadedFile::fake()->create('sertifikat.pdf', 500, 'application/pdf');
        $storedPath = $fakePdf->store('certificates', 'public');

        $this->registration->update([
            'certificate_path'      => $storedPath,
            'certificate_issued_at' => now(),
        ]);
        $this->registration->loadMissing(['user.profile', 'position']);

        $mail = new CertificateIssuedMail($this->registration, false);
        $mail->assertHasSubject('[SIMAGANG] 🎓 Selamat! Sertifikat Kelulusan Magang Digital Telah Terbit — ' . $this->registration->nomor_pendaftaran);
        $mail->assertSeeInHtml($this->peserta->name);
        $mail->assertSeeInHtml($this->registration->nomor_pendaftaran);
        $mail->assertSeeInHtml('Unduh Sertifikat Kelulusan (PDF)');
    }
}
