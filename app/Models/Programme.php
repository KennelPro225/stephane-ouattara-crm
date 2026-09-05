<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Programme extends Model
{
    use HasFactory, SoftDeletes;

    public const AUDIENCES = ['adolescents', 'adultes', 'entreprises'];

    public const TYPES = ['individual', 'group', 'corporate'];

    public const STATUSES = ['draft', 'published'];

    protected $fillable = [
        'title', 'slug', 'audience', 'type', 'level', 'description',
        'price_amount', 'price_label', 'start_date', 'end_date', 'registration_deadline',
        'duration_label', 'ages_label', 'max_participants', 'image_path',
        'session_weekday', 'session_start_time', 'session_duration_minutes',
        'featured', 'status', 'created_by',
    ];

    protected $appends = ['available_seats', 'image_url'];

    protected function casts(): array
    {
        return [
            'price_amount' => 'decimal:2',
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'registration_deadline' => 'date:Y-m-d',
            'session_start_time' => 'datetime:H:i',
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

    /** Programmes with a recurring weekly session that meets on the given date. */
    public function scopeOccurringOn(Builder $query, \Illuminate\Support\Carbon $date): Builder
    {
        return $query->whereNotNull('session_weekday')
            ->whereNotNull('session_start_time')
            ->where('session_weekday', $date->dayOfWeek)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function galleryItems()
    {
        return $this->hasMany(GalleryItem::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getConfirmedBookingsCountAttribute(): int
    {
        return $this->bookings()->whereIn('status', ['confirmed', 'completed'])->count();
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->max_participants - $this->confirmed_bookings_count);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}
