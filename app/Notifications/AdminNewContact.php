<?php

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewContact extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Customer $customer) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau message de contact')
            ->greeting('Nouveau message reçu via le formulaire de contact !')
            ->line("**Client :** {$this->customer->full_name} ({$this->customer->email})")
            ->line("**Téléphone :** {$this->customer->phone}")
            ->action('Voir la fiche client', route('admin.customers.show', $this->customer));
    }
}
