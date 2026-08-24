<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $categoryLabel;

    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly string $email,
        public readonly string $category,
        public readonly string $messageContent,
        public readonly ?string $submittedAt = null,
    ) {
        $categoryMap = [
            'mahasiswa'  => 'Mahasiswa / Perguruan Tinggi',
            'siswa'      => 'Siswa / SMK / SMA',
            'dosen_guru' => 'Dosen / Guru Pembimbing',
            'lainnya'    => 'Lainnya / Umum',
        ];
        $this->categoryLabel = $categoryMap[$this->category] ?? ucfirst($this->category);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'simagang@diskominfo-tuban.go.id'),
                (string) config('mail.from.name', 'SIM-MAGANG Diskominfo Kabupaten Tuban')
            ),
            replyTo: [
                new Address($this->email, $this->name),
            ],
            subject: sprintf(
                '[SIM-MAGANG] Pesan Pertanyaan dari %s (%s)',
                $this->name,
                $this->categoryLabel
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-message',
            with: [
                'name'           => $this->name,
                'phone'          => $this->phone,
                'email'          => $this->email,
                'categoryLabel'  => $this->categoryLabel,
                'messageContent' => $this->messageContent,
                'submittedAt'    => $this->submittedAt ?? now()->translatedFormat('d F Y, H:i') . ' WIB',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
