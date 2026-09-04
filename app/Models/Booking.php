<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'completed', 'cancelled'];

    public const SERVICE_TYPES = ['individual', 'club_des_champions', 'group', 'corporate_wellness', 'teambuilding', 'other'];

    public const TIME_SLOTS = ['09:00', '10:30', '14:00', '15:30', '17:00'];

    protected $fillable = [
        'customer_id', 'programme_id', 'service_type', 'preferred_date',
        'preferred_time', 'message', 'status', 'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'confirmed_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
