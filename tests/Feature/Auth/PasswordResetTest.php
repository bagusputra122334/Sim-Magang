<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\CustomResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 0: Screen can be rendered
     */
    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    /**
     * TEST 1: Registered email -> request reset -> reset email notification generated.
     */
    public function test_registered_email_generates_password_reset_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'peserta@example.com']);

        $response = $this->post('/forgot-password', ['email' => 'peserta@example.com']);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, CustomResetPasswordNotification::class);
    }

    /**
     * TEST 2: Open reset link -> valid reset password page rendered with email & token.
     */
    public function test_open_reset_link_renders_valid_reset_page(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, CustomResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->get('/reset-password/' . $notification->token . '?email=' . urlencode($user->email));

            $response->assertStatus(200);
            $response->assertSee($user->email);
            return true;
        });
    }

    /**
     * TEST 3 & TEST 4: Valid new password + confirmation -> password changes & user can log in with new password.
     */
    public function test_password_can_be_reset_and_user_can_login_with_new_password(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email'    => 'user_reset@example.com',
            'password' => Hash::make('old-password-123'),
        ]);

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, CustomResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'NewSecurePassword123!',
                'password_confirmation' => 'NewSecurePassword123!',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'))
                ->assertSessionHas('status');

            // Verify the user password has changed in database
            $user->refresh();
            $this->assertTrue(Hash::check('NewSecurePassword123!', $user->password));

            // Test login with new password (TEST 4)
            $loginResponse = $this->post('/login', [
                'email'    => 'user_reset@example.com',
                'password' => 'NewSecurePassword123!',
            ]);

            $this->assertAuthenticatedAs($user);
            return true;
        });
    }

    /**
     * TEST 5: Expired token -> rejected.
     */
    public function test_expired_token_is_rejected(): void
    {
        $user = User::factory()->create(['email' => 'expired_token@example.com']);

        $token = Password::createToken($user);

        // Manually age the token past 60 minutes
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->update(['created_at' => Carbon::now()->subMinutes(61)]);

        $response = $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    /**
     * TEST 6: Invalid token -> rejected.
     */
    public function test_invalid_token_is_rejected(): void
    {
        $user = User::factory()->create(['email' => 'invalid_token@example.com']);

        $response = $this->post('/reset-password', [
            'token'                 => 'completely-invalid-random-token',
            'email'                 => $user->email,
            'password'              => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    /**
     * TEST 7: Already-used token -> rejected upon reuse.
     */
    public function test_already_used_token_is_rejected_on_second_attempt(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reused_token@example.com']);

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, CustomResetPasswordNotification::class, function ($notification) use ($user) {
            // First reset: should succeed
            $this->post('/reset-password', [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'FirstNewPassword123!',
                'password_confirmation' => 'FirstNewPassword123!',
            ])->assertSessionHasNoErrors();

            // Second reset using the same token: must fail
            $secondAttempt = $this->post('/reset-password', [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'SecondNewPassword123!',
                'password_confirmation' => 'SecondNewPassword123!',
            ]);

            $secondAttempt->assertSessionHasErrors(['email']);
            $this->assertTrue(Hash::check('FirstNewPassword123!', $user->fresh()->password));
            return true;
        });
    }

    /**
     * TEST 8: Password confirmation mismatch -> validation error.
     */
    public function test_password_confirmation_mismatch_fails_validation(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'PasswordOne123!',
            'password_confirmation' => 'PasswordDifferent456!',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * TEST 9: Invalid email format -> validation error.
     */
    public function test_invalid_email_format_fails_validation(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'not-an-email-address',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * TEST 10: Unknown email -> generic success message without revealing account existence (prevents enumeration).
     */
    public function test_unknown_email_returns_generic_success_without_enumerating_account(): void
    {
        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent_account@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status');

        // No notification sent since account does not exist
        Notification::assertNothingSent();
    }
}

