<?php

namespace App\Models;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Models\Concerns\BelongsToOpd;
use Database\Factories\PositionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property int|null         $opd_id             FK → opds.id
 * @property \Carbon\Carbon   $tanggal_buka       Cast: date
 * @property \Carbon\Carbon   $tanggal_tutup      Cast: date
 * @property \Carbon\Carbon|null $deleted_at      Soft Deletes — arsip posisi lama (cast: datetime)
 * @property \Carbon\Carbon   $created_at
 * @property \Carbon\Carbon   $updated_at
 *
 * ──── RELATIONSHIP ACCESSOR ───────────────────────────────────────────────────
 * @property-read Opd|null $opd Data OPD posisi ini (belongsTo Opd)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Registration> $registrations  Pendaftar di posisi ini (1:N)
 */
#[Fillable([
    'nama_posisi',
    'slug',
    'deskripsi',
    'kualifikasi',
    'kualifikasi_siswa',
    'kualifikasi_mahasiswa',
    'tipe_pendaftar',
    'kuota',
    'mentor_name',
    'mentor_nip',
    'status',
    'opd_id',
])]
class Position extends Model
{
    /** @use HasFactory<PositionFactory> */
    use BelongsToOpd, HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (self $position): void {
            if (empty($position->slug) && !empty($position->nama_posisi)) {
                $baseSlug = \Illuminate\Support\Str::slug($position->nama_posisi);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . (++$counter);
                }
                $position->slug = $slug;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'kuota'      => 'integer',
            'status'     => PositionStatus::class,
            'deleted_at' => 'datetime',
        ];
    }

    public function isOpenForSiswa(): bool
    {
        return in_array($this->tipe_pendaftar ?? 'all', ['all', 'siswa'], true);
    }

    public function isOpenForMahasiswa(): bool
    {
        return in_array($this->tipe_pendaftar ?? 'all', ['all', 'mahasiswa'], true);
    }

    public function getTargetJenjangLabelAttribute(): string
    {
        return match($this->tipe_pendaftar ?? 'all') {
            'siswa'     => 'Khusus Siswa (SMK)',
            'mahasiswa' => 'Khusus Mahasiswa',
            default     => 'Mahasiswa & SMK',
        };
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function getNameAttribute(): string
    {
        return $this->nama_posisi;
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
