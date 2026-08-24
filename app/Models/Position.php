<?php

namespace App\Models;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Position — Master data posisi / lowongan magang
 *
 * @property int              $id
 * @property string           $nama_posisi        UNIQUE (misal: "Web Developer Backend")
 * @property string           $slug               UNIQUE URL-friendly (misal: "web-developer-backend")
 * @property string           $deskripsi          Tugas & tanggung jawab
 * @property string|null      $kualifikasi        Syarat / kualifikasi (opsional)
 * @property int              $kuota              Kuota jumlah diterima (unsigned int, default 1)
 * @property PositionStatus   $status             Enum: Aktif / TidakAktif
 * @property \Carbon\Carbon   $tanggal_buka       Cast: date
 * @property \Carbon\Carbon   $tanggal_tutup      Cast: date
 * @property \Carbon\Carbon|null $deleted_at      Soft Deletes — arsip posisi lama (cast: datetime)
 * @property \Carbon\Carbon   $created_at
 * @property \Carbon\Carbon   $updated_at
 *
 * ──── RELATIONSHIP ACCESSOR ───────────────────────────────────────────────────
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Registration> $registrations  Pendaftar di posisi ini (1:N)
 */
#[Fillable([
    'nama_posisi',
    'slug',
    'deskripsi',
    'kualifikasi',
    'kuota',
    'mentor_name',
    'mentor_nip',
    'status',
    'tanggal_buka',
    'tanggal_tutup',
])]
class Position extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'tanggal_buka' => 'date',
            'tanggal_tutup' => 'date',
            'kuota'        => 'integer',
            'status'       => PositionStatus::class,
            'deleted_at'   => 'datetime',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function isAktif(): bool
    {
        return $this->status->isAktif();
    }

    public function sedangDibuka(): bool
    {
        return $this->isAktif();
    }

    public function sisaKuota(): int
    {
        return 999999;
    }

    public function kuotaPenuh(): bool
    {
        return false;
    }
}
