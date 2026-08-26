<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InternDeactivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Registration $registration,
        public string $catatanPenonaktifan = ''
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

        return (new MailMessage)
            ->subject('[SIM-MAGANG] Pemberitahuan Penonaktifan Status Magang #' . $kodePendaftaran)
            ->greeting('Halo, ' . $namaPeserta . '!')
            ->line('Kami menginformasikan bahwa status kepesertaan magang Anda untuk posisi ' . $namaPosisi . ' (' . $kodePendaftaran . ') telah DINONAKTIFKAN oleh Administrator.')
            ->line('Catatan Penonaktifan: ' . ($this->catatanPenonaktifan ?: 'Penonaktifan oleh Administrator SIM-MAGANG.'))
            ->line('Jika Anda memiliki pertanyaan mengenai keputusan penonaktifan ini, silakan hubungi pihak Diskominfo SP Kabupaten Tuban.');
    }
}
