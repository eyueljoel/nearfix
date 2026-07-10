<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    // ── Provider: list & manage their own portfolio ────────────────

    public function index()
    {
        $items = PortfolioItem::where('provider_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('portfolio.index', compact('items'));
    }

    public function create()
    {
        return view('portfolio.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'category'      => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:255',
            'price_from'    => 'nullable|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'is_featured'   => 'boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('portfolio', 'public');
        }

        PortfolioItem::create([
            'provider_id'   => Auth::id(),
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? null,
            'category'      => $validated['category'] ?? null,
            'location'      => $validated['location'] ?? null,
            'price_from'    => $validated['price_from'] ?? null,
            'duration_days' => $validated['duration_days'] ?? null,
            'is_featured'   => $request->boolean('is_featured'),
            'image_path'    => $imagePath,
        ]);

        return redirect()->route('portfolio.index')
            ->with('success', 'Portfolio item added successfully!');
    }

    public function edit(PortfolioItem $portfolioItem)
    {
        abort_if($portfolioItem->provider_id !== Auth::id(), 403);
        return view('portfolio.edit', ['item' => $portfolioItem]);
    }

    public function update(Request $request, PortfolioItem $portfolioItem)
    {
        abort_if($portfolioItem->provider_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'category'      => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:255',
            'price_from'    => 'nullable|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'is_featured'   => 'boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = $portfolioItem->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('portfolio', 'public');
        }

        $portfolioItem->update([
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? null,
            'category'      => $validated['category'] ?? null,
            'location'      => $validated['location'] ?? null,
            'price_from'    => $validated['price_from'] ?? null,
            'duration_days' => $validated['duration_days'] ?? null,
            'is_featured'   => $request->boolean('is_featured'),
            'image_path'    => $imagePath,
        ]);

        return redirect()->route('portfolio.index')
            ->with('success', 'Portfolio item updated.');
    }

    public function destroy(PortfolioItem $portfolioItem)
    {
        abort_if($portfolioItem->provider_id !== Auth::id(), 403);

        if ($portfolioItem->image_path) {
            Storage::disk('public')->delete($portfolioItem->image_path);
        }

        $portfolioItem->delete();

        return back()->with('success', 'Portfolio item deleted.');
    }

    // ── Public: anyone can view a provider's portfolio ─────────────

    public function show(User $provider)
    {
        abort_if($provider->role !== 'provider', 404);

        $items     = PortfolioItem::where('provider_id', $provider->id)
            ->orderByDesc('is_featured')
            ->latest()
            ->get();

        $reviews   = Review::where('reviewee_id', $provider->id)
            ->with('reviewer', 'serviceRequest')
            ->latest()
            ->take(6)
            ->get();

        $avgRating    = Review::where('reviewee_id', $provider->id)->avg('rating') ?? 0;
        $totalReviews = Review::where('reviewee_id', $provider->id)->count();
        $completedJobs = $provider->offers()->where('status', 'accepted')->count();

        return view('portfolio.show', compact(
            'provider', 'items', 'reviews',
            'avgRating', 'totalReviews', 'completedJobs'
        ));
    }
}
