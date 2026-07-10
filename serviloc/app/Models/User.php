<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
        'bio',
        'is_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isProvider()
    {
        return $this->role === 'provider';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'provider_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function assignedRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'assigned_provider_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function unreadMessageCount(): int
    {
        return Message::unreadCountForUser($this);
    }

    public function portfolioItems()
    {
        return $this->hasMany(PortfolioItem::class, 'provider_id');
    }
}
