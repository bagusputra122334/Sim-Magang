<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\QueuedAdminResetPassword;
use App\Notifications\QueuedUserResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class MultiGuardPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_link_and_receives_queued_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role'  => UserRole::Peserta,
            'email' => 'peserta@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'peserta@example.com',
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo(
            $user,
            QueuedUserResetPassword::class,
            function (QueuedUserResetPassword $notification) use ($user) {
                $this->assertNotEmpty($notification->token);
                return true;
            }
        );
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'role'     => UserRole::Peserta,
            'email'    => 'peserta_reset@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        $token = Password::broker('users')->createToken($user);

        $response = $this->post(route('password.store'), [
            'token'                 => $token,
            'email'                 => 'peserta_reset@example.com',
            'password'              => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertRedirect(route('login'));
        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword123', $user->password));
    }

    public function test_admin_can_request_password_reset_link_and_receives_queued_notification(): void
    {
        Notification::fake();

        $admin = Admin::create([
            'name'     => 'Admin Test',
            'role'     => UserRole::Admin,
            'email'    => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.password.email'), [
            'email' => 'admin@example.com',
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo(
            $admin,
            QueuedAdminResetPassword::class,
            function (QueuedAdminResetPassword $notification) {
                $this->assertNotEmpty($notification->token);
                return true;
            }
        );
    }

    public function test_admin_can_reset_password_with_valid_token(): void
    {
        $admin = Admin::create([
            'name'     => 'Admin Reset Test',
            'role'     => UserRole::Admin,
            'email'    => 'admin_reset@example.com',
            'password' => Hash::make('oldadminpass123'),
        ]);

        $token = Password::broker('admins')->createToken($admin);

        $response = $this->post(route('admin.password.store'), [
            'token'                 => $token,
            'email'                 => 'admin_reset@example.com',
            'password'              => 'NewAdminPass123',
            'password_confirmation' => 'NewAdminPass123',
        ]);

        $response->assertRedirect(route('login'));
        $admin->refresh();
        $this->assertTrue(Hash::check('NewAdminPass123', $admin->password));
    }
}
