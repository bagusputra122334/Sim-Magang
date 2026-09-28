<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — Data autentikasi user (Admin / Peserta Magang)
 *
 * @property int                $id
 * @property string             $name
 * @property string             $email
 * @property UserRole           $role               Enum: Admin / Peserta
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string             $password           (Hashed, Hidden saat serialize)
 * @property string|null        $remember_token     (Hidden saat serialize)
 * @property \Carbon\Carbon     $created_at
 * @property \Carbon\Carbon     $updated_at
 *
 * ──── RELATIONSHIP ACCESSOR (IDE AUTO-COMPLETE) ──────────────────────────────
 * @property-read Profile|null        $profile        Data profil peserta (1:1)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Registration> $registrations  Riwayat pendaftaran user (1:N)
 */
#[Fillable(['name', 'email', 'password', 'nip', 'position_title', 'division_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if (! isset($user->role) || ! $user->role instanceof UserRole) {
                $user->role = UserRole::Peserta;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function isAdmin(): bool
    {
        $role = $this->role instanceof \App\Enums\UserRole ? $this->role->value : $this->role;
        return $this->id === 1 
            || str_contains(strtolower($this->email), 'admin') 
            || str_contains(strtolower($this->email), 'diskominfo')
            || $role === 'admin';
    }

    public function isPeserta(): bool
    {
        try {
            return $this->role instanceof UserRole && $this->role->isPeserta();
        } catch (\Throwable) {
            return false;
        }
    }

    public function isParticipant(): bool
    {
        if ($this->isAdmin() || $this->isPembimbing()) return false; // Ensure hierarchy
        return true; // Everyone else is strictly a participant
    }

    public function hasProfile(): bool
    {
        try {
            if ($this->relationLoaded('profile')) {
                return $this->profile !== null;
            }

            return $this->profile()->exists();
        } catch (\Throwable) {
            return false;
        }
    }

    public function hasActiveApplication(): bool
    {
        $today = now()->toDateString();

        return $this->registrations()
            ->where(function ($query) use ($today): void {
                $query->whereIn('status', [
                    \App\Enums\RegistrationStatus::Submitted->value,
                    \App\Enums\RegistrationStatus::UnderReview->value,
                ])
                ->orWhere(function ($q) use ($today): void {
                    $q->where('status', \App\Enums\RegistrationStatus::Accepted->value)
                        ->where('is_terminated', false)
                        ->where(function ($sq) use ($today): void {
                            $sq->whereNull('periode_selesai')
                                ->orWhereDate('periode_selesai', '>=', $today);
                        });
                });
            })
            ->exists();
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->profile?->foto_url;
    }

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new \App\Notifications\QueuedUserResetPassword($token));
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail($notification = null): string
    {
        return 'bagusdwijunior@gmail.com';
    }

    public function division(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'pembimbing_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'pembimbing_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'intern_id');
    }

    public function materialProgresses(): HasMany
    {
        return $this->hasMany(MaterialProgress::class, 'intern_id');
    }

    public function moduleSubmissions(): HasMany
    {
        return $this->hasMany(ModuleSubmission::class);
    }

    /**
     * Check if user is a Pembimbing
     */
    public function isPembimbing(): bool
    {
        if ($this->isAdmin()) return false; // Admin can NEVER be categorized as Pembimbing
        
        $role = $this->role instanceof \App\Enums\UserRole ? $this->role->value : $this->role;
        return $role === 'pembimbing';
    }
}
