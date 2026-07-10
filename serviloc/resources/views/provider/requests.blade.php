@extends('layouts.app')
@section('title', 'Available Requests')

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

    .btn-toggle-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
        font-family: inherit;
        transition: all 0.18s;
    }

    .btn-toggle-filter:hover {
        border-color: var(--blue);
        color: var(--blue);
        background: var(--blue-light);
    }

    .btn-toggle-filter svg {
        width: 15px;
        height: 15px;
        transition: transform 0.2s;
    }

    .btn-toggle-filter.open svg {
        transform: rotate(180deg);
    }

    /* ── Filter bar ──────────────────────────── */
    .filter-bar {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 20px;
        margin-bottom: 20px;
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.25s ease, padding 0.25s;
    }

    .filter-bar.collapsed {
        max-height: 0;
        padding-top: 0;
        padding-bottom: 0;
        opacity: 0;
        border-color: transparent;
        margin-bottom: 0;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto;
        gap: 12px;
        align-items: end;
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

    .filter-field select,
    .filter-field input {
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

    .filter-field select:focus,
    .filter-field input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(14,165,233,0.12);
        background: var(--white);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        padding-bottom: 1px;
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
        font-family: inherit;
        cursor: pointer;
        transition: all 0.18s;
        white-space: nowrap;
    }

    .btn-clear:hover {
        border-color: var(--muted);
        color: var(--text);
        background: var(--white);
    }

    /* ── Request cards ───────────────────────── */
    .req-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 20px;
        margin-bottom: 12px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .req-card:hover {
        border-color: var(--blue);
        box-shadow: 0 4px 18px rgba(14,165,233,0.1);
    }

    /* top meta row */
    .req-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 10px;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        font-weight: 500;
        padding: 3px 9px;
        border-radius: 20px;
        border: 1px solid var(--border);
        color: var(--muted);
        background: var(--bg);
        white-space: nowrap;
    }

    .chip-blue {
        background: var(--blue-light);
        color: var(--blue-dark);
        border-color: #bfdbfe;
        font-weight: 600;
    }

    .chip-green {
        background: var(--green-lt);
        color: var(--green);
        border-color: #a7f3d0;
        font-weight: 600;
    }

    /* title & description */
    .req-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 5px;
        line-height: 1.3;
    }

    .req-desc {
        font-size: 13px;
        color: var(--muted);
        line-height: 1.55;
        margin-bottom: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* bottom row */
    .req-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    .cust-info {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .cust-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--blue), var(--blue-dark));
        color: var(--white);
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cust-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.2;
    }

    .cust-label {
        font-size: 11px;
        color: var(--muted);
    }

    .req-footer-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .budget-val {
        font-size: 18px;
        font-weight: 800;
        color: var(--blue-dark);
        letter-spacing: -0.3px;
    }

    .budget-val span {
        font-size: 12px;
        font-weight: 500;
        color: var(--muted);
    }

    .btn-offer {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: var(--blue);
        color: var(--white);
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
    }

    .btn-offer:hover {
        background: var(--blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(14,165,233,0.35);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 14px;
        background: var(--bg);
        color: var(--muted);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.18s;
    }

    .btn-secondary:hover {
        border-color: var(--blue);
        color: var(--blue);
        background: var(--blue-light);
    }

    .badge-offered {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        background: var(--green-lt);
        color: var(--green);
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
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

    /* ── Pagination wrapper ───────────────────── */
    .pagination-wrap {
        margin-top: 24px;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
        .filter-actions {
            grid-column: span 2;
        }
    }

    @media (max-width: 560px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
        .filter-actions {
            grid-column: span 1;
        }
        .req-footer {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

{{-- Page header --}}
<div class="pg-header">
    <div class="pg-header-left">
        <h1 class="pg-title">Available Requests</h1>
        <span class="count-chip">{{ $requests->total() }} open</span>
    </div>
    <button class="btn-toggle-filter" id="filterToggle" onclick="toggleFilter(this)" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 6h18M7 12h10M11 18h2"/>
        </svg>
        Filters
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </button>
</div>

{{-- Filter bar --}}
<div class="filter-bar{{ request()->hasAny(['category','min_budget','max_budget','sort']) ? '' : ' collapsed' }}" id="filterBar">
    <form action="{{ route('provider.requests') }}" method="GET">
        <div class="filter-grid">
            <div class="filter-field">
                <label for="f-category">Category</label>
                <select name="category" id="f-category">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label for="f-min">Min Budget (ETB)</label>
                <input type="number" name="min_budget" id="f-min" placeholder="e.g. 500" value="{{ request('min_budget') }}">
            </div>
            <div class="filter-field">
                <label for="f-max">Max Budget (ETB)</label>
                <input type="number" name="max_budget" id="f-max" placeholder="e.g. 5000" value="{{ request('max_budget') }}">
            </div>
            <div class="filter-field">
                <label for="f-sort">Sort By</label>
                <select name="sort" id="f-sort">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="budget_high" {{ request('sort') === 'budget_high' ? 'selected' : '' }}>Budget: High to Low</option>
                    <option value="budget_low" {{ request('sort') === 'budget_low' ? 'selected' : '' }}>Budget: Low to High</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply</button>
                <a href="{{ route('provider.requests') }}" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

{{-- Request cards --}}
@forelse($requests as $req)
    @php
        $alreadyOffered = auth()->user()->offers()->where('service_request_id', $req->id)->exists();
    @endphp
    <div class="req-card">
        {{-- Meta chips --}}
        <div class="req-meta-row">
            @if($req->category)
                <span class="chip chip-blue">{{ $req->category->name }}</span>
            @endif
            <span class="chip">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                {{ $req->location }}
            </span>
            <span class="chip">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $req->created_at->diffForHumans() }}
            </span>
            <span class="chip">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                {{ $req->offers()->count() }} {{ Str::plural('offer', $req->offers()->count()) }}
            </span>
            @if($alreadyOffered)
                <span class="chip chip-green">Offer Sent</span>
            @endif
        </div>

        {{-- Title + description --}}
        <div class="req-title">{{ $req->title }}</div>
        @if($req->description)
            <div class="req-desc">{{ $req->description }}</div>
        @endif

        {{-- Footer --}}
        <div class="req-footer">
            <div class="cust-info">
                <div class="cust-avatar">{{ strtoupper(substr($req->user->name, 0, 2)) }}</div>
                <div>
                    <div class="cust-name">{{ $req->user->name }}</div>
                    <div class="cust-label">Customer</div>
                </div>
            </div>
            <div class="req-footer-right">
                <div class="budget-val"><span>ETB</span> {{ number_format($req->budget, 0) }}</div>
                @if($alreadyOffered)
                    <span class="badge-offered">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Offer Sent
                    </span>
                @else
                    <a href="{{ route('offers.create', $req->id) }}" class="btn-offer">
                        Send Offer
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                @endif
                <a href="{{ route('messages.show', $req) }}" class="btn-secondary">Details</a>
            </div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <div class="empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                <rect x="9" y="3" width="6" height="4" rx="1"/>
                <line x1="9" y1="12" x2="15" y2="12"/>
                <line x1="9" y1="16" x2="12" y2="16"/>
            </svg>
        </div>
        <h3>No requests available right now</h3>
        <p>Check back soon — new service requests are posted regularly.</p>
        <a href="{{ route('provider.dashboard') }}" class="btn-offer" style="text-decoration:none; display:inline-flex;">
            Back to Dashboard
        </a>
    </div>
@endforelse

{{-- Pagination --}}
@if($requests->hasPages())
    <div class="pagination-wrap">
        {{ $requests->withQueryString()->links() }}
    </div>
@endif

@push('scripts')
<script>
    (function () {
        var bar    = document.getElementById('filterBar');
        var toggle = document.getElementById('filterToggle');
        var open   = !bar.classList.contains('collapsed');
        if (open) toggle.classList.add('open');

        window.toggleFilter = function (btn) {
            open = !open;
            bar.classList.toggle('collapsed', !open);
            btn.classList.toggle('open', open);
        };
    })();
</script>
@endpush

@endsection
