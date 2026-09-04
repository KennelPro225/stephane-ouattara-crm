<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use App\Notifications\AdminNewContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_submitting_the_contact_form_notifies_admins(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post('/contact', [
            'first_name' => 'Awa', 'last_name' => 'Koné', 'email' => 'awa.kone@example.ci',
            'phone' => '+225 07 00 00 00', 'message' => 'Je souhaite un rendez-vous.',
        ])->assertRedirect(route('contact'));

        Notification::assertSentTo($admin, AdminNewContact::class);
    }

    public function test_repeat_contact_appends_notes_instead_of_overwriting_them(): void
    {
        $this->post('/contact', [
            'first_name' => 'Awa', 'last_name' => 'Koné', 'email' => 'awa.kone@example.ci',
            'phone' => '+225 07 00 00 00', 'message' => 'Premier message.',
        ]);
        $this->post('/contact', [
            'first_name' => 'Awa', 'last_name' => 'Koné', 'email' => 'awa.kone@example.ci',
            'phone' => '+225 07 00 00 00', 'message' => 'Second message.',
        ]);

        $customer = Customer::where('email', 'awa.kone@example.ci')->firstOrFail();
        $this->assertStringContainsString('Premier message.', $customer->notes);
        $this->assertStringContainsString('Second message.', $customer->notes);
        $this->assertSame(1, Customer::where('email', 'awa.kone@example.ci')->count());
    }
}
