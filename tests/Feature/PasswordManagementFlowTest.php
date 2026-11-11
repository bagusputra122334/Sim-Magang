<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Opd;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordManagementFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SettingSeeder::class);
    }

    public function test_user_can_update_their_own_password_when_authenticated()
    {
        $user = User::factory()->create([
            'role' => UserRole::Peserta,
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword123!', $user->password));
    }

    public function test_admin_opd_can_reset_pembimbing_password_directly()
    {
        $division = Division::factory()->create();
        $adminOpd = User::factory()->create([
            'role' => UserRole::Admin,
            'division_id' => $division->id,
        ]);

        $pembimbing = User::factory()->create([
            'role' => UserRole::Pembimbing,
            'division_id' => $division->id,
            'nip' => '198501012010011001',
            'password' => Hash::make('OriginalPassword#1'),
        ]);

        $response = $this->actingAs($adminOpd)->put(route('admin.pembimbing.update', $pembimbing->id), [
            'name' => $pembimbing->name,
            'nip' => $pembimbing->nip,
            'email' => $pembimbing->email,
            'division_id' => $division->id,
            'password' => 'AdminResetPassword#2026',
        ]);

        $response->assertRedirect(route('admin.pembimbing.index'));
        $response->assertSessionHas('success');

        $pembimbing->refresh();
        $this->assertTrue(Hash::check('AdminResetPassword#2026', $pembimbing->password));
    }

    public function test_super_admin_can_reset_admin_opd_password_directly()
    {
        $opd = Opd::factory()->create();
        $superAdmin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
        ]);

        $adminOpd = User::factory()->create([
            'role' => UserRole::Admin,
            'opd_id' => $opd->id,
            'password' => Hash::make('AdminOldPassword#1'),
        ]);

        $response = $this->actingAs($superAdmin)->put(route('admin.opd-admins.update', $adminOpd->id), [
            'name' => $adminOpd->name,
            'email' => $adminOpd->email,
            'role' => 'admin',
            'opd_id' => $opd->id,
            'password' => 'SuperAdminReset#2026',
        ]);

        $response->assertRedirect(route('admin.opd-admins.index'));
        $response->assertSessionHas('success');

        $adminOpd->refresh();
        $this->assertTrue(Hash::check('SuperAdminReset#2026', $adminOpd->password));
    }

    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create([
            'email' => 'peserta.test@tubankab.go.id',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'peserta.test@tubankab.go.id',
        ]);

        $response->assertSessionHas('status');
    }
}
