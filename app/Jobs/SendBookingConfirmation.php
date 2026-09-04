<?php

namespace App\Jobs;

use App\Models\Session;
use App\Models\User;
use App\Notifications\AdminNewBooking;
use App\Notifications\SessionConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendBookingConfirmation implements ShouldQueue
{
    use Queueable;

    public function __construct(public Session $session)
    {
        $this->session->loadMissing(['customer', 'programme']);
    }

    public function handle(): void
    {
        $this->session->customer->notify(new SessionConfirmation($this->session));
        User::query()->where('role', 'admin')->chunkById(50, function ($admins) {
            $admins->each(fn ($admin) => $admin->notify(new AdminNewBooking($this->session)));
        });

        if ($this->session->status === 'confirmed') {
            $this->session->update(['confirmed_at' => now()]);
        }
    }
}
