<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions';

    protected $fillable = [
        'customer_id',
        'programme_id',
        'user_id',
        'type',
        'preferred_date',
        'preferred_time',
        'message',
        'status',
        'confirmed_at',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'preferred_time' => 'datetime:H:i',
            'confirmed_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public const STATUSES = ['pending', 'confirmed', 'completed', 'cancelled'];

    public const TYPES = ['individual', 'club_des_champions', 'group', 'corporate_wellness', 'teambuilding', 'other'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
