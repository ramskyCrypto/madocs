<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'gurutest',
            'password' => bcrypt('password123'),
            'status' => User::STATUS_AKTIF,
        ]);

        $response = $this->post('/login', [
            'username' => 'gurutest',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        User::factory()->create([
            'username' => 'gurutest',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'gurutest',
            'password' => 'salah-password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'username' => 'nonaktiftest',
            'password' => bcrypt('password123'),
            'status' => User::STATUS_NONAKTIF,
        ]);

        $response = $this->post('/login', [
            'username' => 'nonaktiftest',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }
}
