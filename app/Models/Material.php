<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['pembimbing_id', 'title', 'description', 'youtube_url', 'is_task', 'task_instruction', 'deadline'];

    protected $casts = [
        'is_task' => 'boolean',
        'deadline' => 'datetime',
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
}
