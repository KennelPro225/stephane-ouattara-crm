<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Programme extends Model
{
    use HasFactory, SoftDeletes;

    public const AUDIENCES = ['adolescents', 'adultes', 'entreprises'];

    public const TYPES = ['individual', 'group', 'corporate'];

    public const STATUSES = ['draft', 'published'];

    /** How a programme occupies the coach's agenda. */
    public const SCHEDULE_MODES = ['none', 'weekly', 'weekly_full_day', 'full_period'];

    /** Modes that repeat on one weekday between start_date and end_date. */
    public const WEEKLY_MODES = ['weekly', 'weekly_full_day'];

    /** Modes that swallow a whole service day rather than a timed interval. */
    public const FULL_DAY_MODES = ['weekly_full_day', 'full_period'];

    protected $fillable = [
        'title', 'slug', 'audience', 'type', 'level', 'description',
        'price_amount', 'price_label', 'start_date', 'end_date', 'registration_deadline',
        'duration_label', 'ages_label', 'max_participants', 'image_path',
        'schedule_mode', 'session_weekday', 'session_start_time', 'session_duration_minutes',
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
            // Form input arrives as strings; keep the scheduling maths numeric.
            'session_weekday' => 'integer',
            'session_duration_minutes' => 'integer',
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

    /**
     * Programmes that occupy the agenda at some point within the given range.
     * Whether a programme actually applies to a specific date (weekday match for
     * the weekly modes, always for full_period) is decided by AvailabilityService.
     */
    public function scopeAffectingRange(Builder $query, Carbon $from, Carbon $to): Builder
    {
        return $query->where('schedule_mode', '!=', 'none')
            ->whereDate('start_date', '<=', $to)
            ->whereDate('end_date', '>=', $from);
    }

    /** Does this programme occupy the agenda on the given date? */
    public function occursOn(Carbon $date): bool
    {
        if ($this->schedule_mode === 'none') {
            return false;
        }

        if ($date->lt($this->start_date) || $date->gt($this->end_date)) {
            return false;
        }

        return $this->schedule_mode === 'full_period'
            || (int) $this->session_weekday === $date->dayOfWeek;
    }

    /** Does this programme swallow the whole service day when it occurs? */
    public function blocksFullDay(): bool
    {
        return in_array($this->schedule_mode, self::FULL_DAY_MODES, true);
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
