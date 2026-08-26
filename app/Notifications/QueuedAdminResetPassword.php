<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QueuedAdminResetPassword extends Notification implements ShouldQueue
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
        $url = route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('[SIM-MAGANG Admin] Permintaan Atur Ulang Kata Sandi Administrator')
            ->greeting('Halo Administrator, ' . ($notifiable->name ?? 'Admin') . '!')
            ->line('Anda menerima email ini karena kami menerima permintaan atur ulang kata sandi (reset password) untuk akun Administrator SIM-MAGANG Diskominfo SP Kabupaten Tuban.')
            ->action('Atur Ulang Kata Sandi Admin', $url)
            ->line('Tautan atur ulang kata sandi admin ini akan kedaluwarsa dalam waktu 60 menit.')
            ->line('Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.');
    }
}
