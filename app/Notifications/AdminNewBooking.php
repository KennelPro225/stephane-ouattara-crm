<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewBooking extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $customer = $this->booking->customer;

        return (new MailMessage)
            ->subject('Nouvelle demande de réservation')
            ->greeting('Nouvelle demande reçue !')
            ->line("Client : {$customer->full_name} ({$customer->email})")
            ->line("Téléphone : {$customer->phone}")
            ->when($this->booking->programme, fn ($m) => $m->line('Programme : '.$this->booking->programme->title))
            ->line('Date souhaitée : '.$this->booking->preferred_date->format('d/m/Y'))
            ->action('Gérer la réservation', route('admin.dashboard'));
    }
}
