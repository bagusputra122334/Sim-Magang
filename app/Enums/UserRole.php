<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Peserta = 'peserta';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Peserta => 'Peserta',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function isPeserta(): bool
    {
        return $this === self::Peserta;
    }

    public function isParticipant(): bool
    {
        return $this === self::Peserta;
    }
}
