<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Offer;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Stats
        $totalRequests = $user->serviceRequests()->count();
        $openRequests = $user->serviceRequests()->where('status', 'open')->count();
        $completedRequests = $user->serviceRequests()->where('status', 'completed')->count();
        
        // Get offers on user's requests
        $requestIds = $user->serviceRequests()->pluck('id');
        $totalOffers = Offer::whereIn('service_request_id', $requestIds)->count();
        $pendingOffers = Offer::whereIn('service_request_id', $requestIds)->where('status', 'pending')->count();
        
        // Recent requests
        $recentRequests = $user->serviceRequests()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();
        
        // Recent offers on user's requests
        $recentOffers = Offer::whereIn('service_request_id', $requestIds)
            ->with(['serviceRequest', 'provider'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('customer.dashboard', compact(
            'totalRequests',
            'openRequests',
            'completedRequests',
            'totalOffers',
            'pendingOffers',
            'recentRequests',
            'recentOffers'
        ));
    }

    public function requests()
    {
        $requests = auth()->user()->serviceRequests()->with('category')->latest()->get();
        return view('customer.requests.index', compact('requests'));
    }

    public function createRequest()
    {
        $categories = \App\Models\Category::all();
        return view('customer.requests.create', compact('categories'));
    }

    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
            'budget' => 'required|numeric|min:1',
            'location' => 'required|string|max:255',
            'scheduled_date' => 'nullable|date|after:today',
        ]);

        $request->user()->serviceRequests()->create($validated);

        return redirect()->route('customer.requests')
            ->with('success', 'Service request posted successfully!');
    }

    public function showRequest($id)
    {
        $request = auth()->user()->serviceRequests()->with(['category', 'offers.provider'])->findOrFail($id);
        return view('customer.requests.show', compact('request'));
    }

    public function offers(Request $request)
    {
        $user = auth()->user();
        $requestIds = $user->serviceRequests()->pluck('id');
        
        $query = Offer::whereIn('service_request_id', $requestIds)
            ->with(['serviceRequest', 'provider']);
        
        // Filter by status
        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }
        
        // Sort
        $sortBy = $request->get('sort', 'latest');
        if ($sortBy === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $offers = $query->paginate(15);
        
        return view('customer.offers.index', compact('offers'));
    }

    public function reviews(Request $request)
    {
        $user = auth()->user();
        
        // Get reviews given by this customer
        $query = Review::where('reviewer_id', $user->id)
            ->with(['serviceRequest', 'reviewee']);
        
        // Filter by rating
        if ($request->get('rating')) {
            $query->where('rating', $request->get('rating'));
        }
        
        // Sort
        $sortBy = $request->get('sort', 'latest');
        if ($sortBy === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $reviews = $query->paginate(15);
        
        return view('customer.reviews.index', compact('reviews'));
    }
}