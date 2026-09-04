<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Programme;
use App\Models\Session;
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

        $this->get('/')->assertOk()->assertSee('Stéphane Ouattara');
        $this->get('/programmes')->assertOk();

        $slug = Programme::published()->first()->slug;
        $this->get("/programmes/{$slug}")->assertOk();
        $this->get('/reserver-une-session')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/adolescents')->assertRedirect();
        $this->get('/entreprises')->assertRedirect();

        // Réservation avec préselection depuis une fiche programme
        $programme = Programme::published()->first();
        $this->get("/reserver-une-session?programme={$programme->id}&type=club_des_champions")->assertOk();

        $response = $this->post('/reserver-une-session', [
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'test.client@example.ci',
            'phone' => '+225 07 00 11 22',
            'type' => 'group',
            'programme_id' => Programme::published()->first()->id,
            'preferred_date' => now()->addDays(10)->toDateString(),
            'message' => 'Je souhaite participer.',
        ]);

        $response->assertRedirect(route('reserver-une-session'));
        $this->assertTrue(Customer::where('email', 'test.client@example.ci')->exists());
        $this->assertTrue(
            Session::whereHas('customer', fn ($q) => $q->where('email', 'test.client@example.ci'))->exists()
        );
    }

    public function test_guests_cannot_access_admin_but_admins_can(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/admin/dashboard')->assertRedirect(route('admin.login'));
        $this->get('/admin/login')->assertOk();

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $this->get('/admin/login')->assertRedirect('/admin/dashboard');
        $this->get('/admin/dashboard')->assertOk();
        $this->get('/admin/analytics')->assertOk();
        $this->get('/admin/programmes')->assertOk();
        $this->get('/admin/programmes/create')->assertOk();
        $programme = Programme::first();
        $this->get("/admin/programmes/{$programme->id}/edit")->assertOk();
        $this->get('/admin/customers')->assertOk();
        $customer = Customer::first();
        $this->get("/admin/customers/{$customer->id}")->assertOk();
        $this->get('/admin/content')->assertOk();

        $export = $this->get('/admin/customers/export');
        $export->assertOk();
    }

    public function test_admin_can_create_a_programme(): void
    {
        $this->seed(DatabaseSeeder::class);
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $data = [
            'title' => 'Nouveau Programme Test',
            'description' => 'Une description complète du programme de test.',
            'age_min' => 15,
            'age_max' => 40,
            'type' => 'group',
            'price' => 50000,
            'max_participants' => 25,
            'start_date' => now()->addMonth()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'status' => 'published',
            'featured' => false,
        ];

        $this->followingRedirects()->post(route('admin.programmes.store'), $data)
            ->assertOk();
        $this->assertDatabaseHas('programmes', ['title' => 'Nouveau Programme Test']);
    }
}
