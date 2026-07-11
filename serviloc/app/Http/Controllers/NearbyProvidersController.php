<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;

class NearbyProvidersController extends Controller
{
    /**
     * Show providers near the request's location.
     * Only accessible by the customer who owns the request.
     */
    public function show(Request $request, ServiceRequest $serviceRequest)
    {
        // Ownership check
        abort_if($serviceRequest->user_id !== auth()->id(), 403);

        $location = $serviceRequest->location;

        // Extract the first meaningful keyword from location
        // e.g. "Bole, Addis Ababa" → search for "Bole" and "Addis Ababa"
        $keywords = array_filter(
            array_map('trim', preg_split('/[,\s]+/', $location)),
            fn($k) => strlen($k) >= 3
        );

        // Find providers whose address matches any keyword
        $query = User::where('role', 'provider')
            ->where('id', '!=', auth()->id())
            ->where(function ($q) use ($keywords, $location) {
                // Full location match first
                $q->where('address', 'like', '%' . $location . '%');
                // Also match individual keywords
                foreach ($keywords as $keyword) {
                    $q->orWhere('address', 'like', '%' . $keyword . '%');
                }
            });

        // Filter by category match (same category providers preferred)
        $categoryName = $serviceRequest->category->name ?? null;

        $providers = $query->get()->map(function (User $provider) use ($categoryName) {
            $avgRating    = Review::where('reviewee_id', $provider->id)->avg('rating') ?? 0;
            $totalReviews = Review::where('reviewee_id', $provider->id)->count();
            $completedJobs = $provider->offers()->where('status', 'accepted')->count();
            $portfolioCount = $provider->portfolioItems()->count();

            // Score: rating (0-5) + review bonus + completed jobs bonus
            $score = ($avgRating * 2) + ($totalReviews * 0.5) + ($completedJobs * 0.3);

            return [
                'provider'       => $provider,
                'avg_rating'     => round($avgRating, 1),
                'total_reviews'  => $totalReviews,
                'completed_jobs' => $completedJobs,
                'portfolio_count'=> $portfolioCount,
                'score'          => $score,
            ];
        })->sortByDesc('score')->values();

        return view('customer.nearby-providers', compact('serviceRequest', 'providers', 'location'));
    }

    /**
     * Send a direct message to a provider about a specific request.
     * Creates the conversation and fires NewMessageReceived notification.
     */
    public function contact(Request $request, ServiceRequest $serviceRequest)
    {
        abort_if($serviceRequest->user_id !== auth()->id(), 403);

        $request->validate([
            'provider_id' => 'required|exists:users,id',
            'body'        => 'required|string|min:5|max:1000',
        ]);

        $provider = User::findOrFail($request->provider_id);
        abort_if($provider->role !== 'provider', 422);

        // Create the message
        $message = \App\Models\Message::create([
            'service_request_id' => $serviceRequest->id,
            'sender_id'          => auth()->id(),
            'recipient_id'       => $provider->id,
            'body'               => $request->body,
        ]);

        // Notify the provider
        $message->load('sender', 'serviceRequest');
        $provider->notify(new \App\Notifications\NewMessageReceived($message));

        return redirect()
            ->route('messages.show', $serviceRequest)
            ->with('success', "Message sent to {$provider->name}! They have been notified.");
    }
}
