<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Registration — Data transaksi pendaftaran magang
 *
 * @property int                 $id
 * @property string              $nomor_pendaftaran      UNIQUE — Contoh: MAGANG-2026-0001
 * @property int                 $user_id                FK → users.id
 * @property int                 $position_id            FK → positions.id
 * @property string              $cv_path                Path file CV di storage (bukan BLOB)
 * @property string              $surat_pengantar_path   Path file Surat Pengantar
 * @property string|null         $proposal_magang_path   Path file Proposal Magang
 * @property RegistrationStatus  $status                 Enum: Submitted / UnderReview / Accepted / Rejected
 * @property string|null         $catatan_admin          Catatan reviewer admin (opsional)
 * @property string|null         $surat_balasan_path     Path file Surat Balasan PDF (null hingga accepted & diupload)
 * @property \Carbon\Carbon      $tanggal_submit         Cast: datetime, default CURRENT_TIMESTAMP
 * @property \Carbon\Carbon|null $periode_mulai          Cast: date — Tanggal mulai magang
 * @property \Carbon\Carbon|null $periode_selesai        Cast: date — Tanggal selesai magang
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 *
 * ──── RELATIONSHIP ACCESSOR ───────────────────────────────────────────────────
 * @property-read User     $user       Pemilik pendaftaran (belongsTo User)
 * @property-read Position $position   Posisi yang didaftar (belongsTo Position)
 * ──── APPENDED ACCESSOR ─────────────────────────────────────────────────────
 * @property-read string|null $periode_label
 */
#[Fillable([
    'nomor_pendaftaran',
    'user_id',
    'position_id',
    'cv_path',
    'surat_pengantar_path',
    'proposal_magang_path',
    'status',
    'catatan_admin',
    'surat_balasan_path',
    'tanggal_submit',
    'periode_mulai',
    'periode_selesai',
    'is_terminated',
    'catatan_penonaktifan',
    'terminated_at',
])]
class Registration extends Model
{
    protected function casts(): array
    {
        return [
            'status'          => RegistrationStatus::class,
            'tanggal_submit'  => 'datetime',
            'periode_mulai'   => 'date',
            'periode_selesai' => 'date',
            'is_terminated'   => 'boolean',
            'terminated_at'   => 'datetime',
        ];
    }

    protected function appends(): array
    {
        return [
            'periode_label',
            'operational_status',
            'operational_status_label',
            'operational_status_badge_class',
        ];
    }

    public function getOperationalStatusAttribute(): string
    {
        if ($this->is_terminated) {
            return 'terminated';
        }

        if ($this->periode_selesai && now()->isAfter($this->periode_selesai->copy()->endOfDay())) {
            return 'completed';
        }

        if ($this->periode_mulai && now()->isBefore($this->periode_mulai->copy()->startOfDay())) {
            return 'upcoming';
        }

        return 'active';
    }

    public function getOperationalStatusLabelAttribute(): string
    {
        return match ($this->operational_status) {
            'terminated' => 'Dinonaktifkan / Berhenti',
            'completed'  => 'Selesai Magang',
            'upcoming'   => 'Belum Mulai',
            default      => 'Aktif Magang',
        };
    }

    public function getOperationalStatusBadgeClassAttribute(): string
    {
        return match ($this->operational_status) {
            'terminated' => 'bg-danger-subtle text-danger border border-danger-subtle',
            'completed'  => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
            'upcoming'   => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            default      => 'bg-success-subtle text-success border border-success-subtle',
        };
    }

    public function getPeriodeLabelAttribute(): ?string
    {
        if ($this->periode_mulai === null && $this->periode_selesai === null) {
            return null;
        }

        $mulai = $this->periode_mulai?->translatedFormat('d M Y') ?? '-';
        $selesai = $this->periode_selesai?->translatedFormat('d M Y') ?? '-';

        if ($mulai === $selesai) {
            return $mulai;
        }

        return sprintf('%s s/d %s', $mulai, $selesai);
    }

    public function getRegistrationNumberAttribute(): ?string
    {
        return $this->nomor_pendaftaran;
    }

    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    public function getInstitutionAttribute(): ?string
    {
        return $this->user?->profile?->institusi;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function isSubmitted(): bool
    {
        return $this->status->isSubmitted();
    }

    public function isUnderReview(): bool
    {
        return $this->status->isUnderReview();
    }

    public function isAccepted(): bool
    {
        return $this->status->isAccepted();
    }

    public function isRejected(): bool
    {
        return $this->status->isRejected();
    }

    public function dapatDiubah(): bool
    {
        return $this->status->canEdit();
    }

    public function dapatDihapus(): bool
    {
        return $this->status->isSubmitted() || $this->status->isRejected();
    }

    public function adaSuratBalasan(): bool
    {
        return $this->isAccepted() && ! empty($this->surat_balasan_path);
    }

    public static function generateNomorPendaftaran(): string
    {
        $tahun  = now()->format('Y');
        $prefix = "MAGANG-{$tahun}-";
        $latest = self::where('nomor_pendaftaran', 'like', "{$prefix}%")->latest('id')->first();
        $nomorUrut = $latest ? (int) substr($latest->nomor_pendaftaran, -4) + 1 : 1;

        return $prefix.str_pad((string) $nomorUrut, 4, '0', STR_PAD_LEFT);
    }
}
