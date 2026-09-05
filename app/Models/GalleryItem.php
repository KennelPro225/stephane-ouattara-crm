<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = ['title', 'subtitle', 'slot_label', 'image_path', 'programme_id', 'sort_order'];

    protected $appends = ['image_url'];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}
