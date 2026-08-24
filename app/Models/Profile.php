<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Model Profile — Data profil lengkap peserta magang (1:1 dengan User)
 *
 * @property int                $id
 * @property int                $user_id            FK → users.id (UNIQUE)
 * @property ParticipantType    $participant_type   Enum: University / Student
 * @property string|null        $nik                16 digit KTP — UNIQUE
 * @property string|null        $nama_lengkap       Nama lengkap peserta — 150 chars
 * @property string             $nis_nim            NIS (SMA) / NIM (Kuliah) — UNIQUE
 * @property string|null        $nim                NIM Khusus Mahasiswa — 30 chars UNIQUE
 * @property string             $tempat_lahir
 * @property \Carbon\Carbon     $tanggal_lahir      Cast: date
 * @property JenisKelamin       $jenis_kelamin      Enum: Laki-laki / Perempuan
 * @property string             $alamat
 * @property string|null        $foto               Path relatif di storage/app/public/profiles/ — URL via foto_url
 * @property string             $no_telepon
 * @property string             $institusi          Sekolah / Universitas
 * @property string             $jurusan            Program Studi / Jurusan
 * @property string             $tahun_angkatan
 * @property int|null           $semester           1 s/d 14
 * @property \Carbon\Carbon     $created_at
 * @property \Carbon\Carbon     $updated_at
 *
 * ──── RELATIONSHIP ACCESSOR ───────────────────────────────────────────────────
 * @property-read User $user  Pemilik profile (belongsTo User)
 * ──── APPENDED ACCESSOR ─────────────────────────────────────────────────────
 * @property-read string|null $foto_url  URL publik foto profil
 */
#[Fillable([
    'user_id',
    'participant_type',
    'nik',
    'nama_lengkap',
    'nis_nim',
    'nim',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'alamat',
    'foto',
    'no_telepon',
    'institusi',
    'jurusan',
    'tahun_angkatan',
    'semester',
])]
class Profile extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal_lahir'    => 'date',
            'jenis_kelamin'    => JenisKelamin::class,
            'participant_type' => ParticipantType::class,
            'semester'         => 'integer',
        ];
    }

    protected function appends(): array
    {
        return [
            'foto_url',
        ];
    }

    public function isMahasiswa(): bool
    {
        $val = $this->participant_type?->value ?? $this->participant_type;
        return in_array($val, ['university', 'mahasiswa'], true);
    }

    public function isSiswa(): bool
    {
        $val = $this->participant_type?->value ?? $this->participant_type;
        return in_array($val, ['student', 'siswa'], true);
    }

    public function participantTypeLabel(): string
    {
        return $this->isSiswa() ? 'Siswa / SMK' : 'Mahasiswa';
    }

    public function institutionLabel(): string
    {
        return $this->isSiswa() ? 'Nama Sekolah' : 'Universitas / Perguruan Tinggi';
    }

    public function numberLabel(): string
    {
        return $this->isSiswa() ? 'NIS / NISN' : 'NIM';
    }

    public function numberValue(): string
    {
        $val = $this->isSiswa() ? ($this->nis_nim ?? '-') : ($this->nim ?? $this->nis_nim ?? '-');
        if (is_string($val) && str_starts_with(trim($val), '[object')) {
            return ($this->nis_nim && !str_starts_with(trim($this->nis_nim), '[object')) ? $this->nis_nim : '-';
        }
        return (string) $val;
    }

    public function majorLabel(): string
    {
        return $this->isSiswa() ? 'Jurusan' : 'Program Studi';
    }

    public function getInstansiAttribute(): string
    {
        return (string) ($this->attributes['institusi'] ?? '');
    }

    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto === null || trim($this->foto) === '') {
            return null;
        }

        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        return Storage::disk('public')->url($this->foto);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
