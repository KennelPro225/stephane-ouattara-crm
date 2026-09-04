<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = ['title', 'subtitle', 'slot_label', 'image_path', 'programme_id', 'sort_order'];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
