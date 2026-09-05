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
