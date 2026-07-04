<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $location = $request->get('location');
        $minBudget = $request->get('min_budget');
        $maxBudget = $request->get('max_budget');
        
        $serviceRequests = ServiceRequest::with(['category', 'user'])
            ->where('status', 'open');
        
        if ($query) {
            $serviceRequests->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }
        
        if ($category) {
            $serviceRequests->where('category_id', $category);
        }
        
        if ($location) {
            $serviceRequests->where('location', 'LIKE', "%{$location}%");
        }
        
        if ($minBudget) {
            $serviceRequests->where('budget', '>=', $minBudget);
        }
        
        if ($maxBudget) {
            $serviceRequests->where('budget', '<=', $maxBudget);
        }
        
        $serviceRequests = $serviceRequests->latest()->get();
        $categories = Category::all();
        
        return view('search.results', compact('serviceRequests', 'categories', 'query', 'category', 'location', 'minBudget', 'maxBudget'));
    }
}