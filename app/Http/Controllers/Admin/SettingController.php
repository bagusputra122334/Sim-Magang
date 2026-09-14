<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display global settings management form grouped by category.
     */
    public function index(): View
    {
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update global settings in database.
     */
    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($request->hasFile($key)) {
                $request->validate([
                    $key => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
                ]);

                $path = $request->file($key)->store('settings', 'public');
                $relativeStoragePath = 'storage/' . $path;

                if ($setting) {
                    $setting->update([
                        'value' => $relativeStoragePath,
                    ]);
                } else {
                    Setting::create([
                        'key'   => $key,
                        'value' => $relativeStoragePath,
                        'type'  => 'image',
                        'group' => 'general',
                    ]);
                }
            } else {
                if ($setting) {
                    $setting->update([
                        'value' => $value,
                    ]);
                } else {
                    Setting::create([
                        'key'   => $key,
                        'value' => $value,
                        'type'  => 'text',
                        'group' => 'general',
                    ]);
                }
            }
        }

        Cache::forget('global_app_settings');

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
