<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Programme;
use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCapacityTest extends TestCase
{
    use RefreshDatabase;

    private function fullProgramme(): Programme
    {
        $programme = Programme::create([
            'title' => 'Atelier complet',
            'description' => 'Un atelier avec une seule place, déjà prise.',
            'age_min' => 18,
            'age_max' => 60,
            'type' => 'group',
            'price' => 10000,
            'max_participants' => 1,
            'start_date' => now()->addMonth()->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'status' => 'published',
        ]);

        $customer = Customer::create([
            'first_name' => 'Déjà', 'last_name' => 'Inscrit', 'email' => 'deja.inscrit@example.ci',
            'phone' => '+225 01 00 00 00', 'source' => 'website', 'status' => 'active',
        ]);

        Session::create([
            'customer_id' => $customer->id,
            'programme_id' => $programme->id,
            'type' => 'group',
            'preferred_date' => now()->addWeeks(5)->toDateString(),
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return $programme;
    }

    public function test_available_seats_is_exposed_on_the_public_programme(): void
    {
        $programme = $this->fullProgramme();

        $response = $this->get("/programmes/{$programme->slug}");

        $response->assertOk();
        $this->assertSame(0, $programme->fresh()->available_seats);
    }

    public function test_booking_a_full_programme_is_rejected(): void
    {
        $programme = $this->fullProgramme();

        $response = $this->post('/reserver-une-session', [
            'first_name' => 'Nouveau', 'last_name' => 'Client', 'email' => 'nouveau.client@example.ci',
            'phone' => '+225 07 00 11 22', 'type' => 'group', 'programme_id' => $programme->id,
            'preferred_date' => now()->addDays(10)->toDateString(),
        ]);

        $response->assertSessionHasErrors('programme_id');
        $this->assertFalse(Customer::where('email', 'nouveau.client@example.ci')->exists());
    }

    public function test_booking_without_a_programme_is_unaffected_by_capacity_checks(): void
    {
        $response = $this->post('/reserver-une-session', [
            'first_name' => 'Session', 'last_name' => 'Perso', 'email' => 'session.perso@example.ci',
            'phone' => '+225 07 00 11 22', 'type' => 'individual',
            'preferred_date' => now()->addDays(10)->toDateString(),
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertTrue(Customer::where('email', 'session.perso@example.ci')->exists());
    }
}
