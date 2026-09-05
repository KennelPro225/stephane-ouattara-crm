<?php

namespace App\Services;

use App\Models\AvailabilityRule;
use App\Models\Booking;
use App\Models\Programme;
use Illuminate\Support\Carbon;

class AvailabilityService
{
    public function slotMinutes(): int
    {
        return (int) setting('booking_slot_minutes', 60);
    }

    /**
     * All bookable "HH:MM" slots for a given date, after removing whatever
     * is already occupied by existing bookings and recurring group
     * programmes. Empty array if the coach is closed that day.
     */
    public function slotsForDate(Carbon $date): array
    {
        $rule = AvailabilityRule::where('weekday', $date->dayOfWeek)->first();

        if (! $rule || ! $rule->is_open || ! $rule->start_time || ! $rule->end_time) {
            return [];
        }

        $slotMinutes = $this->slotMinutes();
        $busy = $this->busyIntervals($date);

        $dayStart = Carbon::parse($date->toDateString().' '.$rule->start_time);
        $dayEnd = Carbon::parse($date->toDateString().' '.$rule->end_time);

        $slots = [];
        $cursor = $dayStart->copy();
        while ($cursor->copy()->addMinutes($slotMinutes)->lte($dayEnd)) {
            $slotEnd = $cursor->copy()->addMinutes($slotMinutes);

            $overlaps = collect($busy)->contains(
                fn ($interval) => $cursor->lt($interval['end']) && $slotEnd->gt($interval['start'])
            );

            if (! $overlaps) {
                $slots[] = $cursor->format('H:i');
            }

            $cursor->addMinutes($slotMinutes);
        }

        return $slots;
    }

    public function isAvailable(Carbon $date, string $time): bool
    {
        return in_array($time, $this->slotsForDate($date), true);
    }

    /**
     * @return array<int, array{start: Carbon, end: Carbon}>
     */
    private function busyIntervals(Carbon $date): array
    {
        $slotMinutes = $this->slotMinutes();
        $intervals = [];

        Booking::whereDate('preferred_date', $date)
            ->whereNotNull('preferred_time')
            ->where('status', '!=', 'cancelled')
            ->get(['preferred_time'])
            ->each(function (Booking $booking) use (&$intervals, $date, $slotMinutes) {
                $start = Carbon::parse($date->toDateString().' '.$booking->preferred_time);
                $intervals[] = ['start' => $start, 'end' => $start->copy()->addMinutes($slotMinutes)];
            });

        Programme::occurringOn($date)
            ->get(['session_start_time', 'session_duration_minutes'])
            ->each(function (Programme $programme) use (&$intervals, $date) {
                $start = Carbon::parse($date->toDateString().' '.$programme->session_start_time->format('H:i'));
                $intervals[] = ['start' => $start, 'end' => $start->copy()->addMinutes($programme->session_duration_minutes ?? 60)];
            });

        return $intervals;
    }
}
