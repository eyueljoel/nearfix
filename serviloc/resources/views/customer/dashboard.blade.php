@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<style>
    :root {
        --blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;
        --white:#ffffff;--bg:#f8fafc;--border:#e2e8f0;
        --text:#0f172a;--muted:#64748b;
        --green:#059669;--green-lt:#ecfdf5;
        --yellow:#d97706;--yellow-lt:#fffbeb;
        --red:#dc2626;--r:12px;
    }
    .welcome-bar{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 55%,#0c4a6e 100%);border-radius:var(--r);padding:20px 26px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;position:relative;overflow:hidden;}
    .welcome-bar::after{content:'';position:absolute;right:-40px;top:-40px;width:180px;height:180px;background:radial-gradient(circle,rgba(14,165,233,0.2),transparent 70%);pointer-events:none;}
    .wb-text{color:white;position:relative;z-index:1;}
    .wb-text h2{font-size:18px;font-weight:700;letter-spacing:-0.2px;}
    .wb-text p{font-size:13px;color:rgba(255,255,255,0.6);margin-top:3px;}
    .wb-btn{background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.25);color:white;padding:9px 20px;border-radius:var(--r);font-size:13px;font-weight:600;text-decoration:none;transition:all 0.2s;position:relative;z-index:1;white-space:nowrap;}
    .wb-btn:hover{background:rgba(255,255,255,0.22);}
    .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
    .s-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:16px 18px;transition:all 0.2s;position:relative;overflow:hidden;}
    .s-card::before{content:'';position:absolute;top:0;left:0;width:3px;height:100%;}
    .s-card.c1::before{background:var(--blue)}.s-card.c2::before{background:var(--yellow)}.s-card.c3::before{background:var(--green)}.s-card.c4::before{background:#7c3aed;}
    .s-card:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,0.07);}
    .s-val{font-size:26px;font-weight:800;color:var(--text);letter-spacing:-0.5px;}
    .s-lbl{font-size:11.5px;color:var(--muted);margin-top:2px;font-weight:500;}
    .s-icon{font-size:20px;margin-bottom:8px;}
    .pending-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:16px;}
    .pending-num{font-size:28px;font-weight:800;color:var(--blue-dk);letter-spacing:-0.5px;flex-shrink:0;}
    .pending-bar-text{flex:1;}
    .pending-bar-text p{font-size:13.5px;font-weight:600;color:var(--text);}
    .pending-bar-text span{font-size:12px;color:var(--muted);}
    .pending-bar-action{font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;white-space:nowrap;}
    .pending-bar-action:hover{color:var(--blue-dk);}
    .c-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:20px 22px;margin-bottom:16px;}
    .c-card-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border);}
    .c-card-hd h3{font-size:14px;font-weight:700;color:var(--text);}
    .c-card-hd a{font-size:12.5px;color:var(--blue);font-weight:600;text-decoration:none;}
    .c-card-hd a:hover{color:var(--blue-dk);}
    .row-item{display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid var(--border);gap:12px;}
    .row-item:last-child{border-bottom:none;}
    .ri-left{flex:1;min-width:0;}
    .ri-title{font-size:13.5px;font-weight:600;color:var(--text);text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .ri-title:hover{color:var(--blue);}
    .ri-sub{font-size:12px;color:var(--muted);margin-top:1px;}
    .ri-right{display:flex;align-items:center;gap:8px;flex-shrink:0;}
    .ri-price{font-size:13px;font-weight:700;color:var(--text);}
    .s-badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
    .s-open{background:var(--blue-lt);color:var(--blue-dk);}
    .s-assigned{background:var(--yellow-lt);color:var(--yellow);}
    .s-completed{background:var(--green-lt);color:var(--green);}
    .s-cancelled{background:#fef2f2;color:var(--red);}
    .s-in_progress{background:#fef3c7;color:#92400e;}
    .s-pending{background:var(--yellow-lt);color:var(--yellow);}
    .s-accepted{background:var(--green-lt);color:var(--green);}
    .s-rejected{background:#fef2f2;color:var(--red);}
    .review-btn{font-size:11.5px;font-weight:600;color:var(--yellow);background:var(--yellow-lt);border:1px solid #fde68a;padding:3px 10px;border-radius:6px;text-decoration:none;white-space:nowrap;}
    .review-btn:hover{background:#fef3c7;}
    .empty-list{text-align:center;padding:28px 16px;background:var(--bg);border-radius:9px;border:1.5px dashed var(--border);}
    .empty-list p{font-size:13px;color:var(--muted);}
    .empty-list a{color:var(--blue);font-weight:600;text-decoration:none;}
    @media(max-width:900px){.stats-row{grid-template-columns:1fr 1fr;}}
    @media(max-width:480px){.stats-row{grid-template-columns:1fr 1fr;}.pending-bar{flex-wrap:wrap;}}
</style>

@php $hour=now()->hour; $g=$hour<12?'Good morning':($hour<17?'Good afternoon':'Good evening'); @endphp

<div class="welcome-bar">
    <div class="wb-text">
        <h2>{{ $g }}, {{ explode(' ',auth()->user()->name)[0] }}</h2>
        <p>
            @if($openRequests > 0)
                You have <strong style="color:white;">{{ $openRequests }} open {{ Str::plural('request',$openRequests) }}</strong> — {{ $pendingOffers }} pending {{ Str::plural('offer',$pendingOffers) }}
            @else
                Track your service requests and manage offers from providers
            @endif
        </p>
    </div>
    <a href="{{ route('customer.requests.create') }}" class="wb-btn">+ Post New Request</a>
</div>

<div class="stats-row">
    <div class="s-card c1"><div class="s-icon">📋</div><div class="s-val">{{ $totalRequests }}</div><div class="s-lbl">Total Requests</div></div>
    <div class="s-card c2"><div class="s-icon">🟢</div><div class="s-val">{{ $openRequests }}</div><div class="s-lbl">Open Requests</div></div>
    <div class="s-card c3"><div class="s-icon">✅</div><div class="s-val">{{ $completedRequests }}</div><div class="s-lbl">Completed</div></div>
    <div class="s-card c4"><div class="s-icon">💬</div><div class="s-val">{{ $totalOffers }}</div><div class="s-lbl">Offers Received</div></div>
</div>

@if($pendingOffers > 0)
<div class="pending-bar">
    <div class="pending-num">{{ $pendingOffers }}</div>
    <div class="pending-bar-text">
        <p>Pending {{ Str::plural('Offer',$pendingOffers) }}</p>
        <span>{{ $pendingOffers === 1 ? 'An offer is' : 'Offers are' }} waiting for your response</span>
    </div>
    <a href="{{ route('customer.offers') }}" class="pending-bar-action">Review Offers →</a>
</div>
@endif

<div class="c-card">
    <div class="c-card-hd"><h3>Recent Requests</h3><a href="{{ route('customer.requests') }}">View all →</a></div>
    @forelse($recentRequests as $req)
        <div class="row-item">
            <div class="ri-left">
                <a href="{{ route('customer.requests.show',$req->id) }}" class="ri-title">{{ $req->title }}</a>
                <div class="ri-sub">{{ $req->category->name }} &middot; {{ $req->location }}</div>
            </div>
            <div class="ri-right">
                <span class="s-badge s-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
                <span class="ri-price">ETB {{ number_format($req->budget,0) }}</span>
                @if($req->status==='completed')
                    <a href="/reviews/create/{{ $req->id }}" class="review-btn">★ Review</a>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-list">
            <p>No requests yet. <a href="{{ route('customer.requests.create') }}">Post your first one →</a></p>
        </div>
    @endforelse
</div>

<div class="c-card">
    <div class="c-card-hd"><h3>Recent Offers</h3><a href="{{ route('customer.offers') }}">View all →</a></div>
    @forelse($recentOffers as $offer)
        <div class="row-item">
            <div class="ri-left">
                <span class="ri-title" style="color:var(--text);">{{ $offer->serviceRequest->title }}</span>
                <div class="ri-sub">From {{ $offer->provider->name }}</div>
            </div>
            <div class="ri-right">
                <span class="s-badge s-{{ $offer->status }}">{{ ucfirst($offer->status) }}</span>
                <span class="ri-price">ETB {{ number_format($offer->price,0) }}</span>
            </div>
        </div>
    @empty
        <div class="empty-list">
            <p>No offers yet. Post a request to start receiving bids from providers.</p>
        </div>
    @endforelse
</div>
@endsection
