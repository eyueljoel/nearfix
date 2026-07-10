@extends('layouts.app')
@section('title', 'My Offers')

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
    }

    /* ── Page header ─────────────────────────── */
    .pg-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .pg-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pg-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.3px;
        line-height: 1.2;
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

    /* ── Offer cards ─────────────────────────── */
    .offer-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .offer-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 3px 14px rgba(0,0,0,0.06);
    }

    /* left accent bar */
    .offer-card::before {
        content: '';
        width: 3px;
        height: 100%;
        border-radius: 3px 0 0 3px;
        flex-shrink: 0;
        align-self: stretch;
        margin-left: -20px;
        margin-right: 4px;
        border-radius: 3px;
    }

    .offer-card.status-pending::before  { background: var(--yellow); }
    .offer-card.status-accepted::before { background: var(--green); }
    .offer-card.status-rejected::before { background: var(--red); }
    .offer-card.status-completed::before{ background: var(--blue); }

    .offer-left {
        flex: 1;
        min-width: 0;
    }

    .offer-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .offer-sub {
        font-size: 12.5px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .offer-sub-dot {
        width: 3px;
        height: 3px;
        background: var(--border);
        border-radius: 50%;
        flex-shrink: 0;
    }

    .offer-right {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-shrink: 0;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .offer-price {
        font-size: 18px;
        font-weight: 800;
        color: var(--blue-dark);
        letter-spacing: -0.3px;
        white-space: nowrap;
    }

    .offer-price span {
        font-size: 12px;
        font-weight: 500;
        color: var(--muted);
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .badge-pending  { background: var(--yellow-lt); color: var(--yellow); }
    .badge-pending::before  { background: var(--yellow); }

    .badge-accepted { background: var(--green-lt);  color: var(--green); }
    .badge-accepted::before { background: var(--green); }

    .badge-rejected { background: var(--red-lt);    color: var(--red); }
    .badge-rejected::before { background: var(--red); }

    .badge-completed{ background: var(--blue-light); color: var(--blue-dark); }
    .badge-completed::before{ background: var(--blue); }

    .btn-msg {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 14px;
        background: var(--bg);
        color: var(--muted);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.18s;
        white-space: nowrap;
    }

    .btn-msg:hover {
        border-color: var(--blue);
        color: var(--blue);
        background: var(--blue-light);
    }

    .btn-msg svg {
        width: 13px;
        height: 13px;
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
        background: var(--blue-light);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .empty-icon svg {
        width: 28px;
        height: 28px;
        color: var(--blue);
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
        margin-bottom: 20px;
    }

    .btn-primary-sm {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: var(--blue);
        color: var(--white);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.18s;
    }

    .btn-primary-sm:hover { background: var(--blue-dark); }

    /* ── Pagination ──────────────────────────── */
    .pagination-wrap { margin-top: 24px; }

    @media (max-width: 640px) {
        .offer-card { flex-wrap: wrap; }
        .offer-right { width: 100%; justify-content: flex-start; }
    }
</style>

{{-- Page header --}}
<div class="pg-header">
    <div class="pg-header-left">
        <h1 class="pg-title">My Offers</h1>
        <span class="count-chip">{{ $offers->total() }} total</span>
    </div>
</div>

{{-- Filter bar --}}
<div class="filter-bar">
    <form action="{{ route('provider.offers') }}" method="GET">
        <div class="filter-row">
            <div class="filter-field">
                <label for="f-status">Status</label>
                <select name="status" id="f-status">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label for="f-sort">Sort By</label>
                <select name="sort" id="f-sort">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="price_low"  {{ request('sort') === 'price_low'  ? 'selected' : '' }}>Price: Low to High</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply</button>
                <a href="{{ route('provider.offers') }}" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

{{-- Offer cards --}}
@forelse($offers as $offer)
    @php $st = $offer->status ?? 'pending'; @endphp
    <div class="offer-card status-{{ $st }}">
        <div class="offer-left">
            <div class="offer-title">{{ $offer->serviceRequest->title ?? 'Service Request' }}</div>
            <div class="offer-sub">
                <span>{{ $offer->serviceRequest->user->name ?? '—' }}</span>
                <span class="offer-sub-dot"></span>
                <span>Sent {{ $offer->created_at->format('M j, Y') }}</span>
                <span class="offer-sub-dot"></span>
                <span>{{ $offer->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <div class="offer-right">
            <div class="offer-price"><span>ETB</span> {{ number_format($offer->price, 0) }}</div>
            <span class="status-badge badge-{{ $st }}">{{ ucfirst($st) }}</span>
            <a href="{{ route('messages.show', $offer->serviceRequest->id) }}" class="btn-msg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Message
            </a>
        </div>
    </div>
@empty
    <div class="empty-state">
        <div class="empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <h3>No offers submitted yet</h3>
        <p>Browse available requests and send your first offer to get started.</p>
        <a href="{{ route('provider.requests') }}" class="btn-primary-sm">Browse Requests</a>
    </div>
@endforelse

{{-- Pagination --}}
@if($offers->hasPages())
    <div class="pagination-wrap">
        {{ $offers->withQueryString()->links() }}
    </div>
@endif

@endsection
