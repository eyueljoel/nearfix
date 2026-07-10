<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'offer_id',
        'customer_id',
        'provider_id',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'payment_method',
        'notes',
        'paid_at',
        'released_at',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'released_at'  => 'datetime',
        'amount'       => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /**
     * Generate a unique transaction ID like TXN-20260708-ABCD1234
     */
    public static function generateTransactionId(): string
    {
        return 'TXN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isPaid(): bool     { return $this->status === 'paid'; }
    public function isReleased(): bool { return $this->status === 'released'; }
    public function isRefunded(): bool { return $this->status === 'refunded'; }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForCustomer($query, int $userId)
    {
        return $query->where('customer_id', $userId);
    }

    public function scopeForProvider($query, int $userId)
    {
        return $query->where('provider_id', $userId);
    }
}
