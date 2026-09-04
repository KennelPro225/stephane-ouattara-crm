<?php

namespace App\Notifications;

use App\Models\Programme;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProgrammeReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Programme $programme) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Rappel : votre programme \"{$this->programme->title}\" commence dans 1 semaine")
            ->greeting("Bonjour {$notifiable->first_name},")
            ->line("Le programme **{$this->programme->title}** démarre le ".$this->programme->start_date->format('d/m/Y').'.')
            ->line("**Lieu :** {$this->programme->location}")
            ->when($this->programme->session_time, fn ($m) => $m->line('**Heure :** '.$this->programme->session_time))
            ->line('Préparez-vous à vivre une expérience transformatrice !')
            ->salutation('Stéphane Ouattara & l\'équipe');
    }
}
