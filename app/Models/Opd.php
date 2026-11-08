<?php

namespace App\Models;

use Database\Factories\OpdFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Model Opd — Master data Organisasi Perangkat Daerah (OPD) Kabupaten Tuban
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $slug
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $head_of_office_name
 * @property string|null $head_of_office_nip
 * @property string|null $logo_path
 * @property bool $is_active
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * ──── RELATIONSHIPS ───────────────────────────────────────────────────────────
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Division> $divisions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Position> $positions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Registration> $registrations
 */
class Opd extends Model
{
    /** @use HasFactory<OpdFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'opds';

    protected $fillable = [
        'name',
        'code',
        'slug',
        'address',
        'phone',
        'email',
        'head_of_office_name',
        'head_of_office_nip',
        'logo_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $opd): void {
            if (empty($opd->slug) && !empty($opd->code)) {
                $opd->slug = Str::slug($opd->code);
            } elseif (empty($opd->slug) && !empty($opd->name)) {
                $opd->slug = Str::slug($opd->name);
            }

            if (empty($opd->code) && !empty($opd->slug)) {
                $opd->code = $opd->slug;
            } elseif (empty($opd->code) && !empty($opd->name)) {
                $opd->code = Str::slug($opd->name);
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper to get default OPD (Diskominfo SP Tuban or first active)
     */
    public static function default(): ?self
    {
        return static::where('code', 'diskominfo-sp')->first() 
            ?? static::where('is_active', true)->first() 
            ?? static::first();
    }
}
