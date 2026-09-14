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
