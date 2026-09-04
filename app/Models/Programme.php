<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Programme extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['draft', 'published', 'archived'];

    public const TYPES = ['individual', 'group', 'corporate', 'adolescents'];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'age_min',
        'age_max',
        'type',
        'level',
        'price',
        'max_participants',
        'start_date',
        'end_date',
        'registration_deadline',
        'session_time',
        'duration_hours',
        'location',
        'image_path',
        'featured',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'registration_deadline' => 'date',
            'session_time' => 'datetime:H:i',
            'featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Programme $programme) {
            if (empty($programme->slug)) {
                $programme->slug = Str::slug($programme->title).'-'.Str::lower(Str::random(5));
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Session::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getConfirmedSessionsCountAttribute(): int
    {
        return $this->sessions()->whereIn('status', ['confirmed', 'completed'])->count();
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->max_participants - $this->confirmed_sessions_count);
    }
}
