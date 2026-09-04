<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Programme;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_and_booking_flow_works(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/')->assertOk()->assertSee('OUATTARA');
        $this->get('/programmes')->assertOk();

        $programme = Programme::published()->first();
        $this->get("/programmes/{$programme->slug}")->assertOk();
        $this->get('/reserver-une-session')->assertOk();
        $this->get('/contact')->assertOk();

        $this->get("/reserver-une-session?programme={$programme->id}")->assertOk();

        $response = $this->post('/reserver-une-session', [
            'service' => 'Coaching individuel',
            'prenom' => 'Test',
            'nom' => 'Client',
            'email' => 'test.client@example.ci',
            'programme_id' => $programme->id,
            'date' => now()->addDays(10)->toDateString(),
            'heure' => '09:00',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Customer::where('email', 'test.client@example.ci')->exists());
    }

    public function test_guests_cannot_access_admin_but_admins_can(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/login')->assertOk();

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $this->get('/admin/dashboard')->assertOk();
        $this->get('/admin/programmes')->assertOk();
        $this->get('/admin/programmes/create')->assertOk();
        $programme = Programme::first();
        $this->get("/admin/programmes/{$programme->id}/edit")->assertOk();
        $this->get('/admin/clients')->assertOk();
        $customer = Customer::first();
        $this->get("/admin/clients/{$customer->id}")->assertOk();
        $this->get('/admin/reservations')->assertOk();
        $this->get('/admin/contenus')->assertOk();
        $this->get('/admin/clients/export')->assertOk();
    }

    public function test_staff_role_cannot_access_admin(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($staff)->get('/admin/dashboard')->assertForbidden();
    }
}
