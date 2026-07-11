@extends('layouts.app')
@section('title', 'My Reviews')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--star-on:#f59e0b;--star-off:#e2e8f0;--r:12px;}
    .pg-hd{display:flex;align-items:center;gap:10px;margin-bottom:20px;flex-wrap:wrap;}
    .pg-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;}
    .count-chip{background:var(--blue-lt);color:var(--blue-dk);font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #bfdbfe;}

    .filter-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:18px;}
    .filter-row{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;}
    .f-field{flex:1;min-width:140px;}
    .f-field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--muted);margin-bottom:5px;}
    .f-field select{width:100%;padding:8px 11px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text);background:var(--bg);font-family:inherit;outline:none;transition:border-color 0.18s;}
    .f-field select:focus{border-color:var(--blue);}
    .btn-filter{padding:8px 18px;background:var(--blue);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;}
    .btn-filter:hover{background:var(--blue-dk);}
    .btn-clear{padding:8px 13px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;transition:all 0.18s;}
    .btn-clear:hover{border-color:var(--muted);color:var(--text);}

    .review-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;margin-bottom:12px;transition:all 0.2s;}
    .review-card:hover{box-shadow:0 3px 14px rgba(0,0,0,0.06);}
    .review-hd{display:flex;align-items:center;gap:12px;margin-bottom:12px;}
    .prov-av{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#2563eb);color:white;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .prov-av.c1{background:linear-gradient(135deg,#6366f1,#8b5cf6);}
    .prov-av.c2{background:linear-gradient(135deg,#059669,#0d9488);}
    .prov-av.c3{background:linear-gradient(135deg,#d97706,#f59e0b);}
    .prov-av.c4{background:linear-gradient(135deg,#dc2626,#e11d48);}
    .prov-info{flex:1;}
    .prov-name{font-size:14px;font-weight:700;color:var(--text);line-height:1.2;}
    .prov-role{font-size:11.5px;color:var(--muted);}
    .review-date{font-size:12px;color:var(--muted);white-space:nowrap;}
    .stars-row{display:flex;gap:1px;margin-bottom:10px;}
    .star{font-size:16px;line-height:1;}
    .star-on{color:var(--star-on);}
    .star-off{color:var(--star-off);}
    .rating-label{font-size:13px;font-weight:700;color:var(--text);margin-left:8px;}
    .review-comment{font-size:13.5px;color:#374151;font-style:italic;line-height:1.65;background:var(--bg);padding:12px 14px;border-radius:8px;border-left:3px solid var(--border);margin-bottom:10px;}
    .svc-chip{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;color:var(--muted);background:var(--bg);border:1px solid var(--border);padding:3px 10px;border-radius:20px;}

    .empty-state{background:var(--white);border:1.5px dashed var(--border);border-radius:var(--r);padding:60px 24px;text-align:center;}
    .empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
    .empty-state p{font-size:13.5px;color:var(--muted);margin-bottom:18px;}
    .btn-cta{display:inline-flex;align-items:center;padding:9px 20px;background:var(--blue);color:white;border-radius:var(--r);font-size:13px;font-weight:600;text-decoration:none;}
    .btn-cta:hover{background:var(--blue-dk);}
</style>

<div class="pg-hd">
    <h1 class="pg-title">My Reviews</h1>
    <span class="count-chip">{{ $reviews->total() }} {{ Str::plural('review',$reviews->total()) }}</span>
</div>

@if($reviews->total() > 0)
<div class="filter-bar">
    <form action="{{ route('customer.reviews') }}" method="GET">
        <div class="filter-row">
            <div class="f-field">
                <label>Rating</label>
                <select name="rating">
                    <option value="">All Ratings</option>
                    @foreach([5,4,3,2,1] as $r)
                        <option value="{{ $r }}" {{ request('rating')==$r ? 'selected' : '' }}>{{ $r }} {{ $r===1?'star':'stars' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="f-field">
                <label>Sort By</label>
                <select name="sort">
                    <option value="latest" {{ request('sort','latest')==='latest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort')==='oldest' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;padding-bottom:1px;flex-shrink:0;">
                <button type="submit" class="btn-filter">Apply</button>
                <a href="{{ route('customer.reviews') }}" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>
@endif

@forelse($reviews as $review)
    <div class="review-card">
        <div class="review-hd">
            <div class="prov-av c{{ $loop->index % 5 }}">{{ strtoupper(substr($review->reviewee->name,0,2)) }}</div>
            <div class="prov-info">
                <div class="prov-name">{{ $review->reviewee->name }}</div>
                <div class="prov-role">Service Provider</div>
            </div>
            <div class="review-date">{{ $review->created_at->format('M j, Y') }}</div>
        </div>

        <div style="display:flex;align-items:center;margin-bottom:10px;">
            <div class="stars-row">
                @for($i=1;$i<=5;$i++)
                    <span class="star {{ $i<=$review->rating ? 'star-on' : 'star-off' }}">★</span>
                @endfor
            </div>
            <span class="rating-label">{{ $review->rating }}/5</span>
        </div>

        @if($review->comment)
            <div class="review-comment">"{{ $review->comment }}"</div>
        @endif

        @if($review->serviceRequest ?? false)
            <span class="svc-chip">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                {{ $review->serviceRequest->title }}
            </span>
        @endif
    </div>
@empty
    <div class="empty-state">
        <div style="width:52px;height:52px;background:var(--yellow-lt);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px;">⭐</div>
        <h3>
            @if(request('rating')) No reviews with {{ request('rating') }} {{ request('rating')==1?'star':'stars' }}
            @else No reviews yet @endif
        </h3>
        <p>
            @if(request('rating')) Try a different rating filter.
            @else Complete a service and leave a review for your provider. @endif
        </p>
        @if(!request('rating'))
            <a href="{{ route('customer.dashboard') }}" class="btn-cta">Go to Dashboard</a>
        @endif
    </div>
@endforelse

@if($reviews->hasPages())
    <div style="margin-top:24px;">{{ $reviews->withQueryString()->links() }}</div>
@endif
@endsection
