<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'budget',
        'location',
        'status',
        'scheduled_date',
        'assigned_provider_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function assignedProvider()
    {
        return $this->belongsTo(User::class, 'assigned_provider_id');
    }
}