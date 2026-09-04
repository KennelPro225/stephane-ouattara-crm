<?php

namespace App\Notifications;

use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewBooking extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Session $session) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $customer = $this->session->customer;

        return (new MailMessage)
            ->subject('Nouvelle réservation de session')
            ->greeting('Nouvelle réservation reçue !')
            ->line("**Client :** {$customer->full_name} ({$customer->email})")
            ->line("**Téléphone :** {$customer->phone}")
            ->line("**Type :** {$this->session->type}")
            ->when($this->session->programme, fn ($m) => $m->line('**Programme :** '.$this->session->programme->title))
            ->line('**Date souhaitée :** '.$this->session->preferred_date->format('d/m/Y'))
            ->action('Gérer la réservation', route('admin.dashboard'));
    }
}
