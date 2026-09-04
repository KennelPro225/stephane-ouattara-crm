<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'customer_id',
        'programme_id',
        'author_name',
        'author_title',
        'author_company',
        'rating',
        'message',
        'image_path',
        'featured',
        'approved',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'approved' => 'boolean',
            'rating' => 'integer',
        ];
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
