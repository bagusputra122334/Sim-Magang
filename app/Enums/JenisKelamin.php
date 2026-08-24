<?php

namespace App\Enums;

enum JenisKelamin: string
{
    case LakiLaki = 'Laki-laki';
    case Perempuan = 'Perempuan';

    public function label(): string
    {
        return $this->value;
    }
}
