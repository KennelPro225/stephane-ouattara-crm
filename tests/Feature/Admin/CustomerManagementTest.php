<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): Customer
    {
        return Customer::create([
            'first_name' => 'Awa', 'last_name' => 'Koné', 'email' => 'awa.kone@example.ci',
            'phone' => '+225 07 00 00 00', 'source' => 'Site web', 'status' => 'nouveau',
        ]);
    }

    public function test_guests_cannot_update_customers(): void
    {
        $customer = $this->customer();

        $this->patch(route('admin.customers.update', $customer), ['status' => 'converti'])
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_change_customer_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = $this->customer();

        $this->actingAs($admin)
            ->patch(route('admin.customers.update', $customer), ['status' => 'converti'])
            ->assertSessionHasNoErrors();

        $this->assertSame('converti', $customer->fresh()->status);
    }

    public function test_admin_can_append_a_note_without_losing_previous_notes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = $this->customer();
        $customer->appendNote('Note initiale');
        $customer->save();

        $this->actingAs($admin)
            ->post(route('admin.customers.notes.store', $customer), ['note' => 'Suivi effectué'])
            ->assertSessionHasNoErrors();

        $notes = $customer->fresh()->notes;
        $this->assertStringContainsString('Note initiale', $notes);
        $this->assertStringContainsString('Suivi effectué', $notes);
    }
}
