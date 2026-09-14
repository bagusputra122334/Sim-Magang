<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /**
     * Cache key for storing global application settings.
     */
    public const CACHE_KEY = 'global_app_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Boot model and register cache invalidation event listeners.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * Helper to retrieve setting value by key via cached global helper.
     */
    public static function getByKey(string $key, mixed $default = null): mixed
    {
        return get_setting($key, $default);
    }

    /**
     * Helper to set or update setting by key.
     */
    public static function setByKey(string $key, mixed $value, string $type = 'text', string $group = 'general'): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }
}
