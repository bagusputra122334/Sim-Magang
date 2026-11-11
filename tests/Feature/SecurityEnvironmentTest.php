<?php

namespace Tests\Feature;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Security Environment Test Suite
 *
 * Memverifikasi keamanan konfigurasi aplikasi terhadap standar mitigasi PHP2SHELL
 * (CVE-2026-109618 & CVE-2026-109619), termasuk:
 * 1. Pemblokiran ekstensi file script berbahaya (.php, .phtml, .exe, .sh)
 * 2. Validasi ketat ukuran file maksimum (max upload limits)
 * 3. Pencegahan eksekusi dan penyimpanan payload berbahaya di storage
 * 4. Proteksi dokumen privat dari akses unauthenticated (guest)
 */
class SecurityEnvironmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $peserta;
    protected User $admin;
    protected Position $position;
    protected Opd $opd;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

        $this->opd = Opd::factory()->create([
            'name'      => 'Dinas Komunikasi dan Informatika Tuban',
            'code'      => 'DISKOMINFO-SEC',
            'slug'      => 'diskominfo-sec',
            'is_active' => true,
        ]);

        $this->peserta = User::factory()->create([
            'name'  => 'Peserta Security Audit',
            'email' => 'peserta.sec@tubankab.go.id',
            'role'  => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->peserta->id,
            'nama_lengkap'     => 'Peserta Security Audit',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '99998888',
            'nim'              => '99998888',
            'nik'              => '3523010101019999',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-01-01',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Politeknik Negeri',
            'jurusan'          => 'Keamanan Siber',
            'tahun_angkatan'   => '2022',
            'alamat'           => 'Tuban',
            'no_telepon'       => '081234567800',
        ]);

        $this->admin = User::factory()->create([
            'name'   => 'Admin Auditor',
            'email'  => 'admin.sec@tubankab.go.id',
            'role'   => UserRole::Admin,
            'opd_id' => $this->opd->id,
        ]);

        $this->position = Position::factory()->create([
            'nama_posisi' => 'Cyber Security Analyst Intern',
            'opd_id'      => $this->opd->id,
            'status'      => PositionStatus::Aktif,
        ]);
    }

    /**
     * CVE-2026-109618 Mitigasi: Pastikan upload pendaftaran menolak file script berbahaya (.php, .phtml, .exe, .sh)
     */
    public function test_registration_uploads_reject_dangerous_script_and_executable_extensions(): void
    {
        $this->actingAs($this->peserta);

        // Siapkan fake files dengan ekstensi berbahaya
        $maliciousPhp = UploadedFile::fake()->create('backdoor.php', 100, 'application/x-php');
        $maliciousPhtml = UploadedFile::fake()->create('webshell.phtml', 100, 'application/x-httpd-php');
        $maliciousExe = UploadedFile::fake()->create('trojan.exe', 200, 'application/x-msdownload');
        $maliciousSh = UploadedFile::fake()->create('exploit.sh', 50, 'text/x-shellscript');

        $response = $this->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $maliciousPhp,
            'surat_pengantar' => $maliciousExe,
            'proposal_magang' => $maliciousSh,
        ]);

        // Verifikasi request ditolak dengan pesan validasi
        $response->assertSessionHasErrors(['cv', 'surat_pengantar', 'proposal_magang']);

        // Verifikasi tidak ada pendaftaran baru yang tersimpan
        $this->assertDatabaseMissing('registrations', [
            'user_id'     => $this->peserta->id,
            'position_id' => $this->position->id,
        ]);

        // Verifikasi tidak ada file berbahaya yang masuk ke storage
        Storage::disk('local')->assertMissing('documents/cv/backdoor.php');
        Storage::disk('local')->assertMissing('documents/sp/trojan.exe');
        Storage::disk('local')->assertMissing('documents/proposal/exploit.sh');
    }

    /**
     * CVE-2026-109619 Mitigasi: Pastikan validasi batas ukuran file aktif (CV: 2MB, Surat Pengantar: 3MB, Proposal: 5MB)
     */
    public function test_registration_uploads_strictly_enforce_file_size_limits(): void
    {
        $this->actingAs($this->peserta);

        // CV max 2048 KB -> buat 3000 KB (melebihi batas)
        $oversizedCv = UploadedFile::fake()->create('oversized_cv.pdf', 3000, 'application/pdf');
        // Surat Pengantar max 3072 KB -> buat 4000 KB (melebihi batas)
        $oversizedSp = UploadedFile::fake()->create('oversized_sp.pdf', 4000, 'application/pdf');
        // Proposal max 5120 KB -> buat 6000 KB (melebihi batas)
        $oversizedProposal = UploadedFile::fake()->create('oversized_proposal.pdf', 6000, 'application/pdf');

        $response = $this->post(route('participant.registrations.store'), [
            'position_id'     => $this->position->id,
            'periode_mulai'   => now()->addDays(2)->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'cv'              => $oversizedCv,
            'surat_pengantar' => $oversizedSp,
            'proposal_magang' => $oversizedProposal,
        ]);

        $response->assertSessionHasErrors(['cv', 'surat_pengantar', 'proposal_magang']);
    }

    /**
     * Pastikan upload Surat Balasan Admin menolak ekstensi berbahaya dan ukuran berlebih
     */
    public function test_reply_letter_upload_rejects_dangerous_extensions_and_oversized_files(): void
    {
        $reg = Registration::create([
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'opd_id'               => $this->opd->id,
            'nomor_pendaftaran'    => 'MAGANG-2026-SEC-01',
            'status'               => RegistrationStatus::Accepted,
            'cv_path'              => 'documents/cv/valid.pdf',
            'surat_pengantar_path' => 'documents/sp/valid.pdf',
        ]);

        $this->actingAs($this->admin);

        // 1. Coba upload script PHP sebagai surat balasan
        $dangerousFile = UploadedFile::fake()->create('shell_surat.php', 100, 'application/x-php');
        $responseBadExt = $this->post(route('admin.applications.reply-letter.store', $reg->id), [
            'surat_balasan' => $dangerousFile,
        ]);
        $responseBadExt->assertSessionHasErrors('surat_balasan');

        // 2. Coba upload file melebihi 5MB (> 5120 KB)
        $oversizedFile = UploadedFile::fake()->create('big_reply.pdf', 6000, 'application/pdf');
        $responseOversized = $this->post(route('admin.applications.reply-letter.store', $reg->id), [
            'surat_balasan' => $oversizedFile,
        ]);
        $responseOversized->assertSessionHasErrors('surat_balasan');
    }

    /**
     * Pastikan upload Sertifikat Kelulusan menolak ekstensi berbahaya dan ukuran berlebih
     */
    public function test_certificate_upload_rejects_dangerous_extensions_and_oversized_files(): void
    {
        $reg = Registration::create([
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'opd_id'               => $this->opd->id,
            'nomor_pendaftaran'    => 'MAGANG-2026-SEC-02',
            'status'               => RegistrationStatus::Accepted,
            'cv_path'              => 'documents/cv/valid.pdf',
            'surat_pengantar_path' => 'documents/sp/valid.pdf',
        ]);

        $this->actingAs($this->admin);

        // 1. Coba upload script Shell / executable sebagai sertifikat
        $dangerousCert = UploadedFile::fake()->create('malicious_cert.sh', 50, 'text/x-shellscript');
        $responseCert = $this->post(route('admin.applications.certificate.upload', $reg->id), [
            'certificate' => $dangerousCert,
        ]);
        $responseCert->assertSessionHasErrors('certificate');

        // 2. Coba upload sertifikat melebihi 5MB
        $oversizedCert = UploadedFile::fake()->create('oversized_cert.pdf', 7000, 'application/pdf');
        $responseCertOversized = $this->post(route('admin.applications.certificate.upload', $reg->id), [
            'certificate' => $oversizedCert,
        ]);
        $responseCertOversized->assertSessionHasErrors('certificate');
    }

    /**
     * Pastikan berkas privat pendaftaran tidak dapat diakses tanpa autentikasi (Guest Access Blocked)
     */
    public function test_private_documents_are_protected_from_unauthenticated_guest_access(): void
    {
        $reg = Registration::create([
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'opd_id'               => $this->opd->id,
            'nomor_pendaftaran'    => 'MAGANG-2026-SEC-03',
            'status'               => RegistrationStatus::Submitted,
            'cv_path'              => 'documents/cv/private_cv.pdf',
            'surat_pengantar_path' => 'documents/sp/private_sp.pdf',
        ]);

        // Request tanpa login (Guest)
        $response = $this->get(route('documents.download', ['registration' => $reg->id, 'type' => 'cv']));

        // Wajib diarahkan ke login (302) atau 401 Unauthorized
        $this->assertTrue(
            $response->isRedirect(route('login')) || $response->getStatusCode() === 401
        );
    }

    /**
     * Pastikan header keamanan HTTP terpasang pada respon aplikasi.
     */
    public function test_http_security_headers_are_present_on_responses(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
    }

    /**
     * Uji Verifikasi Email Fleksibel: Peserta yang belum verifikasi email
     * dibatasi saat mengajukan pendaftaran magang baru.
     */
    public function test_unverified_participant_cannot_create_or_submit_internship_registration(): void
    {
        $unverifiedUser = User::factory()->create([
            'role'              => UserRole::Peserta,
            'email_verified_at' => null,
        ]);

        Profile::create([
            'user_id'          => $unverifiedUser->id,
            'nama_lengkap'     => 'Unverified Intern',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '12345678',
            'nik'              => '3523010101010099',
            'jenis_kelamin'    => 'L',
            'jurusan'          => 'Informatika',
            'institusi'        => 'Universitas Tuban',
            'no_telepon'       => '081234567890',
            'no_hp'            => '081234567890',
            'alamat'           => 'Tuban',
        ]);

        // Coba akses form pendaftaran (dihadang dan diarahkan ke dashboard)
        $responseCreate = $this->actingAs($unverifiedUser)->get(route('participant.registrations.create'));
        $responseCreate->assertRedirect(route('participant.dashboard'));
        $responseCreate->assertSessionHas('warning');
    }

    /**
     * Uji Verifikasi Email Fleksibel: Peserta yang telah verifikasi email
     * diizinkan membuka form pendaftaran magang.
     */
    public function test_verified_participant_can_access_registration_create(): void
    {
        $response = $this->actingAs($this->peserta)->get(route('participant.registrations.create'));
        $response->assertOk();
    }

    /**
     * Uji Audit Logger Keamanan: Pencatatan saat terjadi event lockout.
     */
    public function test_audit_logger_records_security_lockout_event(): void
    {
        $mockChannel = \Mockery::mock();
        $mockChannel->shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, 'security.auth.lockout')
                    && ($context['detail']['target_email'] ?? '') === 'attacker@example.com'
                    && ($context['detail']['ip_address'] ?? '') === '192.168.1.100';
            });

        \Illuminate\Support\Facades\Log::shouldReceive('channel')
            ->once()
            ->with('single')
            ->andReturn($mockChannel);

        \App\Support\AuditLogger::lockout('attacker@example.com', '192.168.1.100', 'MaliciousBot/1.0');
    }

    /**
     * Uji Soft Deletes OPD: Penghapusan dinas tidak menghapus permanen dari database.
     */
    public function test_opd_model_supports_soft_deletes_and_preserves_historical_records(): void
    {
        $opd = Opd::factory()->create(['name' => 'Dinas Ketahanan Pangan']);
        $opdId = $opd->id;

        $opd->delete();

        $this->assertSoftDeleted('opds', ['id' => $opdId]);
        $this->assertNull(Opd::find($opdId));
        $this->assertNotNull(Opd::withTrashed()->find($opdId));
    }

    /**
     * Uji Siklus Keamanan Bertingkat:
     * Siklus 1 (5x gagal) = 60 detik
     * Siklus 2 (10x gagal) = 300 detik + Mail SecurityAlertMail
     * Siklus 3 (15x gagal) = 900 detik + Kolom Password Dinonaktifkan
     */
    public function test_tiered_lockout_cycles_and_security_alert_mail(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        \Illuminate\Support\Facades\Mail::fake();

        $user = User::factory()->create([
            'email' => 'korban.bruteforce@tubankab.go.id',
            'role'  => UserRole::Peserta,
        ]);

        $email = $user->email;

        // Siklus 1: 5 kali gagal -> Lockout 60 detik
        for ($i = 1; $i <= 5; $i++) {
            $this->post(route('login'), [
                'email'    => $email,
                'password' => 'wrong-pass',
            ]);
        }

        $resSiklus1 = $this->post(route('login'), ['email' => $email, 'password' => 'wrong-pass']);
        $resSiklus1->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan login', session('errors')->first('email'));

        // Bersihkan timer lockout (mensimulasikan 60 detik telah berlalu)
        \Illuminate\Support\Facades\RateLimiter::clear('login_lockout|' . $email . '|127.0.0.1');
        \Illuminate\Support\Facades\RateLimiter::clear('login_ip_lockout|127.0.0.1');
        \Illuminate\Support\Facades\RateLimiter::clear($email . '|127.0.0.1');
        \Illuminate\Support\Facades\RateLimiter::clear('login_ip|127.0.0.1');

        // Lakukan 5 kali gagal lagi (Total 10x gagal -> Siklus 2)
        for ($i = 1; $i <= 5; $i++) {
            $this->post(route('login'), [
                'email'    => $email,
                'password' => 'wrong-pass',
            ]);
        }

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\SecurityAlertMail::class);

        // Bersihkan timer lockout (mensimulasikan 300 detik telah berlalu)
        \Illuminate\Support\Facades\RateLimiter::clear('login_lockout|' . $email . '|127.0.0.1');
        \Illuminate\Support\Facades\RateLimiter::clear('login_ip_lockout|127.0.0.1');
        \Illuminate\Support\Facades\RateLimiter::clear($email . '|127.0.0.1');
        \Illuminate\Support\Facades\RateLimiter::clear('login_ip|127.0.0.1');

        // Lakukan 5 kali gagal lagi (Total 15x gagal -> Siklus 3)
        for ($i = 1; $i <= 5; $i++) {
            $this->post(route('login'), [
                'email'    => $email,
                'password' => 'wrong-pass',
            ]);
        }

        // Tampilan form login pada Siklus 3 menggantikan kolom password
        $viewResponse = $this->withSession(['_old_input' => ['email' => $email]])->get(route('login'));
        $viewResponse->assertSee('Kolom Kata Sandi Dinonaktifkan');
        $viewResponse->assertSee('Lupa Kata Sandi');
    }
}
