<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Programme;
use App\Notifications\ProgrammeReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendProgrammeReminder implements ShouldQueue
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
                        $customer->notify(new ProgrammeReminder($programme));

                        $customer->sessions()
                            ->where('programme_id', $programme->id)
                            ->update(['reminder_sent_at' => now()]);
                    });
            });
    }
}
