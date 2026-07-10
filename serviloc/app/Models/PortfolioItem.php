<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'title',
        'description',
        'category',
        'location',
        'price_from',
        'image_path',
        'duration_days',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price_from'  => 'decimal:2',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function imageUrl(): string
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : '';
    }
}
