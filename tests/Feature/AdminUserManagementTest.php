<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user_and_generate_password_setup_token(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->post(route('admin.usuarios.store'), [
                'name' => 'Oliver Cliente',
                'email' => 'oliver@example.com',
                'telefono' => '5555-0000',
                'direccion' => 'Antigua Guatemala',
                'role' => 'cliente',
            ])
            ->assertSessionHas('success')
            ->assertRedirect();

        $user = User::where('email', 'oliver@example.com')->firstOrFail();

        $this->assertSame('Oliver Cliente', $user->name);
        $this->assertTrue($user->hasRole('cliente'));
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'oliver@example.com',
        ]);
    }

    public function test_non_admin_cannot_create_users(): void
    {
        $user = User::factory()->create();
        $user->assignRole('cliente');

        $this->actingAs($user)
            ->post(route('admin.usuarios.store'), [
                'name' => 'Blocked',
                'email' => 'blocked@example.com',
                'role' => 'cliente',
            ])
            ->assertRedirect('/admin');

        $this->assertFalse(User::where('email', 'blocked@example.com')->exists());
        $this->assertSame(0, DB::table('password_reset_tokens')->count());
    }
}
