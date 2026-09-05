<?php

namespace Tests\Feature\Admin;

use App\Models\AvailabilityRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_availability_screen(): void
    {
        $this->get('/admin/disponibilites')->assertRedirect(route('login'));
    }

    public function test_admin_can_view_and_update_availability(): void
    {
        AvailabilityRule::ensureDefaults();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin/disponibilites')->assertOk();

        $rules = AvailabilityRule::all();

        $payload = [
            'slot_minutes' => 45,
            'rules' => $rules->map(fn ($rule) => [
                'id' => $rule->id,
                'is_open' => $rule->weekday !== 0,
                'start_time' => $rule->weekday === 0 ? null : '08:00',
                'end_time' => $rule->weekday === 0 ? null : '16:00',
            ])->all(),
        ];

        $this->actingAs($admin)->put('/admin/disponibilites', $payload)->assertSessionHasNoErrors();

        $this->assertSame(45, (int) setting('booking_slot_minutes'));
        $monday = AvailabilityRule::where('weekday', 1)->first();
        $this->assertTrue($monday->is_open);
        $this->assertSame('08:00', $monday->start_time);
        $this->assertSame('16:00', $monday->end_time);

        $sunday = AvailabilityRule::where('weekday', 0)->first();
        $this->assertFalse($sunday->is_open);
    }
}
