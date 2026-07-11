@extends('layouts.app')
@section('title', 'All Reviews')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--star-on:#f59e0b;--star-off:#e2e8f0;--red:#dc2626;--r:12px;}
    .pg-hd{display:flex;align-items:center;gap:10px;margin-bottom:20px;flex-wrap:wrap;}
    .pg-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;}
    .count-chip{background:var(--blue-lt);color:var(--blue-dk);font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #bfdbfe;}
    .filter-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:18px;}
    .filter-row{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;}
    .f-field{flex:1;min-width:140px;}
    .f-field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--muted);margin-bottom:5px;}
    .f-field select{width:100%;padding:8px 11px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text);background:var(--bg);font-family:inherit;outline:none;transition:border-color 0.18s;}
    .f-field select:focus{border-color:var(--blue);}
    .btn-f{padding:8px 18px;background:var(--blue);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;}
    .btn-f:hover{background:var(--blue-dk);}
    .btn-cl{padding:8px 13px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;}
    .btn-cl:hover{border-color:var(--muted);color:var(--text);}
    .review-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;margin-bottom:10px;display:flex;align-items:flex-start;gap:16px;transition:all 0.2s;}
    .review-card:hover{box-shadow:0 3px 12px rgba(0,0,0,0.06);}
    .r-left{flex:1;min-width:0;}
    .r-parties{display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap;}
    .r-name{font-size:13.5px;font-weight:700;color:var(--text);}
    .r-arrow{font-size:12px;color:var(--muted);}
    .r-provider{font-size:13px;font-weight:600;color:var(--blue-dk);}
    .stars-row{display:flex;gap:1px;margin-bottom:8px;}
    .star{font-size:14px;line-height:1;}
    .star-on{color:var(--star-on);}
    .star-off{color:var(--star-off);}
    .r-comment{font-size:13px;color:#374151;font-style:italic;background:var(--bg);padding:9px 12px;border-radius:7px;border-left:3px solid var(--border);line-height:1.55;}
    .svc-chip{display:inline-flex;align-items:center;gap:5px;font-size:11px;color:var(--muted);background:var(--bg);border:1px solid var(--border);padding:2px 9px;border-radius:20px;margin-top:8px;}
    .r-right{text-align:right;flex-shrink:0;}
    .r-date{font-size:12px;color:var(--muted);}
    .r-rating-big{font-size:20px;font-weight:800;color:var(--text);}
    .empty-state{background:var(--white);border:1.5px dashed var(--border);border-radius:var(--r);padding:60px 24px;text-align:center;}
    .empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
    .empty-state p{font-size:13.5px;color:var(--muted);}
</style>

<div class="pg-hd">
    <h1 class="pg-title">All Reviews</h1>
    <span class="count-chip">{{ $reviews->total() }}</span>
</div>

<div class="filter-bar">
    <form action="{{ route('admin.reviews') }}" method="GET">
        <div class="filter-row">
            <div class="f-field">
                <label>Rating</label>
                <select name="rating">
                    <option value="">All Ratings</option>
                    @foreach($ratings as $r)
                        <option value="{{ $r }}" {{ request('rating')==$r ? 'selected' : '' }}>{{ $r }} {{ $r===1?'star':'stars' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="f-field">
                <label>Sort</label>
                <select name="sort">
                    <option value="latest" {{ request('sort','latest')==='latest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort')==='oldest' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;padding-bottom:1px;flex-shrink:0;">
                <button type="submit" class="btn-f">Apply</button>
                <a href="{{ route('admin.reviews') }}" class="btn-cl">Clear</a>
            </div>
        </div>
    </form>
</div>

@forelse($reviews as $review)
    <div class="review-card">
        <div class="r-left">
            <div class="r-parties">
                <span class="r-name">{{ $review->reviewer->name ?? '—' }}</span>
                <span class="r-arrow">→</span>
                <span class="r-provider">{{ $review->reviewee->name ?? '—' }}</span>
            </div>
            <div class="stars-row">
                @for($i=1;$i<=5;$i++)
                    <span class="star {{ $i<=$review->rating ? 'star-on' : 'star-off' }}">★</span>
                @endfor
            </div>
            @if($review->comment)
                <div class="r-comment">{{ $review->comment }}</div>
            @endif
            @if($review->serviceRequest ?? false)
                <div class="svc-chip">📋 {{ \Illuminate\Support\Str::limit($review->serviceRequest->title,50) }}</div>
            @endif
        </div>
        <div class="r-right">
            <div class="r-rating-big">{{ $review->rating }}/5</div>
            <div class="r-date" style="margin-top:4px;">{{ $review->created_at->format('M j, Y') }}</div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <div style="font-size:36px;margin-bottom:12px;">⭐</div>
        <h3>No reviews yet</h3>
        <p>Reviews will appear here once customers rate completed services.</p>
    </div>
@endforelse

@if($reviews->hasPages())
    <div style="margin-top:20px;">{{ $reviews->withQueryString()->links() }}</div>
@endif
@endsection
