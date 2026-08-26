<?php

namespace Tests\Feature\Admin;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\InternDeactivatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DeactivationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_deactivating_intern_dispatches_queued_deactivation_notification(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $participant = User::factory()->create([
            'role'  => UserRole::Peserta,
            'email' => 'peserta_nonaktif@example.com',
        ]);

        $position = \App\Models\Position::create([
            'nama_posisi' => 'Frontend Developer',
            'slug'        => 'frontend-developer',
            'deskripsi'   => 'Deskripsi posisi frontend developer',
            'persyaratan' => 'Persyaratan posisi frontend developer',
            'kuota'       => 5,
            'status'      => \App\Enums\PositionStatus::Aktif,
        ]);

        $registration = Registration::create([
            'user_id'               => $participant->id,
            'position_id'           => $position->id,
            'nomor_pendaftaran'     => 'MAGANG-2026-TEST01',
            'periode_mulai'         => now()->addDay(),
            'periode_selesai'       => now()->addMonths(2),
            'cv_path'               => 'documents/cv.pdf',
            'surat_pengantar_path'  => 'documents/sp.pdf',
            'proposal_magang_path'  => 'documents/pm.pdf',
            'status'                => RegistrationStatus::Accepted,
            'is_terminated'         => false,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.active-interns.toggle-status', $registration->id), [
                'catatan_penonaktifan' => 'Pelanggaran tata tertib magang.',
            ]);

        $response->assertRedirect();
        $registration->refresh();
        $this->assertTrue($registration->is_terminated);

        Notification::assertSentTo(
            $participant,
            InternDeactivatedNotification::class,
            function (InternDeactivatedNotification $notification) {
                return $notification->catatanPenonaktifan === 'Pelanggaran tata tertib magang.';
            }
        );
    }
}
