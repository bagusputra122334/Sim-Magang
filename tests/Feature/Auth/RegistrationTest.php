<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
    }

    public function test_registration_with_existing_email_prevents_user_enumeration(): void
    {
        \App\Models\User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Duplicate Attempt',
            'email' => 'existing@example.com',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
        ]);

        // Anti-enumeration: must redirect to login with generic status, NOT return validation error
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
        $response->assertSessionDoesntHaveErrors();
    }
}
