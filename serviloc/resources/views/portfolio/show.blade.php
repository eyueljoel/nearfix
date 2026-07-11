@extends('layouts.app')
@section('title', $provider->name . ' · Portfolio')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--star-on:#f59e0b;--star-off:#e2e8f0;--r:14px;}

    /* Hero */
    .hero{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#0c4a6e 100%);border-radius:20px;padding:32px 36px;color:white;display:flex;gap:28px;align-items:center;margin-bottom:24px;flex-wrap:wrap;position:relative;overflow:hidden;}
    .hero::after{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;background:radial-gradient(circle,rgba(14,165,233,0.2),transparent 70%);pointer-events:none;}
    .prov-av-lg{width:84px;height:84px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:800;color:white;flex-shrink:0;border:3px solid rgba(255,255,255,0.2);position:relative;z-index:1;}
    .prov-info{flex:1;position:relative;z-index:1;}
    .prov-name{font-size:24px;font-weight:800;letter-spacing:-0.3px;margin-bottom:4px;}
    .prov-bio{font-size:13.5px;color:rgba(255,255,255,0.65);line-height:1.55;max-width:480px;margin-bottom:12px;}
    .prov-loc{font-size:13px;color:rgba(255,255,255,0.5);margin-bottom:14px;display:flex;align-items:center;gap:5px;}
    .prov-stats{display:flex;gap:28px;flex-wrap:wrap;}
    .ps{text-align:center;}
    .ps-val{font-size:22px;font-weight:800;color:white;}
    .ps-lbl{font-size:10.5px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.5px;margin-top:2px;}
    .hero-cta{display:flex;flex-direction:column;gap:9px;flex-shrink:0;position:relative;z-index:1;}
    .btn-cta-solid{padding:11px 22px;background:var(--blue);color:white;border-radius:11px;font-size:13.5px;font-weight:700;text-decoration:none;transition:all 0.2s;text-align:center;white-space:nowrap;}
    .btn-cta-solid:hover{background:var(--blue-dk);transform:translateY(-1px);box-shadow:0 6px 18px rgba(14,165,233,0.4);}
    .btn-cta-ghost{padding:10px 22px;background:rgba(255,255,255,0.1);color:white;border:1.5px solid rgba(255,255,255,0.25);border-radius:11px;font-size:13.5px;font-weight:600;text-decoration:none;transition:all 0.2s;text-align:center;white-space:nowrap;}
    .btn-cta-ghost:hover{background:rgba(255,255,255,0.2);}

    /* Trust bar */
    .trust-bar{display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;}
    .trust-chip{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:var(--white);border:1px solid var(--border);border-radius:50px;font-size:13px;font-weight:600;color:var(--text);}
    .trust-chip .tc-icon{font-size:16px;}
    .trust-chip .tc-val{color:var(--blue-dk);}

    /* Layout */
    .page-layout{display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;}

    /* Section title */
    .sec-title{font-size:16px;font-weight:700;color:var(--text);margin-bottom:16px;padding-bottom:10px;border-bottom:2px solid var(--border);display:flex;align-items:center;gap:8px;}
    .sec-count{background:var(--blue-lt);color:var(--blue-dk);font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;}

    /* Portfolio grid */
    .port-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-bottom:28px;}
    .port-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;transition:all 0.22s;}
    .port-card:hover{transform:translateY(-4px);box-shadow:0 10px 28px rgba(0,0,0,0.1);}
    .port-card.featured{border-color:var(--blue);box-shadow:0 0 0 1px var(--blue);}
    .port-img{width:100%;height:170px;background:linear-gradient(135deg,#f0f4f8,#e2e8f0);display:flex;align-items:center;justify-content:center;font-size:44px;overflow:hidden;}
    .port-img img{width:100%;height:100%;object-fit:cover;}
    .port-body{padding:14px 16px;}
    .port-title{font-size:14px;font-weight:700;color:var(--text);margin-bottom:6px;line-height:1.3;}
    .port-desc{font-size:12.5px;color:var(--muted);line-height:1.5;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .port-chips{display:flex;flex-wrap:wrap;gap:5px;}
    .port-chip{background:var(--bg);color:var(--muted);font-size:11px;font-weight:500;padding:2px 8px;border-radius:20px;border:1px solid var(--border);}
    .port-chip.blue{background:var(--blue-lt);color:var(--blue-dk);border-color:#bfdbfe;}
    .feat-badge{font-size:10px;background:var(--blue-lt);color:var(--blue-dk);padding:2px 7px;border-radius:20px;font-weight:700;margin-right:5px;}

    /* Rating card */
    .rating-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:20px;text-align:center;margin-bottom:16px;}
    .big-num{font-size:52px;font-weight:900;color:var(--text);letter-spacing:-2px;line-height:1;}
    .star-row{display:flex;justify-content:center;gap:3px;margin:8px 0 5px;}
    .star{font-size:20px;}
    .star-on{color:var(--star-on);}
    .star-off{color:var(--star-off);}
    .rev-count{font-size:13px;color:var(--muted);}

    /* Review cards */
    .rev-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:16px 18px;margin-bottom:12px;}
    .rev-hd{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
    .rev-av{width:36px;height:36px;border-radius:50%;color:white;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .rev-av.c0{background:linear-gradient(135deg,#0ea5e9,#2563eb);}
    .rev-av.c1{background:linear-gradient(135deg,#6366f1,#8b5cf6);}
    .rev-av.c2{background:linear-gradient(135deg,#059669,#0d9488);}
    .rev-av.c3{background:linear-gradient(135deg,#d97706,#f59e0b);}
    .rev-av.c4{background:linear-gradient(135deg,#dc2626,#e11d48);}
    .rev-name{font-size:13.5px;font-weight:700;color:var(--text);}
    .rev-date{font-size:11.5px;color:var(--muted);margin-top:1px;}
    .rev-stars{display:flex;gap:1px;margin-bottom:8px;}
    .rev-star{font-size:14px;}
    .rev-comment{font-size:13px;color:#374151;font-style:italic;background:var(--bg);padding:10px 12px;border-radius:8px;border-left:3px solid var(--border);line-height:1.6;}
    .rev-svc{font-size:11px;color:var(--muted);margin-top:8px;display:inline-flex;align-items:center;gap:4px;background:var(--bg);border:1px solid var(--border);padding:2px 9px;border-radius:20px;}

    /* Empty */
    .empty-box{text-align:center;padding:40px 24px;background:var(--bg);border:1.5px dashed var(--border);border-radius:var(--r);color:var(--muted);font-size:13.5px;}

    @media(max-width:1000px){.page-layout{grid-template-columns:1fr;}}
    @media(max-width:640px){.hero{padding:24px 20px;}.prov-stats{gap:16px;}.trust-bar{gap:8px;}}
</style>

{{-- Hero --}}
<div class="hero">
    <div class="prov-av-lg">{{ strtoupper(substr($provider->name,0,2)) }}</div>
    <div class="prov-info">
        <div class="prov-name">{{ $provider->name }}</div>
        @if($provider->bio)
            <div class="prov-bio">{{ $provider->bio }}</div>
        @endif
        @if($provider->address)
            <div class="prov-loc">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                {{ $provider->address }}
            </div>
        @endif
        <div class="prov-stats">
            <div class="ps"><div class="ps-val">{{ number_format($avgRating,1) }}</div><div class="ps-lbl">⭐ Rating</div></div>
            <div class="ps"><div class="ps-val">{{ $totalReviews }}</div><div class="ps-lbl">Reviews</div></div>
            <div class="ps"><div class="ps-val">{{ $completedJobs }}</div><div class="ps-lbl">Jobs Done</div></div>
            <div class="ps"><div class="ps-val">{{ $items->count() }}</div><div class="ps-lbl">Portfolio</div></div>
        </div>
    </div>
    @auth
        @if(auth()->user()->role === 'customer' || auth()->user()->role === 'admin')
            <div class="hero-cta">
                <a href="{{ route('customer.requests.create') }}" class="btn-cta-solid">📋 Post a Request</a>
                <a href="{{ route('messages.inbox') }}" class="btn-cta-ghost">💬 Send Message</a>
            </div>
        @endif
    @else
        <div class="hero-cta">
            <a href="{{ route('register') }}" class="btn-cta-solid">Get Started</a>
            <a href="{{ route('login') }}" class="btn-cta-ghost">Sign In</a>
        </div>
    @endauth
</div>

{{-- Trust bar --}}
<div class="trust-bar">
    <div class="trust-chip">
        <span class="tc-icon">⭐</span>
        <span><span class="tc-val">{{ number_format($avgRating,1) }}/5</span> Rating</span>
    </div>
    <div class="trust-chip">
        <span class="tc-icon">✅</span>
        <span><span class="tc-val">{{ $completedJobs }}</span> Completed Jobs</span>
    </div>
    <div class="trust-chip">
        <span class="tc-icon">💬</span>
        <span><span class="tc-val">{{ $totalReviews }}</span> {{ Str::plural('Review',$totalReviews) }}</span>
    </div>
    @if($provider->phone)
        <div class="trust-chip">
            <span class="tc-icon">📱</span>
            <span>{{ $provider->phone }}</span>
        </div>
    @endif
    <div class="trust-chip">
        <span class="tc-icon">🗂️</span>
        <span><span class="tc-val">{{ $items->count() }}</span> Portfolio Items</span>
    </div>
</div>

{{-- Two-column --}}
<div class="page-layout">

    {{-- LEFT: Portfolio --}}
    <div>
        <div class="sec-title">
            Portfolio
            <span class="sec-count">{{ $items->count() }}</span>
        </div>

        @if($items->count() > 0)
            <div class="port-grid">
                @foreach($items as $item)
                    <div class="port-card {{ $item->is_featured ? 'featured' : '' }}">
                        <div class="port-img">
                            @if($item->image_path)
                                <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}">
                            @else
                                🔧
                            @endif
                        </div>
                        <div class="port-body">
                            <div class="port-title">
                                @if($item->is_featured)<span class="feat-badge">★ Featured</span>@endif
                                {{ $item->title }}
                            </div>
                            @if($item->description)
                                <div class="port-desc">{{ $item->description }}</div>
                            @endif
                            <div class="port-chips">
                                @if($item->category)<span class="port-chip blue">{{ $item->category }}</span>@endif
                                @if($item->location)<span class="port-chip">📍 {{ $item->location }}</span>@endif
                                @if($item->price_from)<span class="port-chip">💰 ETB {{ number_format($item->price_from,0) }}+</span>@endif
                                @if($item->duration_days)<span class="port-chip">⏱ {{ $item->duration_days }}d</span>@endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-box">
                <div style="font-size:36px;margin-bottom:10px;opacity:0.5;">🗂️</div>
                <p>This provider hasn't added portfolio items yet.</p>
            </div>
        @endif
    </div>

    {{-- RIGHT: Rating + Reviews --}}
    <div>
        {{-- Rating summary --}}
        <div class="rating-card">
            <div class="big-num">{{ number_format($avgRating,1) }}</div>
            <div class="star-row">
                @for($i=1;$i<=5;$i++)
                    <span class="star {{ $i<=round($avgRating) ? 'star-on' : 'star-off' }}">★</span>
                @endfor
            </div>
            <div class="rev-count">{{ $totalReviews }} verified {{ Str::plural('review',$totalReviews) }}</div>

            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.requests.create') }}"
                       style="display:block;margin-top:16px;padding:10px;background:var(--blue);color:white;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;transition:background 0.18s;">
                        📋 Post a Request for This Provider
                    </a>
                @endif
            @endauth
        </div>

        {{-- Reviews --}}
        <div class="sec-title" style="margin-top:4px;">
            Reviews
            <span class="sec-count">{{ $totalReviews }}</span>
        </div>

        @forelse($reviews as $review)
            <div class="rev-card">
                <div class="rev-hd">
                    <div class="rev-av c{{ $loop->index % 5 }}">{{ strtoupper(substr($review->reviewer->name ?? '?',0,2)) }}</div>
                    <div style="flex:1;">
                        <div class="rev-name">{{ $review->reviewer->name ?? 'Customer' }}</div>
                        <div class="rev-date">{{ $review->created_at->format('M j, Y') }} · {{ $review->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div class="rev-stars">
                    @for($s=1;$s<=5;$s++)
                        <span class="rev-star {{ $s<=$review->rating ? 'star-on' : 'star-off' }}">★</span>
                    @endfor
                </div>
                @if($review->comment)
                    <div class="rev-comment">"{{ $review->comment }}"</div>
                @endif
                @if($review->serviceRequest ?? false)
                    <div class="rev-svc">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        {{ \Illuminate\Support\Str::limit($review->serviceRequest->title,45) }}
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-box" style="padding:28px;">
                <p>No reviews yet for this provider.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
