<?php

namespace App\Enums;

enum SubmissionType: string
{
    case Pdf = 'pdf';
    case Doc = 'doc';
    case Zip = 'zip';
    case Link = 'link';

    public function label(): string
    {
        return match ($this) {
            self::Pdf => 'PDF (.pdf)',
            self::Doc => 'Document (.doc/.docx)',
            self::Zip => 'Archive (.zip/.rar)',
            self::Link => 'Link URL',
        };
    }

    public function acceptedMimes(): string
    {
        return match ($this) {
            self::Pdf => 'pdf',
            self::Doc => 'doc,docx',
            self::Zip => 'zip,rar',
            self::Link => '',
        };
    }

    public function acceptedExtensions(): string
    {
        return match ($this) {
            self::Pdf => '.pdf',
            self::Doc => '.doc,.docx',
            self::Zip => '.zip,.rar',
            self::Link => '',
        };
    }

    public function isFileUpload(): bool
    {
        return in_array($this, [self::Pdf, self::Doc, self::Zip], true);
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
