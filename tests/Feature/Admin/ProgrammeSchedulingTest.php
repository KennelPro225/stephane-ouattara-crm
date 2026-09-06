<?php

namespace Tests\Feature\Admin;

use App\Models\AvailabilityRule;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProgrammeSchedulingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        AvailabilityRule::ensureDefaults();
        set_setting('booking_slot_minutes', 60);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** A valid, complete programme payload; override what each test is about. */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Programme planifié',
            'audience' => 'adolescents',
            'type' => 'group',
            'description' => 'Un programme récurrent.',
            'price_label' => '100 000 FCFA',
            'start_date' => Carbon::today()->addWeek()->toDateString(),
            'end_date' => Carbon::today()->addMonths(3)->toDateString(),
            'duration_label' => '12 semaines',
            'ages_label' => '12 – 15 ans',
            'max_participants' => 20,
            'status' => 'published',
            'schedule_mode' => 'weekly',
            'session_weekday' => 2,
            'session_start_time' => '10:00',
            'session_duration_minutes' => 120,
        ], $overrides);
    }

    private function existingProgramme(array $overrides = []): Programme
    {
        return Programme::create($this->payload($overrides) + ['created_by' => $this->admin->id]);
    }

    public function test_session_on_a_closed_day_is_rejected(): void
    {
        AvailabilityRule::where('weekday', 0)->update(['is_open' => false]);

        $this->actingAs($this->admin)
            ->post('/admin/programmes', $this->payload(['session_weekday' => 0]))
            ->assertSessionHasErrors('session_weekday');

        $this->assertDatabaseCount('programmes', 0);
    }

    public function test_session_running_past_closing_time_is_rejected(): void
    {
        AvailabilityRule::where('weekday', 2)->update(['is_open' => true, 'start_time' => '09:00', 'end_time' => '18:00']);

        $this->actingAs($this->admin)
            ->post('/admin/programmes', $this->payload(['session_start_time' => '17:00', 'session_duration_minutes' => 120]))
            ->assertSessionHasErrors('session_start_time');

        $this->assertDatabaseCount('programmes', 0);
    }

    public function test_session_overlapping_another_programme_is_rejected(): void
    {
        $this->existingProgramme(['title' => 'Déjà planifié']);

        $this->actingAs($this->admin)
            ->post('/admin/programmes', $this->payload(['title' => 'Nouveau', 'session_start_time' => '11:00']))
            ->assertSessionHasErrors('schedule_mode');

        $this->assertDatabaseMissing('programmes', ['title' => 'Nouveau']);
    }

    public function test_full_day_programme_conflicts_with_a_timed_session_the_same_day(): void
    {
        $this->existingProgramme(['title' => 'Séance minutée']);

        $this->actingAs($this->admin)
            ->post('/admin/programmes', $this->payload([
                'title' => 'Journée entière',
                'schedule_mode' => 'weekly_full_day',
                'session_start_time' => null,
                'session_duration_minutes' => null,
            ]))
            ->assertSessionHasErrors('schedule_mode');
    }

    /** A real browser posts every field as a string — the maths must still work. */
    public function test_conflict_detection_handles_string_form_input(): void
    {
        $this->existingProgramme(['title' => 'Déjà planifié']);

        $this->actingAs($this->admin)
            ->post('/admin/programmes', $this->payload([
                'title' => 'Nouveau',
                'session_weekday' => '2',
                'session_start_time' => '11:00',
                'session_duration_minutes' => '120',
                'max_participants' => '20',
            ]))
            ->assertSessionHasErrors('schedule_mode');
    }

    public function test_non_overlapping_session_is_accepted(): void
    {
        $this->existingProgramme(['title' => 'Déjà planifié']);

        $this->actingAs($this->admin)
            ->post('/admin/programmes', $this->payload(['title' => 'Nouveau', 'session_start_time' => '14:00']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('programmes', ['title' => 'Nouveau', 'schedule_mode' => 'weekly']);
    }

    public function test_editing_a_programme_does_not_conflict_with_itself(): void
    {
        $programme = $this->existingProgramme();

        $this->actingAs($this->admin)
            ->put("/admin/programmes/{$programme->id}", $this->payload(['session_duration_minutes' => 60]))
            ->assertSessionHasNoErrors();

        $this->assertSame(60, $programme->fresh()->session_duration_minutes);
    }

    public function test_scheduling_over_an_existing_booking_is_saved_but_warns(): void
    {
        $customer = Customer::create([
            'first_name' => 'Awa', 'last_name' => 'Koné', 'email' => 'awa.conflict@exemple.ci',
        ]);

        // A client already booked the very slot the programme is about to take.
        $clash = Carbon::today()->addWeeks(2)->next(Carbon::TUESDAY);
        Booking::create([
            'customer_id' => $customer->id,
            'service_type' => 'individual',
            'preferred_date' => $clash->toDateString(),
            'preferred_time' => '10:00',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/programmes', $this->payload());

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('warning');
        $this->assertStringContainsString('Awa Koné', session('warning'));
        $this->assertDatabaseHas('programmes', ['title' => 'Programme planifié']);
    }
}
