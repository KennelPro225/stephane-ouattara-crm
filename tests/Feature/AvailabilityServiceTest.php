<?php

namespace Tests\Feature;

use App\Models\AvailabilityRule;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Programme;
use App\Models\User;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private AvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AvailabilityService::class);
        AvailabilityRule::ensureDefaults();
    }

    private function programme(array $attributes): Programme
    {
        return Programme::create($attributes + [
            'title' => 'Programme test '.uniqid(),
            'audience' => 'adultes', 'type' => 'group',
            'description' => 'x', 'price_label' => 'x', 'duration_label' => 'x', 'ages_label' => 'x',
            'max_participants' => 10, 'status' => 'published',
            'created_by' => User::factory()->create(['role' => 'admin'])->id,
        ]);
    }

    public function test_closed_day_has_no_slots(): void
    {
        AvailabilityRule::where('weekday', 0)->update(['is_open' => false]);

        $sunday = Carbon::parse('next sunday');

        $this->assertSame([], $this->service->slotsForDate($sunday));
    }

    public function test_open_day_generates_evenly_spaced_slots(): void
    {
        set_setting('booking_slot_minutes', 60);
        $monday = Carbon::parse('next monday');
        AvailabilityRule::where('weekday', 1)->update(['is_open' => true, 'start_time' => '09:00', 'end_time' => '12:00']);

        $this->assertSame(['09:00', '10:00', '11:00'], $this->service->slotsForDate($monday));
    }

    public function test_existing_booking_removes_its_slot(): void
    {
        set_setting('booking_slot_minutes', 60);
        $monday = Carbon::parse('next monday');
        AvailabilityRule::where('weekday', 1)->update(['is_open' => true, 'start_time' => '09:00', 'end_time' => '12:00']);

        $customer = Customer::create(['first_name' => 'Test', 'last_name' => 'Client', 'email' => uniqid('client').'@exemple.ci']);
        Booking::create([
            'customer_id' => $customer->id,
            'service_type' => 'individual',
            'preferred_date' => $monday->toDateString(),
            'preferred_time' => '10:00',
            'status' => 'pending',
        ]);

        $this->assertSame(['09:00', '11:00'], $this->service->slotsForDate($monday));
    }

    public function test_scheduled_group_programme_removes_its_interval(): void
    {
        set_setting('booking_slot_minutes', 60);
        $wednesday = Carbon::parse('next wednesday');
        AvailabilityRule::where('weekday', 3)->update(['is_open' => true, 'start_time' => '09:00', 'end_time' => '13:00']);

        $admin = User::factory()->create(['role' => 'admin']);
        Programme::create([
            'title' => 'Club test', 'slug' => 'club-test', 'audience' => 'adolescents', 'type' => 'group',
            'description' => 'x', 'price_label' => 'x', 'duration_label' => 'x', 'ages_label' => 'x',
            'max_participants' => 10, 'status' => 'published', 'created_by' => $admin->id,
            'start_date' => $wednesday->copy()->subWeek(), 'end_date' => $wednesday->copy()->addWeek(),
            'schedule_mode' => 'weekly', 'session_weekday' => 3, 'session_start_time' => '10:00', 'session_duration_minutes' => 120,
        ]);

        $this->assertSame(['09:00', '12:00'], $this->service->slotsForDate($wednesday));
    }

    public function test_full_day_weekly_programme_blocks_the_whole_day(): void
    {
        set_setting('booking_slot_minutes', 60);
        $wednesday = Carbon::parse('next wednesday');
        AvailabilityRule::where('weekday', 3)->update(['is_open' => true, 'start_time' => '09:00', 'end_time' => '18:00']);

        $this->programme([
            'start_date' => $wednesday->copy()->subWeek(), 'end_date' => $wednesday->copy()->addWeek(),
            'schedule_mode' => 'weekly_full_day', 'session_weekday' => 3,
        ]);

        $this->assertSame([], $this->service->slotsForDate($wednesday));
        // The neighbouring days are untouched.
        $this->assertNotEmpty($this->service->slotsForDate($wednesday->copy()->addDay()));
    }

    public function test_full_period_programme_blocks_every_date_of_its_range(): void
    {
        set_setting('booking_slot_minutes', 60);
        $start = Carbon::parse('next monday');
        $end = $start->copy()->addDays(2);

        $this->programme([
            'start_date' => $start, 'end_date' => $end, 'schedule_mode' => 'full_period',
        ]);

        foreach ([$start, $start->copy()->addDay(), $end] as $blocked) {
            $this->assertSame([], $this->service->slotsForDate($blocked), $blocked->toDateString().' devrait être bloqué');
        }

        $this->assertNotEmpty($this->service->slotsForDate($end->copy()->addDay()));
    }

    public function test_available_dates_in_month_skips_blocked_closed_and_past_dates(): void
    {
        set_setting('booking_slot_minutes', 60);
        $blocked = Carbon::today()->addMonth()->startOfMonth()->next(Carbon::TUESDAY);

        $this->programme([
            'start_date' => $blocked, 'end_date' => $blocked, 'schedule_mode' => 'full_period',
        ]);

        $dates = $this->service->availableDatesInMonth($blocked->format('Y-m'));

        $this->assertNotContains($blocked->toDateString(), $dates);
        // Sunday is closed by default, and nothing before today is ever offered.
        foreach ($dates as $date) {
            $parsed = Carbon::parse($date);
            $this->assertNotSame(Carbon::SUNDAY, $parsed->dayOfWeek);
            $this->assertTrue($parsed->gte(Carbon::today()));
        }
    }

    public function test_adjacent_non_overlapping_slot_stays_available(): void
    {
        set_setting('booking_slot_minutes', 60);
        $monday = Carbon::parse('next monday');
        AvailabilityRule::where('weekday', 1)->update(['is_open' => true, 'start_time' => '09:00', 'end_time' => '12:00']);

        $customer = Customer::create(['first_name' => 'Test', 'last_name' => 'Client', 'email' => uniqid('client').'@exemple.ci']);
        Booking::create([
            'customer_id' => $customer->id,
            'service_type' => 'individual',
            'preferred_date' => $monday->toDateString(),
            'preferred_time' => '10:00',
            'status' => 'pending',
        ]);

        $slots = $this->service->slotsForDate($monday);

        $this->assertContains('09:00', $slots);
        $this->assertContains('11:00', $slots);
        $this->assertNotContains('10:00', $slots);
    }
}
