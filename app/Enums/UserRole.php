<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Peserta = 'peserta';
    case Pembimbing = 'pembimbing';
    case Intern = 'intern';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Peserta => 'Peserta',
            self::Pembimbing => 'Pembimbing',
            self::Intern => 'Intern',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin || $this === self::SuperAdmin;
    }

    public function isSuperAdmin(): bool
    {
        return $this === self::SuperAdmin;
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
