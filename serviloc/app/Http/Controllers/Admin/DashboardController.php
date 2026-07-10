<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Headline stats ─────────────────────────────────────────────
        $totalUsers      = User::count();
        $totalCustomers  = User::where('role', 'customer')->count();
        $totalProviders  = User::where('role', 'provider')->count();
        $totalRequests   = ServiceRequest::count();
        $totalOffers     = Offer::count();
        $totalCategories = Category::count();
        $totalPayments   = Payment::where('status', 'released')->sum('amount');

        // ── Chart 1: Registrations per day (last 30 days) ──────────────
        $registrations = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $regLabels = [];
        $regData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $regLabels[] = now()->subDays($i)->format('M d');
            $regData[]   = $registrations->get($d)?->count ?? 0;
        }

        // ── Chart 2: Requests by status (doughnut) ─────────────────────
        $requestsByStatus = ServiceRequest::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // ── Chart 3: Requests per day (last 30 days) ───────────────────
        $requestsPerDay = ServiceRequest::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $reqLabels = [];
        $reqData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $reqLabels[] = now()->subDays($i)->format('M d');
            $reqData[]   = $requestsPerDay->get($d)?->count ?? 0;
        }

        // ── Chart 4: Revenue per day (last 30 days) ────────────────────
        $revenuePerDay = Payment::select(
                DB::raw('DATE(released_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'released')
            ->where('released_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $revLabels = [];
        $revData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $revLabels[] = now()->subDays($i)->format('M d');
            $revData[]   = (float) ($revenuePerDay->get($d)?->total ?? 0);
        }

        // ── Recent requests ────────────────────────────────────────────
        $recentRequests = ServiceRequest::with(['user', 'category'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalCustomers', 'totalProviders',
            'totalRequests', 'totalOffers', 'totalCategories', 'totalPayments',
            'regLabels', 'regData',
            'requestsByStatus',
            'reqLabels', 'reqData',
            'revLabels', 'revData',
            'recentRequests'
        ));
    }

    public function requests(Request $request)
    {
        $query = ServiceRequest::with(['user', 'category', 'assignedProvider']);

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('category')) $query->where('category_id', $request->category);

        $query->orderBy('created_at', $request->get('sort', 'latest') === 'oldest' ? 'asc' : 'desc');

        $requests   = $query->paginate(15);
        $categories = Category::all();
        $statuses   = ['open', 'assigned', 'in_progress', 'completed', 'cancelled'];

        return view('admin.requests', compact('requests', 'categories', 'statuses'));
    }

    public function offers(Request $request)
    {
        $query = Offer::with(['provider', 'serviceRequest']);

        if ($request->filled('status')) $query->where('status', $request->status);

        $query->orderBy('created_at', $request->get('sort', 'latest') === 'oldest' ? 'asc' : 'desc');

        $offers   = $query->paginate(15);
        $statuses = ['pending', 'accepted', 'rejected'];

        return view('admin.offers', compact('offers', 'statuses'));
    }

    public function reviews(Request $request)
    {
        $query = Review::with(['reviewer', 'reviewee']);

        if ($request->filled('rating')) $query->where('rating', $request->rating);

        $query->orderBy('created_at', $request->get('sort', 'latest') === 'oldest' ? 'asc' : 'desc');

        $reviews = $query->paginate(15);
        $ratings = [5, 4, 3, 2, 1];

        return view('admin.reviews', compact('reviews', 'ratings'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }

        $query->orderBy('created_at', $request->get('sort', 'latest') === 'oldest' ? 'asc' : 'desc');

        $users = $query->paginate(20);
        $roles = ['customer', 'provider', 'admin'];

        return view('admin.users', compact('users', 'roles'));
    }

    /**
     * Update a user's role (admin only action).
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        // Prevent admin from changing their own role
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate(['role' => 'required|in:customer,provider,admin']);

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role updated to {$request->role} for {$user->name}.");
    }

    /**
     * Delete a user account.
     */
    public function destroyUser(User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "{$name} has been deleted.");
    }
}
