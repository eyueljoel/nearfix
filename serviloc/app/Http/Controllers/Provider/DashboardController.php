<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $totalOffers = $user->offers()->count();
        $pendingOffers = $user->offers()->where('status', 'pending')->count();
        $acceptedOffers = $user->offers()->where('status', 'accepted')->count();
        
        // This month earnings (paid status — money held but not released yet)
        $thisMonthEarnings = \App\Models\Payment::forProvider($user->id)
            ->where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');
        
        // Total earnings (released payments)
        $totalEarnings = \App\Models\Payment::forProvider($user->id)
            ->where('status', 'released')
            ->sum('amount');
        
        // Average rating
        $avgRating = Review::where('reviewee_id', $user->id)->avg('rating') ?? 0;
        $totalReviews = Review::where('reviewee_id', $user->id)->count();
        
        // Available requests near you (open)
        $availableRequests = ServiceRequest::where('status', 'open')
            ->where('user_id', '!=', $user->id)
            ->with('category', 'user')
            ->latest()
            ->take(5)
            ->get();
        
        // Active jobs (accepted offers where payment is received but not completed yet)
        $activeJobs = $user->offers()
            ->where('status', 'accepted')
            ->with(['serviceRequest.user', 'serviceRequest.payment'])
            ->latest()
            ->take(3)
            ->get();
        
        // Recent messages (last 3)
        $recentMessages = \App\Models\Message::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('recipient_id', $user->id);
            })
            ->with(['sender', 'recipient', 'serviceRequest'])
            ->latest()
            ->take(3)
            ->get();
        
        return view('provider.dashboard', compact(
            'totalOffers',
            'pendingOffers',
            'acceptedOffers',
            'thisMonthEarnings',
            'totalEarnings',
            'avgRating',
            'totalReviews',
            'availableRequests',
            'activeJobs',
            'recentMessages'
        ));
    }

    /**
     * Show provider's offers with filters
     */
    public function offers(Request $request)
    {
        $user = Auth::user();
        $query = $user->offers()->with('serviceRequest');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $offers = $query->paginate(15);
        $statuses = ['pending', 'accepted', 'rejected', 'completed'];
        
        return view('provider.offers', compact('offers', 'statuses'));
    }

    /**
     * Show available requests to bid on
     */
    public function requests(Request $request)
    {
        $user = Auth::user();
        $query = ServiceRequest::where('status', 'open')
            ->where('user_id', '!=', $user->id)
            ->with(['category', 'user']);
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by budget range
        if ($request->filled('min_budget')) {
            $query->where('budget', '>=', $request->min_budget);
        }
        if ($request->filled('max_budget')) {
            $query->where('budget', '<=', $request->max_budget);
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $requests = $query->paginate(15);
        $categories = Category::all();
        
        return view('provider.requests', compact('requests', 'categories'));
    }

    /**
     * Show provider's reviews
     */
    public function reviews(Request $request)
    {
        $user = Auth::user();
        $query = Review::where('reviewee_id', $user->id)->with(['reviewer', 'reviewee']);
        
        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $reviews = $query->paginate(15);
        $avgRating = Review::where('reviewee_id', $user->id)->avg('rating') ?? 0;
        $ratings = [5, 4, 3, 2, 1];
        
        return view('provider.reviews', compact('reviews', 'avgRating', 'ratings'));
    }
}