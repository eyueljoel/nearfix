@extends('layouts.app')
@section('title', 'My Reviews')

@section('content')
<style>
    :root {
        --blue:       #0ea5e9;
        --blue-dark:  #2563eb;
        --blue-light: #eff6ff;
        --white:      #ffffff;
        --bg:         #f8fafc;
        --border:     #e2e8f0;
        --text:       #0f172a;
        --muted:      #64748b;
        --green:      #059669;
        --green-lt:   #ecfdf5;
        --yellow:     #d97706;
        --yellow-lt:  #fffbeb;
        --red:        #dc2626;
        --red-lt:     #fef2f2;
        --radius:     12px;
        --star-on:    #f59e0b;
        --star-off:   #e2e8f0;
    }

    /* ── Page header ─────────────────────────── */
    .pg-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .pg-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.3px;
    }

    .count-chip {
        background: var(--blue-light);
        color: var(--blue-dark);
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
    }

    /* ── Summary cards ───────────────────────── */
    .summary-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 22px;
        transition: box-shadow 0.2s;
    }

    .summary-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }

    .summary-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--muted);
        margin-bottom: 10px;
    }

    .summary-big {
        font-size: 36px;
        font-weight: 900;
        color: var(--text);
        letter-spacing: -1px;
        line-height: 1;
        margin-bottom: 6px;
    }

    .summary-stars {
        display: flex;
        gap: 2px;
        margin-bottom: 5px;
    }

    .star {
        font-size: 17px;
        line-height: 1;
    }

    .star-on  { color: var(--star-on); }
    .star-off { color: var(--star-off); }

    .summary-sub {
        font-size: 12px;
        color: var(--muted);
    }

    .rep-label {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -0.3px;
        line-height: 1;
        margin-bottom: 6px;
    }

    .rep-excellent { color: var(--green); }
    .rep-good      { color: var(--blue-dark); }
    .rep-average   { color: var(--yellow); }
    .rep-poor      { color: var(--red); }

    /* ── Filter bar ──────────────────────────── */
    .filter-bar {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-field {
        flex: 1;
        min-width: 160px;
    }

    .filter-field label {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .filter-field select {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        color: var(--text);
        background: var(--bg);
        font-family: inherit;
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s;
    }

    .filter-field select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(14,165,233,0.12);
        background: var(--white);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        padding-bottom: 1px;
        flex-shrink: 0;
    }

    .btn-filter {
        padding: 8px 18px;
        background: var(--blue);
        color: var(--white);
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.18s;
        white-space: nowrap;
    }

    .btn-filter:hover { background: var(--blue-dark); }

    .btn-clear {
        padding: 8px 14px;
        background: var(--bg);
        color: var(--muted);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s;
        white-space: nowrap;
    }

    .btn-clear:hover {
        border-color: var(--muted);
        color: var(--text);
        background: var(--white);
    }

    /* ── Review cards ────────────────────────── */
    .review-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 20px;
        margin-bottom: 12px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .review-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 3px 14px rgba(0,0,0,0.06);
    }

    /* reviewer header */
    .reviewer-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: var(--white);
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* vary avatar colours by index */
    .reviewer-avatar.c0 { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
    .reviewer-avatar.c1 { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .reviewer-avatar.c2 { background: linear-gradient(135deg, #059669, #0d9488); }
    .reviewer-avatar.c3 { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .reviewer-avatar.c4 { background: linear-gradient(135deg, #dc2626, #e11d48); }

    .reviewer-meta { flex: 1; min-width: 0; }

    .reviewer-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.2;
    }

    .reviewer-date {
        font-size: 12px;
        color: var(--muted);
        margin-top: 1px;
    }

    /* stars */
    .review-stars {
        display: flex;
        gap: 2px;
        margin-bottom: 10px;
    }

    .review-stars .star {
        font-size: 15px;
    }

    /* comment */
    .review-comment {
        font-size: 13.5px;
        color: #374151;
        font-style: italic;
        line-height: 1.6;
        background: var(--bg);
        border-radius: 8px;
        padding: 12px 14px;
        border-left: 3px solid var(--border);
        margin-bottom: 12px;
    }

    /* service chip */
    .service-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 500;
        color: var(--muted);
        background: var(--bg);
        border: 1px solid var(--border);
        padding: 3px 10px;
        border-radius: 20px;
    }

    .service-chip svg {
        width: 11px;
        height: 11px;
        flex-shrink: 0;
    }

    /* ── Empty state ─────────────────────────── */
    .empty-state {
        background: var(--white);
        border: 1.5px dashed var(--border);
        border-radius: var(--radius);
        padding: 60px 24px;
        text-align: center;
    }

    .empty-icon {
        width: 56px;
        height: 56px;
        background: var(--yellow-lt);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 28px;
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .empty-state p {
        font-size: 13.5px;
        color: var(--muted);
    }

    /* ── Pagination ──────────────────────────── */
    .pagination-wrap { margin-top: 24px; }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-row { grid-template-columns: 1fr; }
    }

    @media (max-width: 540px) {
        .reviewer-row { flex-wrap: wrap; }
    }
</style>

@php
    $repClass = 'rep-poor';
    $repText  = 'Needs Improvement';
    if ($avgRating >= 4.5)      { $repClass = 'rep-excellent'; $repText = 'Excellent'; }
    elseif ($avgRating >= 3.5)  { $repClass = 'rep-good';      $repText = 'Good'; }
    elseif ($avgRating >= 2.5)  { $repClass = 'rep-average';   $repText = 'Average'; }
@endphp

{{-- Page header --}}
<div class="pg-header">
    <h1 class="pg-title">My Reviews</h1>
    <span class="count-chip">{{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }}</span>
</div>

{{-- Summary cards --}}
<div class="summary-row">

    {{-- Average rating --}}
    <div class="summary-card">
        <div class="summary-label">Average Rating</div>
        <div class="summary-big">{{ number_format($avgRating, 1) }}</div>
        <div class="summary-stars">
            @for($i = 1; $i <= 5; $i++)
                <span class="star {{ $i <= round($avgRating) ? 'star-on' : 'star-off' }}">★</span>
            @endfor
        </div>
        <div class="summary-sub">out of 5.0</div>
    </div>

    {{-- Total reviews --}}
    <div class="summary-card">
        <div class="summary-label">Total Reviews</div>
        <div class="summary-big">{{ $reviews->total() }}</div>
        <div class="summary-sub" style="margin-top:14px;">Reviews from verified customers</div>
    </div>

    {{-- Reputation --}}
    <div class="summary-card">
        <div class="summary-label">Reputation</div>
        <div class="rep-label {{ $repClass }}" style="margin-bottom:8px;">{{ $repText }}</div>
        <div class="summary-stars">
            @for($i = 1; $i <= 5; $i++)
                <span class="star {{ $i <= round($avgRating) ? 'star-on' : 'star-off' }}">★</span>
            @endfor
        </div>
        <div class="summary-sub">{{ round($avgRating, 1) }}-star provider</div>
    </div>

</div>

{{-- Filter bar --}}
<div class="filter-bar">
    <form action="{{ route('provider.reviews') }}" method="GET">
        <div class="filter-row">
            <div class="filter-field">
                <label for="f-rating">Rating</label>
                <select name="rating" id="f-rating">
                    <option value="">All Ratings</option>
                    @foreach($ratings as $r)
                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>
                            {{ $r }} {{ $r === 1 ? 'star' : 'stars' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label for="f-sort">Sort By</label>
                <select name="sort" id="f-sort">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="rating_high" {{ request('sort') === 'rating_high' ? 'selected' : '' }}>Rating: High to Low</option>
                    <option value="rating_low"  {{ request('sort') === 'rating_low'  ? 'selected' : '' }}>Rating: Low to High</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply</button>
                <a href="{{ route('provider.reviews') }}" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

{{-- Review cards --}}
@forelse($reviews as $i => $review)
    <div class="review-card">
        {{-- Reviewer info --}}
        <div class="reviewer-row">
            <div class="reviewer-avatar c{{ $loop->index % 5 }}">
                {{ strtoupper(substr($review->reviewer->name ?? '?', 0, 2)) }}
            </div>
            <div class="reviewer-meta">
                <div class="reviewer-name">{{ $review->reviewer->name ?? 'Anonymous' }}</div>
                <div class="reviewer-date">{{ $review->created_at->format('M j, Y') }} &middot; {{ $review->created_at->diffForHumans() }}</div>
            </div>
        </div>

        {{-- Stars --}}
        <div class="review-stars">
            @for($s = 1; $s <= 5; $s++)
                <span class="star {{ $s <= $review->rating ? 'star-on' : 'star-off' }}">★</span>
            @endfor
        </div>

        {{-- Comment --}}
        @if($review->comment)
            <div class="review-comment">"{{ $review->comment }}"</div>
        @endif

        {{-- Service chip --}}
        @if($review->serviceRequest ?? false)
            <span class="service-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                {{ $review->serviceRequest->title }}
            </span>
        @endif
    </div>
@empty
    <div class="empty-state">
        <div class="empty-icon">★</div>
        <h3>No reviews yet</h3>
        <p>Complete service requests to receive reviews from your customers.</p>
    </div>
@endforelse

{{-- Pagination --}}
@if($reviews->hasPages())
    <div class="pagination-wrap">
        {{ $reviews->withQueryString()->links() }}
    </div>
@endif

@endsection
