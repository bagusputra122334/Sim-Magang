<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOpd;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use BelongsToOpd, HasFactory;

    protected $fillable = ['nama_divisi', 'opd_id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
