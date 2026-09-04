<?php

namespace App\Notifications;

use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Session $session) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $programme = $this->session->programme?->title ?? 'Session de coaching personnalisée';

        return (new MailMessage)
            ->subject('Confirmation de votre réservation - Stéphane Ouattara')
            ->greeting("Bonjour {$notifiable->first_name},")
            ->line('Merci pour votre confiance ! Nous avons bien reçu votre demande de session.')
            ->line("**Programme :** {$programme}")
            ->line("**Type :** {$this->session->type}")
            ->line('**Date souhaitée :** '.$this->session->preferred_date->format('d/m/Y'))
            ->when($this->session->preferred_time, fn ($mail) => $mail->line('**Heure :** '.$this->session->preferred_time))
            ->line('Votre demande sera confirmée sous 24 à 48 heures par notre équipe.')
            ->line('À très bientôt !')
            ->salutation('Stéphane Ouattara & l\'équipe');
    }
}
