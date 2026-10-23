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
    'certificate_path',
    'certificate_issued_at',
])]
class Registration extends Model
{
    protected $fillable = [
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
        'certificate_path',
        'certificate_issued_at',
    ];

    protected function casts(): array
    {
        return [
            'status'                => RegistrationStatus::class,
            'tanggal_submit'        => 'datetime',
            'periode_mulai'         => 'date',
            'periode_selesai'       => 'date',
            'is_terminated'         => 'boolean',
            'terminated_at'         => 'datetime',
            'certificate_issued_at' => 'datetime',
        ];
    }

    protected function appends(): array
    {
        return [
            'periode_label',
            'operational_status',
            'operational_status_label',
            'operational_status_badge_class',
            'is_graduation_eligible',
        ];
    }

    /**
     * Mengecek apakah peserta magang sudah mencapai hari terakhir magang atau setelahnya (now >= end_date).
     */
    public function isGraduationEligible(): bool
    {
        if ($this->status !== RegistrationStatus::Accepted || $this->is_terminated) {
            return false;
        }

        if (! $this->periode_selesai) {
            return true;
        }

        return now()->startOfDay()->gte($this->periode_selesai->copy()->startOfDay());
    }

    public function getIsGraduationEligibleAttribute(): bool
    {
        return $this->isGraduationEligible();
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
            'completed'  => ! empty($this->certificate_path) ? 'Selesai & Sertifikat Terbit' : 'Selesai (Menunggu Sertifikat)',
            'upcoming'   => 'Belum Mulai',
            default      => 'Aktif Magang',
        };
    }

    public function getOperationalStatusBadgeClassAttribute(): string
    {
        return match ($this->operational_status) {
            'terminated' => 'bg-rose-100 text-rose-800 border border-rose-200',
            'completed'  => ! empty($this->certificate_path)
                ? 'bg-emerald-100 text-emerald-800 border border-emerald-200'
                : 'bg-slate-100 text-slate-800 border border-slate-200',
            'upcoming'   => 'bg-amber-100 text-amber-800 border border-amber-200',
            default      => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
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

    public function getStartDateAttribute(): ?\Carbon\Carbon
    {
        return $this->periode_mulai;
    }

    public function getEndDateAttribute(): ?\Carbon\Carbon
    {
        return $this->periode_selesai;
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
