<?php

namespace App\Models;

use App\Enums\SubmissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembimbing_id',
        'title',
        'description',
        'file_materi',
        'youtube_url',
        'is_task',
        'task_instruction',
        'deadline',
        'submission_type',
    ];

    protected $casts = [
        'is_task' => 'boolean',
        'deadline' => 'datetime',
        'submission_type' => SubmissionType::class,
    ];

    public function pembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
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
            return 'pdf,doc,docx,zip,rar,jpg,jpeg,png';
        }

        return $this->submission_type->acceptedMimes();
    }

    public function getAcceptedExtensionsAttribute(): string
    {
        if ($this->submission_type === null) {
            return '.pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png';
        }

        return $this->submission_type->acceptedExtensions();
    }

    public function getSubmissionTypeLabelAttribute(): string
    {
        if ($this->submission_type === null) {
            return 'Umum (File / Link)';
        }

        return $this->submission_type->label();
    }
}
