<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleSubmission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'files' => 'array',
        'is_video_watched' => 'boolean',
        'video_watched_at' => 'datetime',
        'grade' => 'integer',
    ];

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

        if (empty($list) && !empty($this->file_path)) {
            $list[] = [
                'name' => basename($this->file_path),
                'path' => $this->file_path,
                'size' => null,
                'mime' => null,
            ];
        }

        return $list;
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
