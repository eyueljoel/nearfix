<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * Display inbox with all conversations for the authenticated user.
     */
    public function inbox(Request $request): View
    {
        $conversations = Message::getInboxConversations(auth()->user(), 15);

        return view('messages.inbox', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * Display message thread for a specific service request conversation.
     * Marks all unread messages as read for the authenticated user.
     */
    public function show(ServiceRequest $serviceRequest): View
    {
        $user = auth()->user();

        // Check if user is a participant in this service request
        // Customers can always message, providers can message if assigned or if viewing available request
        $isCustomer = $serviceRequest->user_id === $user->id;
        $isAssignedProvider = $serviceRequest->assigned_provider_id === $user->id;
        $isProvider = $user->role === 'provider';
        
        $isParticipant = $isCustomer || $isAssignedProvider || ($isProvider && $serviceRequest->status === 'open');

        if (!$isParticipant) {
            abort(403, 'You do not have permission to view this conversation.');
        }

        // Get the other party in this conversation
        if ($isCustomer) {
            // Customer messaging assigned provider or any provider on open request
            $otherUserId = $serviceRequest->assigned_provider_id;
        } else {
            // Provider messaging customer
            $otherUserId = $serviceRequest->user_id;
        }

        if (!$otherUserId) {
            abort(404, 'No conversation partner found for this request.');
        }

        $otherUser = User::findOrFail($otherUserId);

        // Get conversation messages
        $messages = Message::getConversation($serviceRequest, $user, $otherUser, 20);

        // Mark all messages in this conversation as read by the current user
        foreach ($messages as $message) {
            if ($message->recipient_id === $user->id && !$message->isReadBy($user)) {
                $message->markAsReadBy($user);
            }
        }

        return view('messages.show', [
            'serviceRequest' => $serviceRequest,
            'otherUser' => $otherUser,
            'messages' => $messages,
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(\App\Http\Requests\StoreMessageRequest $request): RedirectResponse
    {
        $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);
        $user = auth()->user();

        // Create message
        $msg = Message::create([
            'service_request_id' => $serviceRequest->id,
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'body' => $request->body,
        ]);

        // Notify the recipient
        $recipient = \App\Models\User::find($request->recipient_id);
        if ($recipient) {
            $msg->load('sender', 'serviceRequest');
            $recipient->notify(new \App\Notifications\NewMessageReceived($msg));
        }

        return redirect()
            ->route('messages.show', $serviceRequest)
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Mark all messages in a conversation as read.
     */
    public function markAsRead(ServiceRequest $serviceRequest): RedirectResponse
    {
        $user = auth()->user();

        // Check authorization
        $isParticipant = $serviceRequest->user_id === $user->id ||
                        $serviceRequest->assigned_provider_id === $user->id;

        if (!$isParticipant) {
            abort(403, 'You do not have permission to mark these messages as read.');
        }

        // Get the other party
        $otherUserId = $serviceRequest->user_id === $user->id
            ? $serviceRequest->assigned_provider_id
            : $serviceRequest->user_id;

        $otherUser = User::findOrFail($otherUserId);

        // Get all messages in this conversation
        $messages = Message::where('service_request_id', $serviceRequest->id)
            ->where(function ($query) use ($user, $otherUser) {
                $query->where(function ($q) use ($user, $otherUser) {
                    $q->where('sender_id', $user->id)
                        ->where('recipient_id', $otherUser->id);
                })->orWhere(function ($q) use ($user, $otherUser) {
                    $q->where('sender_id', $otherUser->id)
                        ->where('recipient_id', $user->id);
                });
            })
            ->get();

        // Mark all messages as read
        foreach ($messages as $message) {
            if ($message->recipient_id === $user->id && !$message->isReadBy($user)) {
                $message->markAsReadBy($user);
            }
        }

        return redirect()
            ->route('messages.show', $serviceRequest)
            ->with('success', 'Messages marked as read.');
    }

    /**
     * Get unread message count for the authenticated user (API endpoint).
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $unreadCount = Message::unreadCountForUser(auth()->user());

        return response()->json([
            'unread_count' => $unreadCount,
        ]);
    }
}

