<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingManagementTest extends TestCase
{
    use RefreshDatabase;

    private function pendingBooking(int $maxParticipants = 20): Booking
    {
        $programme = Programme::create([
            'title' => 'Programme Test', 'audience' => 'adultes', 'type' => 'group',
            'description' => 'Description de test.', 'price_label' => '10 000 FCFA',
            'start_date' => now()->addMonth()->toDateString(), 'end_date' => now()->addMonths(2)->toDateString(),
            'duration_label' => '4 séances', 'ages_label' => 'Adultes',
            'max_participants' => $maxParticipants, 'status' => 'published',
        ]);

        $customer = Customer::create([
            'first_name' => 'Jean', 'last_name' => 'Test', 'email' => 'jean.test@example.ci',
            'phone' => '+225 01 00 00 00', 'source' => 'Site web',
        ]);

        return Booking::create([
            'customer_id' => $customer->id, 'programme_id' => $programme->id, 'service_type' => 'group',
            'preferred_date' => now()->addWeeks(5)->toDateString(), 'status' => 'pending',
        ]);
    }

    public function test_guests_cannot_manage_bookings(): void
    {
        $booking = $this->pendingBooking();

        $this->get('/admin/reservations')->assertRedirect(route('login'));
        $this->patch("/admin/reservations/{$booking->id}", ['status' => 'confirmed'])
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_confirm_a_pending_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->pendingBooking();

        $response = $this->actingAs($admin)->patch(route('admin.bookings.update', $booking), ['status' => 'confirmed']);

        $response->assertSessionHasNoErrors();
        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertNotNull($booking->confirmed_at);
    }

    public function test_admin_cannot_confirm_a_booking_for_a_full_programme(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->pendingBooking(maxParticipants: 1);

        Booking::create([
            'customer_id' => $booking->customer_id, 'programme_id' => $booking->programme_id, 'service_type' => 'group',
            'preferred_date' => now()->addWeeks(6)->toDateString(), 'status' => 'confirmed', 'confirmed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.bookings.update', $booking), ['status' => 'confirmed']);

        $response->assertSessionHasErrors('status');
        $this->assertSame('pending', $booking->fresh()->status);
    }

    public function test_completed_bookings_cannot_be_reopened(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->pendingBooking();
        $booking->update(['status' => 'completed']);

        $response = $this->actingAs($admin)->patch(route('admin.bookings.update', $booking), ['status' => 'confirmed']);

        $response->assertSessionHasErrors('status');
        $this->assertSame('completed', $booking->fresh()->status);
    }
}
