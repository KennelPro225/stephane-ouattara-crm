<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Programme;
use App\Notifications\ProgrammeReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendProgrammeReminderJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $targetDate = now()->addWeek()->toDateString();

        Programme::published()
            ->whereDate('start_date', $targetDate)
            ->with('sessions.customer')
            ->get()
            ->each(function (Programme $programme) {
                $programme->sessions
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->pluck('customer')
                    ->filter()
                    ->unique('id')
                    ->each(function (Customer $customer) use ($programme) {
                        if (! $customer->hasBeenNotifiedAbout($programme)) {
                            $customer->notify(new ProgrammeReminder($programme));
                        }
                    });
            });
    }
}
