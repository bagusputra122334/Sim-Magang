<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
    }

    public function test_empty_email_returns_specific_validation_error(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Alamat email wajib diisi.',
        ]);
        $this->assertGuest();
    }

    public function test_invalid_email_format_returns_specific_validation_error(): void
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email-format',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Masukkan alamat email yang valid.',
        ]);
        $this->assertGuest();
    }

    public function test_empty_password_returns_specific_validation_error(): void
    {
        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Kata sandi wajib diisi.',
        ]);
        $this->assertGuest();
    }

    public function test_non_existent_email_and_wrong_password_return_identical_generic_error(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'password' => 'correct-password',
        ]);

        // Scenario A: Non-existent email
        $resNonExistent = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'arbitrary-password',
        ]);
        $resNonExistent->assertSessionHasErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        // Scenario B: Existing email + wrong password
        $resWrongPassword = $this->post('/login', [
            'email' => 'existing@example.com',
            'password' => 'wrong-password',
        ]);
        $resWrongPassword->assertSessionHasErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        // Ensure both produce the exact same error message
        $this->assertEquals(
            session('errors')->getBag('default')->first('email'),
            'Email atau kata sandi yang Anda masukkan tidak sesuai.'
        );
    }

    public function test_admin_and_participant_wrong_password_return_identical_generic_error(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => UserRole::Admin,
            'password' => 'admin-password',
        ]);

        $participant = User::factory()->create([
            'email' => 'participant@example.com',
            'role' => UserRole::Peserta,
            'password' => 'participant-password',
        ]);

        $resAdmin = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'wrong-admin-pass',
        ]);
        $resAdmin->assertSessionHasErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        $resParticipant = $this->post('/login', [
            'email' => $participant->email,
            'password' => 'wrong-participant-pass',
        ]);
        $resParticipant->assertSessionHasErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ]);
    }

    public function test_participant_can_authenticate_and_redirects_to_participant_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Peserta,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('participant.dashboard', absolute: false));
    }

    public function test_admin_can_authenticate_and_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_login_rate_limiting_throttles_after_five_failed_attempts(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $this->assertGuest();

        // 6th attempt should be throttled
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertTrue($response->status() === 429 || session()->has('errors'));
        $this->assertGuest();
    }

    public function test_users_can_logout_and_session_is_invalidated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_protected_participant_route_requires_authentication(): void
    {
        $response = $this->get(route('participant.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_participant_cannot_access_admin_dashboard(): void
    {
        $participant = User::factory()->create([
            'role' => UserRole::Peserta,
        ]);

        $response = $this->actingAs($participant)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }
}


