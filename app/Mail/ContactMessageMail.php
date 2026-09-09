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

    public string $name;
    public string $phone;
    public string $email;
    public string $category;
    public string $messageContent;
    public ?string $submittedAt;
    public string $categoryLabel;

    public function __construct(
        string|array $name,
        ?string $phone = null,
        ?string $email = null,
        ?string $category = null,
        ?string $messageContent = null,
        ?string $submittedAt = null
    ) {
        if (is_array($name)) {
            $data = $name;
            $this->name = (string) ($data['name'] ?? $data['nama'] ?? 'Pengunjung');
            $this->phone = (string) ($data['phone'] ?? $data['wa_number'] ?? $data['whatsapp'] ?? $data['telepon'] ?? '-');
            $this->email = (string) ($data['email'] ?? '');
            $this->category = (string) ($data['category'] ?? $data['kategori'] ?? 'lainnya');
            $this->messageContent = (string) ($data['message'] ?? $data['pesan'] ?? $data['messageContent'] ?? '');
            $this->submittedAt = isset($data['submittedAt']) ? (string) $data['submittedAt'] : (now()->translatedFormat('d F Y, H:i') . ' WIB');
        } else {
            $this->name = $name;
            $this->phone = $phone ?? '-';
            $this->email = $email ?? '';
            $this->category = $category ?? 'lainnya';
            $this->messageContent = $messageContent ?? '';
            $this->submittedAt = $submittedAt ?? (now()->translatedFormat('d F Y, H:i') . ' WIB');
        }

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
                'submittedAt'    => $this->submittedAt,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

