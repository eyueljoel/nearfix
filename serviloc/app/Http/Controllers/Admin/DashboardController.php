<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Offer;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProviders = User::where('role', 'provider')->count();
        $totalRequests = ServiceRequest::count();
        $totalOffers = Offer::count();
        $totalCategories = Category::count();
        
        $recentRequests = ServiceRequest::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCustomers',
            'totalProviders',
            'totalRequests',
            'totalOffers',
            'totalCategories',
            'recentRequests'
        ));
    }
}