<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_setting_seeder_populates_default_settings(): void
    {
        $this->seed(SettingSeeder::class);

        $this->assertDatabaseHas('settings', [
            'key'   => 'site_title',
            'group' => 'global',
            'type'  => 'text',
        ]);

        $this->assertDatabaseHas('settings', [
            'key'   => 'hero_title',
            'group' => 'landing_page',
            'type'  => 'text',
        ]);

        $this->assertDatabaseHas('settings', [
            'key'   => 'contact_email',
            'value' => 'diskominfo@tubankab.go.id',
            'group' => 'contact',
            'type'  => 'text',
        ]);

        $this->assertEquals('SIMAGANG', get_setting('app_name'));
        $this->assertEquals('SIMAGANG', Setting::getByKey('app_name'));
    }

    public function test_get_setting_fallback(): void
    {
        $this->assertEquals('Default Value', get_setting('non_existent_key', 'Default Value'));
        $this->assertNull(get_setting('non_existent_key'));
    }

    public function test_setting_caching_and_invalidation(): void
    {
        $this->seed(SettingSeeder::class);

        // Warm up cache
        $this->assertEquals('SIMAGANG', get_setting('app_name'));
        $this->assertTrue(Cache::has('global_app_settings'));

        // DB queries should be 0 on subsequent get_setting calls
        DB::flushQueryLog();
        DB::enableQueryLog();

        $val1 = get_setting('app_name');
        $val2 = get_setting('site_title');

        $this->assertCount(0, DB::getQueryLog());
        $this->assertEquals('SIMAGANG', $val1);

        // Updating a setting invalidates cache
        Setting::setByKey('app_name', 'SIMAGANG V2');

        $this->assertFalse(Cache::has('global_app_settings'));
        $this->assertEquals('SIMAGANG V2', get_setting('app_name'));

        // Deleting a setting invalidates cache
        $setting = Setting::where('key', 'app_name')->first();
        $setting->delete();

        $this->assertFalse(Cache::has('global_app_settings'));
        $this->assertEquals('Fallback App', get_setting('app_name', 'Fallback App'));
    }
}
