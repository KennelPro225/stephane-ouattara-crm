<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre demande de session a bien été reçue')
            ->greeting("Merci {$this->booking->customer->first_name} !")
            ->line('Votre demande de session a bien été enregistrée.')
            ->when($this->booking->programme, fn ($m) => $m->line('Programme : '.$this->booking->programme->title))
            ->line('Date souhaitée : '.$this->booking->preferred_date->format('d/m/Y').($this->booking->preferred_time ? ' à '.$this->booking->preferred_time : ''))
            ->line('Je reviens vers vous sous 24 h ouvrées pour confirmer.');
    }
}
