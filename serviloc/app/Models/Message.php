<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'service_request_id',
        'sender_id',
        'recipient_id',
        'body',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

    /**
     * Get the service request this message belongs to.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the user who sent this message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the user who received this message.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get all read records for this message.
     */
    public function reads(): HasMany
    {
        return $this->hasMany(MessageRead::class);
    }

    /**
     * Check if a message has been read by a specific user.
     */
    public function isReadBy(User $user): bool
    {
        return $this->reads()
            ->where('user_id', $user->id)
            ->where('read_at', '!=', null)
            ->exists();
    }

    /**
     * Mark a message as read by a specific user.
     */
    public function markAsReadBy(User $user): void
    {
        MessageRead::markAsRead($this, $user);
    }

    /**
     * Get total unread message count for a user.
     */
    public static function unreadCountForUser(User $user): int
    {
        return self::query()
            ->where('recipient_id', $user->id)
            ->leftJoin('message_reads', function ($join) use ($user) {
                $join->on('messages.id', '=', 'message_reads.message_id')
                    ->where('message_reads.user_id', '=', $user->id);
            })
            ->whereNull('message_reads.id')
            ->count();
    }

    /**
     * Get all messages for a conversation between two users about a service request.
     * 
     * @param ServiceRequest $serviceRequest
     * @param User $user1
     * @param User $user2
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getConversation(
        ServiceRequest $serviceRequest,
        User $user1,
        User $user2,
        int $perPage = 20
    ): \Illuminate\Pagination\LengthAwarePaginator {
        return self::query()
            ->where('service_request_id', $serviceRequest->id)
            ->where(function ($query) use ($user1, $user2) {
                $query->where(function ($q) use ($user1, $user2) {
                    $q->where('sender_id', $user1->id)
                        ->where('recipient_id', $user2->id);
                })->orWhere(function ($q) use ($user1, $user2) {
                    $q->where('sender_id', $user2->id)
                        ->where('recipient_id', $user1->id);
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get inbox conversations for a user (one entry per unique conversation).
     * Grouped by service request and other participant.
     * 
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getInboxConversations(User $user, int $perPage = 15)
    {
        // Get the latest message for each conversation
        $allMessages = self::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->with(['serviceRequest', 'sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by conversation
        $grouped = $allMessages->groupBy(function ($message) use ($user) {
            $otherUserId = $message->sender_id === $user->id 
                ? $message->recipient_id 
                : $message->sender_id;
            return $message->service_request_id . '_' . $otherUserId;
        })->map(function ($group) use ($user) {
            // Get the most recent message in this conversation
            $latestMessage = $group->sortByDesc('created_at')->first();
            
            // Count unread messages in this conversation for the user
            $unreadCount = $group->filter(function ($msg) use ($user) {
                return $msg->recipient_id === $user->id && !$msg->isReadBy($user);
            })->count();

            $otherUserId = $latestMessage->sender_id === $user->id 
                ? $latestMessage->recipient_id 
                : $latestMessage->sender_id;

            return (object) [
                'service_request_id' => $latestMessage->service_request_id,
                'service_request' => $latestMessage->serviceRequest,
                'other_user_id' => $otherUserId,
                'other_user' => $latestMessage->sender_id === $user->id 
                    ? $latestMessage->recipient 
                    : $latestMessage->sender,
                'last_message' => $latestMessage,
                'last_message_preview' => \Illuminate\Support\Str::limit($latestMessage->body, 50),
                'unread_count' => $unreadCount,
                'last_message_at' => $latestMessage->created_at,
            ];
        })
        ->sortByDesc('last_message_at')
        ->values();

        // Manually paginate the collection
        $page = request()->query('page', 1);
        $page = max(1, (int)$page);
        $items = $grouped->forPage($page, $perPage);
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items->values(),
            $grouped->count(),
            $perPage,
            $page,
            [
                'path' => url('messages'),
                'query' => request()->query(),
            ]
        );
    }
}

