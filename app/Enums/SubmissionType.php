<?php

namespace App\Enums;

enum SubmissionType: string
{
    case Pdf = 'pdf';
    case Doc = 'doc';
    case Zip = 'zip';
    case Image = 'image';
    case Media = 'media';
    case All = 'all';
    case Link = 'link';

    public function label(): string
    {
        return match ($this) {
            self::Pdf => 'PDF (.pdf)',
            self::Doc => 'Dokumen (.doc/.docx/.xls/.xlsx/.ppt/.pptx)',
            self::Zip => 'Arsip (.zip/.rar/.7z)',
            self::Image => 'Gambar / Desain (.jpg/.png/.webp/.svg)',
            self::Media => 'Video / Audio (.mp4/.mkv/.mov/.avi/.mp3)',
            self::All => 'Semua Format File (Bebas / Fleksibel)',
            self::Link => 'Link URL (Google Drive / GitHub / dll)',
        };
    }

    public function acceptedMimes(): string
    {
        return match ($this) {
            self::Pdf => 'pdf',
            self::Doc => 'doc,docx,xls,xlsx,ppt,pptx,csv,txt,odt',
            self::Zip => 'zip,rar,7z,tar,gz',
            self::Image => 'jpg,jpeg,png,webp,gif,svg',
            self::Media => 'mp4,mkv,avi,mov,webm,mp3,wav',
            self::All => 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,txt,odt,zip,rar,7z,tar,gz,jpg,jpeg,png,webp,gif,svg,mp4,mkv,avi,mov,webm,mp3,wav',
            self::Link => '',
        };
    }

    public function acceptedExtensions(): string
    {
        return match ($this) {
            self::Pdf => '.pdf',
            self::Doc => '.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.txt,.odt',
            self::Zip => '.zip,.rar,.7z,.tar,.gz',
            self::Image => '.jpg,.jpeg,.png,.webp,.gif,.svg',
            self::Media => '.mp4,.mkv,.avi,.mov,.webm,.mp3,.wav',
            self::All => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.txt,.odt,.zip,.rar,.7z,.tar,.gz,.jpg,.jpeg,.png,.webp,.gif,.svg,.mp4,.mkv,.avi,.mov,.webm,.mp3,.wav',
            self::Link => '',
        };
    }

    public function isFileUpload(): bool
    {
        return $this !== self::Link;
    }

    public function isLink(): bool
    {
        return $this === self::Link;
    }

    public static function tryFromWithDefault(mixed $value): ?self
    {
        if ($value === null || $value === '') {
            return null;
        }

        return self::tryFrom((string) $value);
    }
}
