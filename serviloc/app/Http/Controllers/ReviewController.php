<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create($requestId)
    {
        $serviceRequest = ServiceRequest::with('assignedProvider')->findOrFail($requestId);
        
        // Check if the customer owns this request
        if ($serviceRequest->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to review this request.');
        }
        
        // Check if request is completed
        if ($serviceRequest->status !== 'completed') {
            return back()->with('error', 'This request is not completed yet.');
        }
        
        // Check if review already exists
        $existingReview = Review::where('service_request_id', $requestId)->first();
        if ($existingReview) {
            return back()->with('error', 'You already reviewed this provider.');
        }
        
        return view('customer.reviews.create', compact('serviceRequest'));
    }

    public function store(Request $request, $requestId)
    {
        $serviceRequest = ServiceRequest::findOrFail($requestId);
        
        // Check if the customer owns this request
        if ($serviceRequest->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to review this request.');
        }
        
        // Validate
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);
        
        // Create review
        Review::create([
            'service_request_id' => $requestId,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $serviceRequest->assigned_provider_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
        
        return redirect()->route('customer.dashboard')
            ->with('success', 'Thank you for your review!');
    }
}