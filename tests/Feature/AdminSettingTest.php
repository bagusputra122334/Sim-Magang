<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $participant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SettingSeeder::class);

        $this->admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $this->participant = User::factory()->create([
            'role' => UserRole::Peserta,
        ]);
    }

    public function test_guest_cannot_access_settings(): void
    {
        $response = $this->get(route('admin.settings.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_participant_cannot_access_settings(): void
    {
        $response = $this->actingAs($this->participant)->get(route('admin.settings.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_settings_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan System');
        $response->assertSee('site_title');
        $response->assertSee('hero_title');
        $response->assertSee('contact_email');
    }

    public function test_admin_can_update_text_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'site_title'    => 'New Updated Site Title',
            'contact_email' => 'updated-admin@tubankab.go.id',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('New Updated Site Title', get_setting('site_title'));
        $this->assertEquals('updated-admin@tubankab.go.id', get_setting('contact_email'));
    }

    public function test_admin_can_upload_setting_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('custom_logo.png', 400, 400);

        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'site_logo' => $image,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $settingValue = get_setting('site_logo');
        $this->assertStringStartsWith('storage/settings/', $settingValue);

        $relativePath = str_replace('storage/', '', $settingValue);
        Storage::disk('public')->assertExists($relativePath);
    }
}
