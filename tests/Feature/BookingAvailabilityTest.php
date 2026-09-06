<?php

namespace Tests\Feature;

use App\Models\AvailabilityRule;
use App\Models\Booking;
use App\Models\Programme;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BookingAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        AvailabilityRule::ensureDefaults();
        set_setting('booking_slot_minutes', 60);
        AvailabilityRule::where('weekday', 1)->update(['is_open' => true, 'start_time' => '09:00', 'end_time' => '11:00']);
    }

    private function payload(Carbon $date, string $heure): array
    {
        return [
            'service' => 'Coaching individuel',
            'prenom' => 'Test',
            'nom' => 'Client',
            'email' => 'booking.availability@exemple.ci',
            'date' => $date->toDateString(),
            'heure' => $heure,
        ];
    }

    public function test_booking_a_time_outside_computed_slots_is_rejected(): void
    {
        $monday = Carbon::parse('next monday');

        $response = $this->post('/reserver-une-session', $this->payload($monday, '15:00'));

        $response->assertSessionHasErrors('heure');
        $this->assertDatabaseMissing('bookings', ['preferred_time' => '15:00']);
    }

    public function test_booking_an_available_time_succeeds(): void
    {
        $monday = Carbon::parse('next monday');

        $response = $this->post('/reserver-une-session', $this->payload($monday, '09:00'));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('bookings', ['preferred_time' => '09:00']);
    }

    public function test_booking_on_a_full_day_blocked_date_is_rejected(): void
    {
        $monday = Carbon::parse('next monday');

        Programme::create([
            'title' => 'Séminaire immersif', 'audience' => 'entreprises', 'type' => 'corporate',
            'description' => 'x', 'price_label' => 'x', 'duration_label' => 'x', 'ages_label' => 'x',
            'max_participants' => 30, 'status' => 'published',
            'start_date' => $monday, 'end_date' => $monday,
            'schedule_mode' => 'full_period',
        ]);

        $this->assertSame([], app(AvailabilityService::class)->slotsForDate($monday));

        // Rejected even without a time: the whole date is unavailable.
        $payload = $this->payload($monday, '09:00');
        unset($payload['heure']);

        $this->post('/reserver-une-session', $payload)->assertSessionHasErrors('date');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_slot_disappears_after_being_booked(): void
    {
        $monday = Carbon::parse('next monday');

        $this->post('/reserver-une-session', $this->payload($monday, '09:00'))->assertSessionHasNoErrors();

        $slots = app(AvailabilityService::class)->slotsForDate($monday);

        $this->assertNotContains('09:00', $slots);
        $this->assertContains('10:00', $slots);
    }
}
