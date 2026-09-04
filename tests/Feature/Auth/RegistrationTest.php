<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertStatus(404);
        $this->post('/register', [])->assertStatus(404);
    }

    public function test_guests_cannot_create_admin_accounts(): void
    {
        $response = $this->post(route('admin.users.store'), [
            'name' => 'Intrus',
            'email' => 'intrus@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('users', ['email' => 'intrus@example.com']);
    }

    public function test_an_admin_can_create_a_new_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Nouvelle Collaboratrice',
            'email' => 'staff@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'staff',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'staff@example.com', 'role' => 'staff']);
    }

    public function test_new_users_default_to_a_non_admin_role_at_the_database_level(): void
    {
        $id = \Illuminate\Support\Facades\DB::table('users')->insertGetId([
            'name' => 'Sans Rôle Explicite',
            'email' => 'norole@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('staff', User::find($id)->role);
    }
}
