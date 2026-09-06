<?php

namespace App\Services;

use App\Models\AvailabilityRule;
use App\Models\Booking;
use App\Models\Programme;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    public function slotMinutes(): int
    {
        return (int) setting('booking_slot_minutes', 60);
    }

    /**
     * All bookable "HH:MM" slots for a given date, after removing whatever
     * is already occupied by existing bookings and scheduled programmes.
     * Empty array if the coach is closed or the day is fully blocked.
     *
     * @return array<int, string>
     */
    public function slotsForDate(Carbon $date): array
    {
        return $this->availabilityForRange($date, $date)[$date->toDateString()] ?? [];
    }

    public function isAvailable(Carbon $date, string $time): bool
    {
        return in_array($time, $this->slotsForDate($date), true);
    }

    /**
     * Free slots for every date in the range, keyed by "Y-m-d".
     *
     * Loads rules, bookings and programmes once and computes each day in PHP —
     * a month of dates costs three queries rather than two per day.
     *
     * @return array<string, array<int, string>>
     */
    public function availabilityForRange(Carbon $from, Carbon $to): array
    {
        $from = $from->copy()->startOfDay();
        $to = $to->copy()->startOfDay();

        $slotMinutes = $this->slotMinutes();
        $rules = $this->rules();
        $programmes = Programme::affectingRange($from, $to)->get();

        $bookingsByDate = Booking::whereDate('preferred_date', '>=', $from)
            ->whereDate('preferred_date', '<=', $to)
            ->whereNotNull('preferred_time')
            ->where('status', '!=', 'cancelled')
            ->get(['preferred_date', 'preferred_time'])
            ->groupBy(fn (Booking $booking) => $booking->preferred_date->toDateString());

        $availability = [];

        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            $key = $date->toDateString();
            $availability[$key] = $this->slotsForDay(
                $date,
                $rules->get($date->dayOfWeek),
                $programmes,
                $bookingsByDate->get($key, collect()),
                $slotMinutes,
            );
        }

        return $availability;
    }

    /**
     * Dates of the given "Y-m" month that can still be booked: at least one
     * free slot, and not in the past. Feeds the public booking calendar.
     *
     * @return array<int, string>
     */
    public function availableDatesInMonth(string $month): array
    {
        $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfDay();
        $end = $start->copy()->endOfMonth()->startOfDay();
        $today = Carbon::today();

        if ($end->lt($today)) {
            return [];
        }

        $availability = $this->availabilityForRange($start->max($today), $end);

        return array_keys(array_filter($availability, fn (array $slots) => $slots !== []));
    }

    /**
     * Non-cancelled bookings that now fall inside the programme's own sessions.
     * Surfaced to the admin as a warning — scheduling is never blocked by them.
     */
    public function conflictingBookings(Programme $programme): Collection
    {
        if ($programme->schedule_mode === 'none') {
            return collect();
        }

        $slotMinutes = $this->slotMinutes();

        return Booking::with('customer')
            ->whereDate('preferred_date', '>=', $programme->start_date)
            ->whereDate('preferred_date', '<=', $programme->end_date)
            ->whereNotNull('preferred_time')
            ->where('status', '!=', 'cancelled')
            ->get()
            ->filter(function (Booking $booking) use ($programme, $slotMinutes) {
                $date = $booking->preferred_date->copy()->startOfDay();

                if (! $programme->occursOn($date)) {
                    return false;
                }

                if ($programme->blocksFullDay()) {
                    return true;
                }

                $session = $this->programmeInterval($programme, $date);
                $start = $this->at($date, $booking->preferred_time);

                return $start->lt($session['end']) && $start->copy()->addMinutes($slotMinutes)->gt($session['start']);
            })
            ->values();
    }

    /**
     * The weekly rules, keyed by weekday. An untouched install has no rows yet —
     * seed the defaults rather than reporting the coach as closed all week.
     *
     * @return Collection<int, AvailabilityRule>
     */
    private function rules(): Collection
    {
        $rules = AvailabilityRule::all();

        if ($rules->isEmpty()) {
            AvailabilityRule::ensureDefaults();
            $rules = AvailabilityRule::all();
        }

        return $rules->keyBy('weekday');
    }

    /**
     * The first already-scheduled programme whose sessions would collide with
     * the candidate's. Returns null when the candidate can be scheduled freely.
     */
    public function findSchedulingConflict(Programme $candidate, ?int $ignoreId = null): ?Programme
    {
        if ($candidate->schedule_mode === 'none') {
            return null;
        }

        return Programme::affectingRange($candidate->start_date, $candidate->end_date)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->get()
            ->first(fn (Programme $other) => $this->schedulesCollide($candidate, $other));
    }

    private function schedulesCollide(Programme $a, Programme $b): bool
    {
        $from = $a->start_date->max($b->start_date);
        $to = $a->end_date->min($b->end_date);

        if ($from->gt($to)) {
            return false;
        }

        $shared = $this->firstSharedDate($a, $b, $from, $to);

        if (! $shared) {
            return false;
        }

        if ($a->blocksFullDay() || $b->blocksFullDay()) {
            return true;
        }

        $first = $this->programmeInterval($a, $shared);
        $second = $this->programmeInterval($b, $shared);

        return $first['start']->lt($second['end']) && $first['end']->gt($second['start']);
    }

    /**
     * Any weekly pattern repeats within a week and full_period occurs daily, so
     * if two programmes ever share a date they share one in the first 7 days.
     */
    private function firstSharedDate(Programme $a, Programme $b, Carbon $from, Carbon $to): ?Carbon
    {
        $limit = $from->copy()->addDays(6)->min($to);

        for ($date = $from->copy(); $date->lte($limit); $date->addDay()) {
            if ($a->occursOn($date) && $b->occursOn($date)) {
                return $date->copy();
            }
        }

        return null;
    }

    /**
     * @param  Collection<int, Programme>  $programmes
     * @param  Collection<int, Booking>  $bookings
     * @return array<int, string>
     */
    private function slotsForDay(Carbon $date, ?AvailabilityRule $rule, Collection $programmes, Collection $bookings, int $slotMinutes): array
    {
        if (! $rule || ! $rule->is_open || ! $rule->start_time || ! $rule->end_time) {
            return [];
        }

        $occurring = $programmes->filter(fn (Programme $programme) => $programme->occursOn($date));

        // A full-day activity swallows the date entirely — no slot survives.
        if ($occurring->contains(fn (Programme $programme) => $programme->blocksFullDay())) {
            return [];
        }

        $busy = $occurring->map(fn (Programme $programme) => $this->programmeInterval($programme, $date))->all();

        foreach ($bookings as $booking) {
            $start = $this->at($date, $booking->preferred_time);
            $busy[] = ['start' => $start, 'end' => $start->copy()->addMinutes($slotMinutes)];
        }

        $dayStart = $this->at($date, $rule->start_time);
        $dayEnd = $this->at($date, $rule->end_time);

        $slots = [];
        $cursor = $dayStart->copy();

        while ($cursor->copy()->addMinutes($slotMinutes)->lte($dayEnd)) {
            $slotEnd = $cursor->copy()->addMinutes($slotMinutes);

            $overlaps = false;
            foreach ($busy as $interval) {
                if ($cursor->lt($interval['end']) && $slotEnd->gt($interval['start'])) {
                    $overlaps = true;
                    break;
                }
            }

            if (! $overlaps) {
                $slots[] = $cursor->format('H:i');
            }

            $cursor->addMinutes($slotMinutes);
        }

        return $slots;
    }

    /**
     * @return array{start: Carbon, end: Carbon}
     */
    private function programmeInterval(Programme $programme, Carbon $date): array
    {
        $start = $this->at($date, $programme->session_start_time?->format('H:i') ?? '00:00');

        return ['start' => $start, 'end' => $start->copy()->addMinutes((int) ($programme->session_duration_minutes ?: 60))];
    }

    /** Combines a date with an "HH:MM" (or "HH:MM:SS") time into a Carbon instance. */
    private function at(Carbon $date, string $time): Carbon
    {
        return Carbon::parse($date->toDateString().' '.substr($time, 0, 5));
    }
}
