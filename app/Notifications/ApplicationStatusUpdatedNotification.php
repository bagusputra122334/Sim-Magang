<?php

namespace App\Notifications;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Registration $registration,
        public string $catatanAdmin = ''
    ) {
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
        $statusEnum = $this->registration->status;
        $statusLabel = $statusEnum?->label() ?? '-';

        $subject = match ($statusEnum) {
            RegistrationStatus::Accepted => '[SIM-MAGANG] Selamat! Pendaftaran Magang #' . $kodePendaftaran . ' DITERIMA',
            RegistrationStatus::Rejected => '[SIM-MAGANG] Informasi Hasil Seleksi Pendaftaran Magang #' . $kodePendaftaran,
            default                      => '[SIM-MAGANG] Perubahan Status Pendaftaran Magang #' . $kodePendaftaran,
        };

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.applications.status_updated', [
                'registration'    => $this->registration,
                'namaPeserta'     => $namaPeserta,
                'kodePendaftaran' => $kodePendaftaran,
                'namaPosisi'      => $namaPosisi,
                'periodeLabel'    => $periodeLabel,
                'statusLabel'     => $statusLabel,
                'statusEnum'      => $statusEnum,
                'catatanAdmin'    => $this->catatanAdmin,
            ]);
    }
}
