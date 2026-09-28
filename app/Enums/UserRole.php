<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Peserta = 'peserta';
    case Pembimbing = 'pembimbing';
    case Intern = 'intern';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Peserta => 'Peserta',
            self::Pembimbing => 'Pembimbing',
            self::Intern => 'Intern',
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
        return $this === self::Peserta || $this === self::Intern;
    }

    public function isPembimbing(): bool
    {
        return $this === self::Pembimbing;
    }
}
