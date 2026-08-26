<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QueuedUserResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        #[\SensitiveParameter]
        public string $token
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('[SIM-MAGANG] Permintaan Atur Ulang Kata Sandi')
            ->greeting('Halo, ' . ($notifiable->name ?? 'Peserta Magang') . '!')
            ->line('Anda menerima email ini karena kami menerima permintaan atur ulang kata sandi (reset password) untuk akun peserta SIM-MAGANG Anda.')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line('Tautan atur ulang kata sandi ini akan kedaluwarsa dalam waktu 60 menit.')
            ->line('Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini dan kata sandi Anda akan tetap aman.');
    }
}
