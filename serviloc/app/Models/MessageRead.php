<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageRead extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'message_id',
        'user_id',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the message this read record belongs to.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Get the user who read the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark a message as read by a user. Prevents duplicate read records.
     */
    public static function markAsRead(Message $message, User $user): void
    {
        // Try to find existing read record
        $existingRead = self::where('message_id', $message->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$existingRead) {
            // Create new read record
            self::create([
                'message_id' => $message->id,
                'user_id' => $user->id,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Check if a message has been read by a user.
     */
    public static function isRead(Message $message, User $user): bool
    {
        return self::where('message_id', $message->id)
            ->where('user_id', $user->id)
            ->where('read_at', '!=', null)
            ->exists();
    }
}

