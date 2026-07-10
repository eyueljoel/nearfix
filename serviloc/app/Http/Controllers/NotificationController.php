<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Show all notifications for the authenticated user.
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Return unread notification count (JSON for polling).
     */
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a single notification as read and redirect to its action URL.
     */
    public function markRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $url = $notification->data['action_url'] ?? route('notifications.index');

        return redirect($url);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        $request->user()
            ->notifications()
            ->findOrFail($id)
            ->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
