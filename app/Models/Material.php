<?php

namespace App\Models;

use App\Enums\SubmissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembimbing_id',
        'target_type',
        'title',
        'description',
        'file_materi',
        'files',
        'youtube_url',
        'is_task',
        'task_instruction',
        'deadline',
        'submission_type',
    ];

    protected $casts = [
        'files' => 'array',
        'is_task' => 'boolean',
        'deadline' => 'datetime',
        'submission_type' => SubmissionType::class,
    ];

    public function pembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'material_assignments', 'material_id', 'user_id')
            ->withTimestamps();
    }

    public function isTargetAll(): bool
    {
        return ($this->target_type ?? 'all') === 'all';
    }

    public function isTargetCustom(): bool
    {
        return ($this->target_type ?? 'all') === 'custom';
    }

    public function isAssignedTo($user): bool
    {
        if ($this->isTargetAll()) {
            return true;
        }

        $userId = $user instanceof User ? $user->id : (int) $user;
        if ($this->relationLoaded('assignedUsers')) {
            return $this->assignedUsers->contains('id', $userId);
        }

        return $this->assignedUsers()->where('users.id', $userId)->exists();
    }

    public function scopeVisibleToIntern($query, $user)
    {
        $userId = $user instanceof User ? $user->id : (int) $user;

        return $query->where(function ($q) use ($userId) {
            $q->where(function ($allQ) {
                $allQ->whereNull('target_type')
                     ->orWhere('target_type', 'all');
            })->orWhere(function ($sub) use ($userId) {
                $sub->where('target_type', 'custom')
                    ->whereHas('assignedUsers', function ($u) use ($userId) {
                        $u->where('users.id', $userId);
                    });
            });
        });
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(MaterialProgress::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ModuleSubmission::class);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if (is_numeric($value)) {
            return parent::resolveRouteBinding($value, $field);
        }

        if (is_string($value) && preg_match('/(\d+)$/', $value, $matches)) {
            return parent::resolveRouteBinding((int) $matches[1], $field);
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function hasSubmissionType(): bool
    {
        return $this->submission_type !== null;
    }

    public function isFileSubmission(): bool
    {
        if ($this->submission_type === null) {
            return true;
        }

        return $this->submission_type->isFileUpload();
    }

    public function isLinkSubmission(): bool
    {
        if ($this->submission_type === null) {
            return true;
        }

        return $this->submission_type->isLink();
    }

    public function getAcceptedMimesAttribute(): string
    {
        if ($this->submission_type === null) {
            return 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,txt,odt,zip,rar,7z,tar,gz,jpg,jpeg,png,webp,gif,svg,mp4,mkv,avi,mov,webm,mp3,wav';
        }

        return $this->submission_type->acceptedMimes();
    }

    public function getAcceptedExtensionsAttribute(): string
    {
        if ($this->submission_type === null) {
            return '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.txt,.odt,.zip,.rar,.7z,.tar,.gz,.jpg,.jpeg,.png,.webp,.gif,.svg,.mp4,.mkv,.avi,.mov,.webm,.mp3,.wav';
        }

        return $this->submission_type->acceptedExtensions();
    }

    public function getSubmissionTypeLabelAttribute(): string
    {
        if ($this->submission_type === null) {
            return 'Umum (File / Link Bebas)';
        }

        return $this->submission_type->label();
    }

    public function getAllFilesAttribute(): array
    {
        $list = [];

        if (is_array($this->files) && count($this->files) > 0) {
            foreach ($this->files as $f) {
                if (!empty($f['path'])) {
                    $list[] = [
                        'name' => $f['name'] ?? basename($f['path']),
                        'path' => $f['path'],
                        'size' => $f['size'] ?? null,
                        'mime' => $f['mime'] ?? null,
                    ];
                }
            }
        }

        if (empty($list) && !empty($this->file_materi)) {
            $list[] = [
                'name' => basename($this->file_materi),
                'path' => $this->file_materi,
                'size' => null,
                'mime' => null,
            ];
        }

        return $list;
    }
}
