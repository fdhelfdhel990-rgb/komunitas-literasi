<?php

namespace Tests\Feature;

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
        $response->assertSee('Email');
        $response->assertSee('Password');
        $response->assertSee('Ingat saya');
        $response->assertDontSee('Daftar');
        $response->assertDontSee('Belum punya akun?');
    }

    public function test_registration_screen_is_disabled(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_users_can_authenticate_using_the_login_screen_with_email(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'username' => 'adminuser',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_users_can_authenticate_using_the_login_screen_with_username(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'username' => 'superadmin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'superadmin',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_authenticated_users_can_access_admin_dashboard_with_logo(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Workspace');
        $response->assertSee('Dashboard');
        $response->assertSee('Selamat datang di workspace.');
        $response->assertSee('logo/logo.png');
    }
}
