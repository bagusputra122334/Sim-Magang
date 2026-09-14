<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    use HasFactory;

    protected $table = 'landing_contents';

    protected $fillable = [
        'section',
        'title',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope query to active landing content items.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query to filter by section.
     */
    public function scopeSection(Builder $query, string $section): Builder
    {
        return $query->where('section', $section);
    }

    /**
     * Scope query to order items by display order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Helper to get active items for a specific section sorted by order.
     */
    public static function getBySection(string $section)
    {
        return static::active()
            ->section($section)
            ->ordered()
            ->get();
    }
}
