<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    // Show form to create an offer
    public function create($requestId)
    {
        $serviceRequest = ServiceRequest::with('user')->findOrFail($requestId);
        
        // Check if request is still open
        if ($serviceRequest->status !== 'open') {
            return back()->with('error', 'This request is no longer available.');
        }
        
        // Check if provider already sent an offer
        $existingOffer = Offer::where('service_request_id', $requestId)
            ->where('provider_id', Auth::id())
            ->first();
        
        if ($existingOffer) {
            return back()->with('error', 'You already sent an offer for this request.');
        }
        
        return view('provider.offers.create', compact('serviceRequest'));
    }

    // Store the offer
    public function store(Request $request, $requestId)
    {
        $serviceRequest = ServiceRequest::findOrFail($requestId);
        
        // Validate
        $validated = $request->validate([
            'price' => 'required|numeric|min:1',
            'message' => 'required|string|min:10|max:500',
        ]);
        
        // Check if request is still open
        if ($serviceRequest->status !== 'open') {
            return back()->with('error', 'This request is no longer available.');
        }
        
        // Check for duplicate offer
        $existingOffer = Offer::where('service_request_id', $requestId)
            ->where('provider_id', Auth::id())
            ->first();
        
        if ($existingOffer) {
            return back()->with('error', 'You already sent an offer for this request.');
        }
        
        // Create offer
        $offer = Offer::create([
            'service_request_id' => $requestId,
            'provider_id' => Auth::id(),
            'price' => $validated['price'],
            'message' => $validated['message'],
            'status' => 'pending'
        ]);
        
        // Notify the customer
        $serviceRequest->user->notify(new \App\Notifications\OfferReceived($offer));
        
        return redirect()->route('provider.dashboard')
            ->with('success', 'Offer sent successfully!');
    }

    // Customer: View all offers on their requests
    public function index(Request $request)
    {
        $user = Auth::user();
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

    // Customer: Accept an offer
    public function accept($offerId)
    {
        $offer = Offer::with('serviceRequest')->findOrFail($offerId);
        
        // Check if the customer owns this request
        if ($offer->serviceRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if offer is still pending
        if ($offer->status !== 'pending') {
            return back()->with('error', 'This offer is no longer available.');
        }
        
        // Update offer status
        $offer->update(['status' => 'accepted']);
        
        // Update service request status
        $offer->serviceRequest->update([
            'status' => 'assigned',
            'assigned_provider_id' => $offer->provider_id
        ]);
        
        // Reject all other offers for this request and notify those providers
        $rejected = Offer::where('service_request_id', $offer->service_request_id)
            ->where('id', '!=', $offer->id)
            ->where('status', 'pending')
            ->with('provider')
            ->get();

        foreach ($rejected as $rej) {
            $rej->update(['status' => 'rejected']);
            $rej->provider->notify(new \App\Notifications\OfferStatusChanged($rej));
        }

        // Notify the accepted provider
        $offer->load('provider', 'serviceRequest');
        $offer->provider->notify(new \App\Notifications\OfferStatusChanged($offer));

        return back()->with('success', 'Offer accepted! Provider has been notified.');
    }

    // Customer: Reject an offer
    public function reject($offerId)
    {
        $offer = Offer::with('serviceRequest')->findOrFail($offerId);
        
        // Check if the customer owns this request
        if ($offer->serviceRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if offer is still pending
        if ($offer->status !== 'pending') {
            return back()->with('error', 'This offer is no longer available.');
        }
        
        $offer->update(['status' => 'rejected']);
        
        // Notify provider
        $offer->load('provider', 'serviceRequest');
        $offer->provider->notify(new \App\Notifications\OfferStatusChanged($offer));
        
        return back()->with('success', 'Offer rejected.');
    }
}