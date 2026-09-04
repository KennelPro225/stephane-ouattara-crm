<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'category',
        'caption',
        'media_path',
        'media_type',
        'programme_id',
        'sort_order',
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
