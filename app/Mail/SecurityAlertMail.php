<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SecurityAlertMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $ipAddress,
        public readonly string $attemptTime,
        public readonly ?string $userAgent = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'simagang@diskominfo-tuban.go.id'),
                (string) config('mail.from.name', 'SIMAGANG Diskominfo Kabupaten Tuban')
            ),
            subject: '[SIMAGANG] ⚠️ Peringatan Keamanan: Terdeteksi 10x Percobaan Masuk Akun Gagal',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.security-alert',
            with: [
                'user'        => $this->user,
                'ipAddress'   => $this->ipAddress,
                'attemptTime' => $this->attemptTime,
                'userAgent'   => $this->userAgent ?? 'Browser / Web Client',
                'resetUrl'    => route('password.request'),
            ],
        );
    }
}

