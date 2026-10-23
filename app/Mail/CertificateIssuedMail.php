<?php

namespace App\Mail;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CertificateIssuedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param Registration $application
     * @param bool $isReplacing
     */
    public function __construct(
        public readonly Registration $application,
        public readonly bool $isReplacing = false
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isReplacing
            ? sprintf('[SIMAGANG] 🎓 Pembaruan Sertifikat Kelulusan Magang Digital — %s', $this->application->nomor_pendaftaran)
            : sprintf('[SIMAGANG] 🎓 Selamat! Sertifikat Kelulusan Magang Digital Telah Terbit — %s', $this->application->nomor_pendaftaran);

        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'simagang@diskominfo-tuban.go.id'),
                (string) config('mail.from.name', 'SIMAGANG Diskominfo Kabupaten Tuban')
            ),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $fileSize = '';
        if (! empty($this->application->certificate_path) && Storage::disk('public')->exists($this->application->certificate_path)) {
            $bytes = Storage::disk('public')->size($this->application->certificate_path);
            $fileSize = $this->formatFileSize($bytes);
        }

        $issuedAt = $this->application->certificate_issued_at
            ? Carbon::parse($this->application->certificate_issued_at)->translatedFormat('l, d F Y H:i') . ' WIB'
            : now()->translatedFormat('l, d F Y H:i') . ' WIB';

        $periodeLabel = $this->application->periode_label;
        if (empty($periodeLabel) && ! empty($this->application->tanggal_mulai) && ! empty($this->application->tanggal_selesai)) {
            $periodeLabel = Carbon::parse($this->application->tanggal_mulai)->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($this->application->tanggal_selesai)->translatedFormat('d M Y');
        } elseif (empty($periodeLabel)) {
            $periodeLabel = '-';
        }

        return new Content(
            markdown: 'emails.certificate-issued',
            with: [
                'application'       => $this->application,
                'isReplacing'       => $this->isReplacing,
                'namaPeserta'       => $this->application->user?->name ?? 'Peserta Magang',
                'kodePendaftaran'   => $this->application->nomor_pendaftaran,
                'namaPosisi'        => $this->application->position?->nama_posisi ?? '-',
                'institusi'         => $this->application->user?->profile?->institusi ?? '-',
                'periodeLabel'      => $periodeLabel,
                'downloadUrl'       => route('participant.applications.certificate.download', $this->application->id),
                'dashboardUrl'      => route('participant.dashboard'),
                'fileSize'          => $fileSize,
                'issuedAt'          => $issuedAt,
            ],
        );
    }

    /**
     * Format bytes into human readable format.
     */
    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
