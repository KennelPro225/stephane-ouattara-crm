<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    public const STATUSES = ['nouveau', 'contacte', 'converti'];

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'company', 'job_title',
        'status', 'source', 'notes',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Appends a timestamped entry instead of overwriting the notes log.
     */
    public function appendNote(string $note): void
    {
        $entry = '['.now()->format('d/m/Y H:i').'] '.trim($note);
        $this->notes = trim(implode("\n\n", array_filter([$this->notes, $entry])));
    }
}
