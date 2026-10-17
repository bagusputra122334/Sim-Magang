<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $settings = Cache::rememberForever('global_app_settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }
}

if (! function_exists('auto_link')) {
    function auto_link(?string $text, string $class = 'text-blue-600 dark:text-blue-400 underline font-semibold hover:text-blue-800 transition-colors'): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        return preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" class="' . $class . '" rel="noopener noreferrer">$1</a>',
            e($text)
        );
    }
}
