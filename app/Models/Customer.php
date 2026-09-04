<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use Notifiable, SoftDeletes;

    public const SOURCES = ['website', 'referral', 'direct', 'social_media'];

    public const STATUSES = ['lead', 'prospect', 'active', 'inactive'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'role',
        'city',
        'age',
        'source',
        'status',
        'notes',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function programmes()
    {
        return $this->hasManyThrough(Programme::class, Session::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Appends a timestamped entry to the notes log instead of overwriting it —
     * notes used to be silently replaced on every repeat contact/booking.
     */
    public function appendNote(string $note): void
    {
        $entry = '['.now()->format('d/m/Y H:i').'] '.trim($note);
        $this->notes = trim(implode("\n\n", array_filter([$this->notes, $entry])));
    }
}
