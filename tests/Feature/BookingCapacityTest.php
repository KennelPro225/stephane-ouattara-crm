<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Programme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCapacityTest extends TestCase
{
    use RefreshDatabase;

    private function fullProgramme(): Programme
    {
        $programme = Programme::create([
            'title' => 'Atelier complet',
            'audience' => 'adultes',
            'type' => 'group',
            'description' => 'Un atelier avec une seule place, déjà prise.',
            'price_label' => '10 000 FCFA',
            'start_date' => now()->addMonth()->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'duration_label' => '1 séance',
            'ages_label' => 'Adultes',
            'max_participants' => 1,
            'status' => 'published',
        ]);

        $customer = Customer::create([
            'first_name' => 'Déjà', 'last_name' => 'Inscrit', 'email' => 'deja.inscrit@example.ci',
            'phone' => '+225 01 00 00 00', 'source' => 'Site web',
        ]);

        Booking::create([
            'customer_id' => $customer->id,
            'programme_id' => $programme->id,
            'service_type' => 'group',
            'preferred_date' => now()->addWeeks(5)->toDateString(),
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return $programme;
    }

    public function test_available_seats_is_exposed_on_the_public_programme(): void
    {
        $programme = $this->fullProgramme();

        $this->get("/programmes/{$programme->slug}")->assertOk();
        $this->assertSame(0, $programme->fresh()->available_seats);
    }

    public function test_booking_a_full_programme_is_rejected(): void
    {
        $programme = $this->fullProgramme();

        $response = $this->post('/reserver-une-session', [
            'service' => 'Coaching de groupe',
            'prenom' => 'Nouveau', 'nom' => 'Client', 'email' => 'nouveau.client@example.ci',
            'programme_id' => $programme->id,
            'date' => now()->addDays(10)->toDateString(),
        ]);

        $response->assertSessionHasErrors('programme_id');
        $this->assertFalse(Customer::where('email', 'nouveau.client@example.ci')->exists());
    }

    public function test_booking_without_a_programme_is_unaffected_by_capacity_checks(): void
    {
        $response = $this->post('/reserver-une-session', [
            'service' => 'Autres',
            'prenom' => 'Session', 'nom' => 'Perso', 'email' => 'session.perso@example.ci',
            'date' => now()->addDays(10)->toDateString(),
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertTrue(Customer::where('email', 'session.perso@example.ci')->exists());
    }
}
