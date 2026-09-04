<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\Programme;
use App\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionManagementTest extends TestCase
{
    use RefreshDatabase;

    private function pendingSession(int $maxParticipants = 20): Session
    {
        $programme = Programme::create([
            'title' => 'Programme Test', 'description' => 'Description de test suffisamment longue.',
            'age_min' => 18, 'age_max' => 60, 'type' => 'group', 'price' => 10000,
            'max_participants' => $maxParticipants,
            'start_date' => now()->addMonth()->toDateString(), 'end_date' => now()->addMonths(2)->toDateString(),
            'status' => 'published',
        ]);

        $customer = Customer::create([
            'first_name' => 'Jean', 'last_name' => 'Test', 'email' => 'jean.test@example.ci',
            'phone' => '+225 01 00 00 00', 'source' => 'website', 'status' => 'lead',
        ]);

        return Session::create([
            'customer_id' => $customer->id, 'programme_id' => $programme->id, 'type' => 'group',
            'preferred_date' => now()->addWeeks(5)->toDateString(), 'status' => 'pending',
        ]);
    }

    public function test_guests_cannot_manage_sessions(): void
    {
        $session = $this->pendingSession();

        $this->get('/admin/sessions')->assertRedirect(route('admin.login'));
        $this->patch("/admin/sessions/{$session->id}", ['status' => 'confirmed'])
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_confirm_a_pending_session(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $session = $this->pendingSession();

        $response = $this->actingAs($admin)->patch(route('admin.sessions.update', $session), ['status' => 'confirmed']);

        $response->assertSessionHasNoErrors();
        $session->refresh();
        $this->assertSame('confirmed', $session->status);
        $this->assertNotNull($session->confirmed_at);
    }

    public function test_admin_cannot_confirm_a_session_for_a_full_programme(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $session = $this->pendingSession(maxParticipants: 1);

        // Fill the single seat with another confirmed session first.
        Session::create([
            'customer_id' => $session->customer_id,
            'programme_id' => $session->programme_id,
            'type' => 'group',
            'preferred_date' => now()->addWeeks(6)->toDateString(),
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.sessions.update', $session), ['status' => 'confirmed']);

        $response->assertSessionHasErrors('status');
        $this->assertSame('pending', $session->fresh()->status);
    }

    public function test_completed_sessions_cannot_be_reopened(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $session = $this->pendingSession();
        $session->update(['status' => 'completed']);

        $response = $this->actingAs($admin)->patch(route('admin.sessions.update', $session), ['status' => 'confirmed']);

        $response->assertSessionHasErrors('status');
        $this->assertSame('completed', $session->fresh()->status);
    }
}
