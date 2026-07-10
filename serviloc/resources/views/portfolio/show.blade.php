@extends('layouts.app')
@section('title', $provider->name . ' · Portfolio')

@section('content')
<style>
    .provider-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
        border-radius: 20px;
        padding: 36px;
        color: white;
        display: flex;
        gap: 28px;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .provider-avatar-lg {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e94560, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
        border: 3px solid rgba(255,255,255,0.2);
    }

    .provider-info { flex: 1; }

    .provider-name {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .provider-bio {
        opacity: 0.75;
        font-size: 14px;
        margin-bottom: 14px;
        line-height: 1.5;
        max-width: 500px;
    }

    .provider-stats {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }

    .pstat { text-align: center; }
    .pstat .ps-value { font-size: 22px; font-weight: 800; }
    .pstat .ps-label { font-size: 11px; opacity: 0.65; text-transform: uppercase; letter-spacing: 0.5px; }

    .btn-contact {
        background: #e94560;
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .btn-contact:hover { background: #c73652; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(233,69,96,0.4); }

    /* Portfolio grid */
    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .portfolio-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .portfolio-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }

    .portfolio-card.featured {
        border-color: #e94560;
        box-shadow: 0 0 0 1px #e94560;
    }

    .portfolio-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: linear-gradient(135deg, #f0f2f5, #e8ecf1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        overflow: hidden;
    }

    .portfolio-img img { width: 100%; height: 100%; object-fit: cover; }

    .portfolio-body { padding: 18px; }

    .portfolio-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 6px;
    }

    .portfolio-desc { font-size: 13px; color: #666; line-height: 1.5; margin-bottom: 12px; }

    .portfolio-meta { display: flex; flex-wrap: wrap; gap: 6px; }

    .meta-tag {
        background: #f0f2f5;
        color: #555;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Reviews */
    .review-card {
        background: white;
        border: 1px solid #e8ecf1;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 14px;
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .stars { color: #f59e0b; font-size: 16px; letter-spacing: 1px; }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f2f5;
    }

    .rating-summary {
        background: white;
        border: 1px solid #e8ecf1;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        margin-bottom: 20px;
    }

    .big-rating { font-size: 56px; font-weight: 800; color: #1a1a2e; line-height: 1; }
    .big-stars { font-size: 24px; margin: 8px 0; }
</style>

{{-- Provider Hero --}}
<div class="provider-hero">
    <div class="provider-avatar-lg">
        {{ strtoupper(substr($provider->name, 0, 2)) }}
    </div>
    <div class="provider-info">
        <div class="provider-name">{{ $provider->name }}</div>
        @if($provider->bio)
            <div class="provider-bio">{{ $provider->bio }}</div>
        @endif
        @if($provider->address)
            <div style="font-size:13px; opacity:0.7; margin-bottom:12px;">📍 {{ $provider->address }}</div>
        @endif
        <div class="provider-stats">
            <div class="pstat">
                <div class="ps-value">{{ number_format($avgRating, 1) }}</div>
                <div class="ps-label">⭐ Rating</div>
            </div>
            <div class="pstat">
                <div class="ps-value">{{ $totalReviews }}</div>
                <div class="ps-label">Reviews</div>
            </div>
            <div class="pstat">
                <div class="ps-value">{{ $completedJobs }}</div>
                <div class="ps-label">Jobs Done</div>
            </div>
            <div class="pstat">
                <div class="ps-value">{{ $items->count() }}</div>
                <div class="ps-label">Portfolio Items</div>
            </div>
        </div>
    </div>
    @auth
        @if(auth()->user()->role === 'customer')
            <a href="{{ route('customer.requests') }}" class="btn-contact">📋 Post a Request</a>
        @endif
    @endauth
</div>

<div style="display:grid; grid-template-columns: 1fr 340px; gap:28px; align-items:start;">
    {{-- Left: Portfolio Items --}}
    <div>
        <div class="section-title">🗂️ Portfolio ({{ $items->count() }} items)</div>

        @if($items->count() > 0)
            <div class="portfolio-grid">
                @foreach($items as $item)
                    <div class="portfolio-card {{ $item->is_featured ? 'featured' : '' }}">
                        <div class="portfolio-img">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}">
                            @else
                                🔧
                            @endif
                        </div>
                        <div class="portfolio-body">
                            <div class="portfolio-title">
                                @if($item->is_featured)
                                    <span style="font-size:10px; background:#fff0f3; color:#e94560; padding:2px 7px; border-radius:20px; font-weight:700; margin-right:6px;">★ Featured</span>
                                @endif
                                {{ $item->title }}
                            </div>
                            @if($item->description)
                                <div class="portfolio-desc">{{ Str::limit($item->description, 110) }}</div>
                            @endif
                            <div class="portfolio-meta">
                                @if($item->category)
                                    <span class="meta-tag">📁 {{ $item->category }}</span>
                                @endif
                                @if($item->location)
                                    <span class="meta-tag">📍 {{ $item->location }}</span>
                                @endif
                                @if($item->price_from)
                                    <span class="meta-tag">💰 From ETB {{ number_format($item->price_from, 0) }}</span>
                                @endif
                                @if($item->duration_days)
                                    <span class="meta-tag">⏱ {{ $item->duration_days }}d</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:60px 32px; background:white; border-radius:16px; border:1px solid #e8ecf1; color:#8895aa;">
                <div style="font-size:48px; margin-bottom:12px;">🗂️</div>
                <p style="font-size:15px;">This provider hasn't added portfolio items yet.</p>
            </div>
        @endif
    </div>

    {{-- Right: Rating & Reviews --}}
    <div>
        {{-- Rating card --}}
        <div class="rating-summary">
            <div class="big-rating">{{ number_format($avgRating, 1) }}</div>
            <div class="big-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span style="color: {{ $i <= round($avgRating) ? '#f59e0b' : '#e8ecf1' }}">★</span>
                @endfor
            </div>
            <div style="color:#8895aa; font-size:14px;">Based on {{ $totalReviews }} review{{ $totalReviews !== 1 ? 's' : '' }}</div>
        </div>

        {{-- Reviews --}}
        <div class="section-title">⭐ Reviews</div>

        @if($reviews->count() > 0)
            @foreach($reviews as $review)
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-avatar">
                            {{ strtoupper(substr($review->reviewer->name, 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-weight:600; font-size:14px; color:#1a1a2e;">{{ $review->reviewer->name }}</div>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color: {{ $i <= $review->rating ? '#f59e0b' : '#e8ecf1' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        <div style="margin-left:auto; font-size:12px; color:#8895aa;">
                            {{ $review->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @if($review->comment)
                        <p style="font-size:13px; color:#555; line-height:1.6;">{{ $review->comment }}</p>
                    @endif
                    @if($review->serviceRequest)
                        <div style="margin-top:8px; font-size:11px; color:#aaa;">
                            📋 {{ Str::limit($review->serviceRequest->title, 40) }}
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div style="text-align:center; padding:32px; background:white; border-radius:14px; border:1px solid #e8ecf1; color:#8895aa;">
                <div style="font-size:32px; margin-bottom:8px;">⭐</div>
                <p style="font-size:14px;">No reviews yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
