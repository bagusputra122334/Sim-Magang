<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Registration $registration)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->registration->loadMissing(['position', 'user']);

        $namaPeserta = $notifiable->name ?? $this->registration->user?->name ?? 'Peserta';
        $kodePendaftaran = $this->registration->nomor_pendaftaran ?? 'MAGANG-2026';
        $namaPosisi = $this->registration->position?->nama_posisi ?? '-';
        $periodeLabel = $this->registration->periode_label ?? '-';
        $statusLabel = $this->registration->status?->label() ?? 'Submitted';

        return (new MailMessage)
            ->subject('[SIM-MAGANG] Konfirmasi Pendaftaran Magang #' . $kodePendaftaran)
            ->markdown('emails.applications.submitted', [
                'registration'    => $this->registration,
                'namaPeserta'     => $namaPeserta,
                'kodePendaftaran' => $kodePendaftaran,
                'namaPosisi'      => $namaPosisi,
                'periodeLabel'    => $periodeLabel,
                'statusLabel'     => $statusLabel,
            ]);
    }
}
