<?php

namespace App\Mail;

use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyLetterAvailableMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Registration $registration
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'simagang@diskominfo-tuban.go.id'),
                (string) config('mail.from.name', 'SIM-MAGANG Diskominfo Kabupaten Tuban')
            ),
            subject: sprintf(
                '[SIM-MAGANG] 📄 Surat Balasan Tersedia — %s',
                $this->registration->nomor_pendaftaran
            ),
        );
    }

    public function content(): Content
    {
        $fileInfo = app(RegistrationService::class)->getSuratBalasanFileInfo($this->registration);
        $fileBasename = $fileInfo['basename'] ?? 'Surat-Balasan.pdf';
        $fileSize = $fileInfo['human_size'] ?? '';

        return new Content(
            markdown: 'emails.applications.reply-letter-available',
            with: [
                'registration'      => $this->registration,
                'namaPeserta'       => $this->registration->user?->name ?? 'Peserta',
                'kodePendaftaran'   => $this->registration->nomor_pendaftaran,
                'namaPosisi'        => $this->registration->position?->nama_posisi ?? '-',
                'statusLabel'       => $this->registration->status->label(),
                'periodeLabel'      => $this->registration->periode_label ?? '-',
                'catatanAdmin'      => null,
                'suratBalasanInfo'  => [
                    'available'       => true,
                    'filename'        => $fileBasename,
                    'file_size'       => $fileSize,
                    'downloadRoute'   => route('participant.applications.reply-letter.download', $this->registration->id),
                ],
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
