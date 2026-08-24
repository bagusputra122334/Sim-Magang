<?php

namespace App\Enums;

enum ParticipantType: string
{
    case University = 'university';
    case Student = 'student';

    // Backwards compatibility cases if old data exists
    case Mahasiswa = 'mahasiswa';
    case Siswa = 'siswa';

    public function label(): string
    {
        return match ($this) {
            self::University, self::Mahasiswa => 'Mahasiswa',
            self::Student, self::Siswa       => 'Siswa / SMK',
        };
    }

    public function isUniversity(): bool
    {
        return $this === self::University || $this === self::Mahasiswa;
    }

    public function isStudent(): bool
    {
        return $this === self::Student || $this === self::Siswa;
    }

    public function isMahasiswa(): bool
    {
        return $this->isUniversity();
    }

    public function isSiswa(): bool
    {
        return $this->isStudent();
    }
}
