<?php

namespace Tests\Feature;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Position;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\ApplicationStatusUpdatedNotification;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AsyncNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $peserta;
    private Position $position;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::factory()->create([
            'name' => 'Admin Diskominfo',
            'email' => 'admin@diskominfo-tuban.go.id',
            'role' => UserRole::Admin,
        ]);

        $this->peserta = User::factory()->create([
            'name' => 'Bagus Dwi Junior',
            'email' => 'bagusdwijunior@gmail.com',
            'role' => UserRole::Peserta,
        ]);

        Profile::create([
            'user_id'          => $this->peserta->id,
            'nama_lengkap'     => 'Bagus Dwi Junior',
            'participant_type' => 'mahasiswa',
            'nis_nim'          => '24050974001',
            'nim'              => '24050974001',
            'nik'              => '3523010101010001',
            'tempat_lahir'     => 'Tuban',
            'tanggal_lahir'    => '2001-05-15',
            'jenis_kelamin'    => 'Laki-laki',
            'institusi'        => 'Universitas Negeri Surabaya',
            'jurusan'          => 'Teknik Informatika',
            'tahun_angkatan'   => '2022',
            'semester'         => 6,
            'alamat'           => 'Jl. Veteran Tuban',
            'no_telepon'       => '081234567890',
        ]);

        $this->position = Position::create([
            'nama_posisi'   => 'Web Developer SPBE',
            'slug'          => 'web-developer-spbe',
            'deskripsi'     => 'Pengembangan aplikasi SPBE',
            'kuota'         => 5,
            'status'        => PositionStatus::Aktif,
        ]);
    }

    /**
     * Test notification classes implement ShouldQueue interface.
     */
    public function test_notification_classes_implement_should_queue(): void
    {
        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0001',
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
        ]);

        $submittedNotif = new ApplicationSubmittedNotification($reg);
        $statusNotif = new ApplicationStatusUpdatedNotification($reg, 'Catatan test');

        $this->assertInstanceOf(ShouldQueue::class, $submittedNotif);
        $this->assertInstanceOf(ShouldQueue::class, $statusNotif);
    }

    /**
     * Test ApplicationSubmittedNotification is dispatched on registration store.
     */
    public function test_application_submitted_notification_dispatched_asynchronously(): void
    {
        Notification::fake();

        $response = $this->actingAs($this->peserta)
            ->post(route('participant.registrations.store'), [
                'position_id'     => $this->position->id,
                'periode_mulai'   => now()->addDays(10)->format('Y-m-d'),
                'periode_selesai' => now()->addDays(40)->format('Y-m-d'),
                'cv'              => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
                'surat_pengantar' => UploadedFile::fake()->create('surat.pdf', 200, 'application/pdf'),
                'proposal_magang' => UploadedFile::fake()->create('proposal.pdf', 200, 'application/pdf'),
            ]);

        $response->assertRedirect();

        Notification::assertSentTo(
            $this->peserta,
            ApplicationSubmittedNotification::class,
            function (ApplicationSubmittedNotification $notification) {
                return $notification->registration->position_id === $this->position->id;
            }
        );
    }

    /**
     * Test ApplicationStatusUpdatedNotification is dispatched on admin review update (Accepted).
     */
    public function test_application_status_updated_notification_dispatched_on_accepted(): void
    {
        Notification::fake();

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0002',
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(5),
            'periode_selesai'      => now()->addDays(35),
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.applications.update-review', $reg->id), [
                'status'        => 'accepted',
                'catatan_admin' => 'Selamat, anda diterima!',
            ]);

        $response->assertRedirect();

        Notification::assertSentTo(
            $this->peserta,
            ApplicationStatusUpdatedNotification::class,
            function (ApplicationStatusUpdatedNotification $notification) use ($reg) {
                return $notification->registration->id === $reg->id
                    && $notification->catatanAdmin === 'Selamat, anda diterima!';
            }
        );
    }

    /**
     * Test ApplicationStatusUpdatedNotification is dispatched on admin review update (Rejected).
     */
    public function test_application_status_updated_notification_dispatched_on_rejected(): void
    {
        Notification::fake();

        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0003',
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Submitted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(5),
            'periode_selesai'      => now()->addDays(35),
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.applications.update-review', $reg->id), [
                'status'        => 'rejected',
                'catatan_admin' => 'Mohon maaf, kuota sudah penuh.',
            ]);

        $response->assertRedirect();

        Notification::assertSentTo(
            $this->peserta,
            ApplicationStatusUpdatedNotification::class,
            function (ApplicationStatusUpdatedNotification $notification) use ($reg) {
                return $notification->registration->id === $reg->id
                    && $notification->catatanAdmin === 'Mohon maaf, kuota sudah penuh.';
            }
        );
    }

    /**
     * Test mail message rendered by notifications contain expected attributes.
     */
    public function test_notification_mail_messages_content(): void
    {
        $reg = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-0004',
            'user_id'              => $this->peserta->id,
            'position_id'          => $this->position->id,
            'cv_path'              => 'dokumen/cv.pdf',
            'surat_pengantar_path' => 'dokumen/surat.pdf',
            'status'               => RegistrationStatus::Accepted,
            'tanggal_submit'       => now(),
            'periode_mulai'        => now()->addDays(5),
            'periode_selesai'      => now()->addDays(35),
        ]);

        $submittedNotif = new ApplicationSubmittedNotification($reg);
        $submittedMail = $submittedNotif->toMail($this->peserta);
        $this->assertStringContainsString('MAGANG-2026-0004', $submittedMail->subject);

        $statusNotif = new ApplicationStatusUpdatedNotification($reg, 'Berkas lengkap');
        $statusMail = $statusNotif->toMail($this->peserta);
        $this->assertStringContainsString('DITERIMA', $statusMail->subject);
    }
}
