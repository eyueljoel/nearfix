<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Offer;
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
        
        $availableRequests = ServiceRequest::where('status', 'open')
            ->where('user_id', '!=', $user->id)
            ->with('category', 'user')
            ->latest()
            ->take(10)
            ->get();
        
        $recentOffers = $user->offers()
            ->with('serviceRequest')
            ->latest()
            ->take(5)
            ->get();
        
        return view('provider.dashboard', compact(
            'totalOffers',
            'pendingOffers',
            'acceptedOffers',
            'availableRequests',
            'recentOffers'
        ));
    }
}