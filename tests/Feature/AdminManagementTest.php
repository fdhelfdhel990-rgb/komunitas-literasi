<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_super_admin_cannot_access_user_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
            'username' => 'adminbiasa',
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_new_admin_with_valid_username(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super-admin',
            'status' => 'aktif',
            'username' => 'superadmin',
        ]);

        $response = $this->actingAs($superAdmin)->post('/admin/users', [
            'name' => 'Admin Baru',
            'username' => 'admin.baru_01',
            'email' => 'baru@example.com',
            'password' => 'rahasia123',
            'privileges' => ['publikasi', 'digital_library'],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'admin.baru_01',
            'email' => 'baru@example.com',
            'status' => 'aktif',
        ]);
    }
}