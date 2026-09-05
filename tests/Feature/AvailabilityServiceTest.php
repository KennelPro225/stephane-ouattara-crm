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
            'session_weekday' => 3, 'session_start_time' => '10:00', 'session_duration_minutes' => 120,
        ]);

        $this->assertSame(['09:00', '12:00'], $this->service->slotsForDate($wednesday));
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
