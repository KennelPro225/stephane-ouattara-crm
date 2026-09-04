<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'role', 'quote', 'programme_id', 'featured', 'sort_order'];

    protected function casts(): array
    {
        return ['featured' => 'boolean'];
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }
}
