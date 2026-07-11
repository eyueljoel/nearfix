@extends('layouts.app')
@section('title', 'Search Results')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--r:12px;}
    .pg-hd{display:flex;align-items:center;gap:10px;margin-bottom:20px;flex-wrap:wrap;}
    .pg-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;}
    .count-chip{background:var(--blue-lt);color:var(--blue-dk);font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #bfdbfe;}
    .filter-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;margin-bottom:20px;}
    .filter-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:12px;align-items:end;}
    .f-field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--muted);margin-bottom:5px;}
    .f-ctrl{width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:9px;font-size:13px;color:var(--text);background:var(--bg);font-family:inherit;outline:none;transition:border-color 0.18s;box-sizing:border-box;}
    .f-ctrl:focus{border-color:var(--blue);background:var(--white);box-shadow:0 0 0 3px rgba(14,165,233,0.12);}
    .f-actions{display:flex;gap:8px;padding-bottom:1px;}
    .btn-search{padding:9px 20px;background:var(--blue);color:white;border:none;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:background 0.18s;white-space:nowrap;}
    .btn-search:hover{background:var(--blue-dk);}
    .btn-clear{padding:9px 14px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;transition:all 0.18s;}
    .btn-clear:hover{border-color:var(--muted);color:var(--text);}
    .result-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;margin-bottom:12px;display:flex;align-items:center;gap:16px;transition:all 0.2s;}
    .result-card:hover{border-color:var(--blue);box-shadow:0 4px 16px rgba(14,165,233,0.1);}
    .rc-body{flex:1;min-width:0;}
    .rc-title{font-size:14.5px;font-weight:700;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .rc-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:12px;color:var(--muted);}
    .rc-desc{font-size:13px;color:var(--muted);line-height:1.5;margin-top:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .meta-dot{width:3px;height:3px;background:var(--border);border-radius:50%;}
    .rc-right{text-align:right;flex-shrink:0;}
    .rc-budget{font-size:18px;font-weight:800;color:var(--blue-dk);margin-bottom:8px;}
    .rc-budget small{font-size:11px;font-weight:500;color:var(--muted);}
    .s-badge{display:inline-flex;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
    .st-open{background:var(--blue-lt);color:var(--blue-dk);}
    .st-assigned{background:var(--yellow-lt);color:var(--yellow);}
    .st-in_progress{background:#fef3c7;color:#92400e;}
    .st-completed{background:var(--green-lt);color:var(--green);}
    .st-cancelled{background:#fef2f2;color:var(--red);}
    .btn-view{display:inline-flex;align-items:center;gap:5px;padding:7px 16px;background:var(--blue);color:white;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.18s;margin-top:8px;}
    .btn-view:hover{background:var(--blue-dk);transform:translateY(-1px);}
    .empty-state{background:var(--white);border:1.5px dashed var(--border);border-radius:var(--r);padding:60px 24px;text-align:center;}
    .empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
    .empty-state p{font-size:13.5px;color:var(--muted);}
    @media(max-width:900px){.filter-grid{grid-template-columns:1fr 1fr;}.f-actions{grid-column:span 2;}}
    @media(max-width:580px){.filter-grid{grid-template-columns:1fr;}.f-actions{grid-column:span 1;}.result-card{flex-wrap:wrap;}.rc-right{width:100%;text-align:left;}}
</style>

<div class="pg-hd">
    <h1 class="pg-title">Search Results</h1>
    @if($query)
        <span class="count-chip">{{ $serviceRequests->count() }} result{{ $serviceRequests->count() !== 1 ? 's' : '' }} for "{{ $query }}"</span>
    @else
        <span class="count-chip">{{ $serviceRequests->count() }} result{{ $serviceRequests->count() !== 1 ? 's' : '' }}</span>
    @endif
</div>

<div class="filter-card">
    <form action="{{ route('search') }}" method="GET">
        <div class="filter-grid">
            <div class="f-field">
                <label>Search</label>
                <input type="text" name="q" class="f-ctrl" placeholder="What service do you need?" value="{{ $query ?? '' }}">
            </div>
            <div class="f-field">
                <label>Category</label>
                <select name="category" class="f-ctrl">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($category ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="f-field">
                <label>Location</label>
                <input type="text" name="location" class="f-ctrl" placeholder="e.g. Bole" value="{{ $location ?? '' }}">
            </div>
            <div class="f-field">
                <label>Min Budget</label>
                <input type="number" name="min_budget" class="f-ctrl" placeholder="ETB" value="{{ $minBudget ?? '' }}">
            </div>
            <div class="f-field">
                <label>Max Budget</label>
                <input type="number" name="max_budget" class="f-ctrl" placeholder="ETB" value="{{ $maxBudget ?? '' }}">
            </div>
            <div class="f-actions">
                <button type="submit" class="btn-search">Search</button>
                <a href="{{ route('search') }}" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

@forelse($serviceRequests as $req)
    <div class="result-card">
        <div class="rc-body">
            <div class="rc-title">{{ $req->title }}</div>
            <div class="rc-meta">
                @if($req->category)<span>📁 {{ $req->category->name }}</span><span class="meta-dot"></span>@endif
                <span>📍 {{ $req->location }}</span>
                <span class="meta-dot"></span>
                <span>👤 {{ $req->user->name ?? '—' }}</span>
                <span class="meta-dot"></span>
                <span>🕐 {{ $req->created_at->diffForHumans() }}</span>
            </div>
            @if($req->description)
                <div class="rc-desc">{{ $req->description }}</div>
            @endif
        </div>
        <div class="rc-right">
            <div class="rc-budget"><small>ETB</small> {{ number_format($req->budget,0) }}</div>
            <span class="s-badge st-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
            @auth
                @if(auth()->user()->role === 'provider')
                    <a href="{{ route('offers.create', $req->id) }}" class="btn-view">Send Offer →</a>
                @else
                    <a href="{{ route('customer.requests.show', $req->id) }}" class="btn-view">View →</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-view">View →</a>
            @endauth
        </div>
    </div>
@empty
    <div class="empty-state">
        <div style="width:52px;height:52px;background:var(--blue-lt);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;">🔍</div>
        <h3>No results found</h3>
        <p>Try different keywords or adjust your filters.</p>
    </div>
@endforelse
@endsection
