<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityRule extends Model
{
    /** 0 = dimanche .. 6 = samedi, dans l'ordre d'affichage habituel (lundi -> dimanche). */
    public const WEEKDAYS = [1, 2, 3, 4, 5, 6, 0];

    public const WEEKDAY_LABELS = [
        0 => 'Dimanche', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi',
        4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi',
    ];

    protected $fillable = ['weekday', 'is_open', 'start_time', 'end_time'];

    protected function casts(): array
    {
        return ['is_open' => 'boolean'];
    }

    /**
     * Ensures all 7 weekday rows exist, defaulting to Mon-Sat 09:00-18:00
     * open and Sunday closed. Idempotent — safe to call on every admin page load.
     */
    public static function ensureDefaults(): void
    {
        foreach (self::WEEKDAYS as $weekday) {
            self::firstOrCreate(['weekday' => $weekday], [
                'is_open' => $weekday !== 0,
                'start_time' => '09:00',
                'end_time' => '18:00',
            ]);
        }
    }
}
