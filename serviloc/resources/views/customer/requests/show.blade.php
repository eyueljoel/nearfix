@extends('layouts.app')
@section('title', $request->title)

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--r:12px;}
    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:18px;transition:color 0.18s;}
    .back-link:hover{color:var(--blue);}
    .show-layout{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}
    .s-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:22px 24px;margin-bottom:16px;}
    .s-card-title{font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border);}
    .req-title{font-size:20px;font-weight:800;color:var(--text);letter-spacing:-0.3px;margin-bottom:6px;}
    .req-desc{font-size:14px;color:var(--muted);line-height:1.7;margin-bottom:20px;}
    .meta-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;padding-top:16px;border-top:1px solid var(--border);}
    .meta-item .mi-label{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--muted);margin-bottom:4px;}
    .meta-item .mi-val{font-size:14px;font-weight:600;color:var(--text);}
    .mi-budget{font-size:18px;font-weight:800;color:var(--blue-dk);}
    .status-pill{display:inline-flex;align-items:center;padding:4px 11px;border-radius:20px;font-size:12px;font-weight:600;}
    .st-open{background:var(--blue-lt);color:var(--blue-dk);}
    .st-assigned{background:var(--yellow-lt);color:var(--yellow);}
    .st-in_progress{background:#fef3c7;color:#92400e;}
    .st-completed{background:var(--green-lt);color:var(--green);}
    .st-cancelled{background:#fef2f2;color:var(--red);}

    /* Offer cards */
    .offer-card{border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:10px;transition:all 0.2s;}
    .offer-card:hover{box-shadow:0 3px 12px rgba(0,0,0,0.07);}
    .offer-card.accepted{border-color:var(--green);background:var(--green-lt);}
    .offer-card.rejected{border-color:#fca5a5;background:#fef2f2;opacity:0.75;}
    .offer-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;}
    .offer-provider{display:flex;align-items:center;gap:9px;}
    .prov-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));color:white;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .prov-name{font-size:13.5px;font-weight:700;color:var(--text);}
    .prov-time{font-size:11.5px;color:var(--muted);}
    .offer-price{font-size:18px;font-weight:800;color:var(--blue-dk);white-space:nowrap;}
    .offer-price small{font-size:11px;font-weight:500;color:var(--muted);}
    .offer-msg{font-size:13px;color:var(--muted);line-height:1.55;background:var(--bg);padding:10px 12px;border-radius:8px;margin-bottom:12px;}
    .offer-actions{display:flex;gap:8px;flex-wrap:wrap;}
    .btn-accept{padding:8px 18px;background:var(--green);color:white;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-accept:hover{background:#047857;transform:translateY(-1px);}
    .btn-reject{padding:8px 14px;background:var(--bg);color:var(--red);border:1.5px solid #fca5a5;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-reject:hover{background:#fef2f2;}
    .btn-pay{padding:8px 18px;background:var(--blue);color:white;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;text-decoration:none;transition:all 0.2s;display:inline-flex;align-items:center;gap:6px;}
    .btn-pay:hover{background:var(--blue-dk);transform:translateY(-1px);}
    .badge-accepted{background:var(--green-lt);color:var(--green);padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;}
    .badge-rejected{background:#fef2f2;color:var(--red);padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;}
    .badge-pending{background:var(--yellow-lt);color:var(--yellow);padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;}

    /* Right sidebar */
    .sidebar-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;margin-bottom:14px;position:sticky;top:80px;}
    .qa-title{font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:12px;}
    .qa-btn{display:block;width:100%;padding:10px 14px;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;text-align:center;cursor:pointer;border:none;font-family:inherit;transition:all 0.2s;margin-bottom:8px;}
    .qa-blue{background:var(--blue);color:white;}
    .qa-blue:hover{background:var(--blue-dk);}
    .qa-ghost{background:var(--bg);color:var(--muted);border:1.5px solid var(--border);}
    .qa-ghost:hover{border-color:var(--blue);color:var(--blue);background:var(--blue-lt);}
    .qa-red{background:#fef2f2;color:var(--red);border:1.5px solid #fca5a5;}
    .qa-red:hover{background:#fee2e2;}

    /* Empty offers */
    .no-offers{text-align:center;padding:32px 16px;background:var(--bg);border-radius:9px;border:1.5px dashed var(--border);}
    .no-offers p{font-size:13px;color:var(--muted);}

    @media(max-width:900px){.show-layout{grid-template-columns:1fr;}.sidebar-card{position:static;}}
    @media(max-width:640px){.meta-grid{grid-template-columns:1fr 1fr;}}
</style>

<a href="{{ route('customer.requests') }}" class="back-link">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back to My Requests
</a>

<div class="show-layout">
    <div>
        {{-- Request details --}}
        <div class="s-card">
            <div class="req-title">{{ $request->title }}</div>
            <p class="req-desc">{{ $request->description }}</p>
            <div class="meta-grid">
                <div class="meta-item">
                    <div class="mi-label">Category</div>
                    <div class="mi-val">{{ $request->category->name }}</div>
                </div>
                <div class="meta-item">
                    <div class="mi-label">Location</div>
                    <div class="mi-val">{{ $request->location }}</div>
                </div>
                <div class="meta-item">
                    <div class="mi-label">Budget</div>
                    <div class="mi-val mi-budget">ETB {{ number_format($request->budget,0) }}</div>
                </div>
                <div class="meta-item">
                    <div class="mi-label">Status</div>
                    <span class="status-pill st-{{ $request->status }}">{{ ucfirst($request->status) }}</span>
                </div>
                @if($request->scheduled_date)
                <div class="meta-item">
                    <div class="mi-label">Preferred Date</div>
                    <div class="mi-val">{{ \Carbon\Carbon::parse($request->scheduled_date)->format('M d, Y') }}</div>
                </div>
                @endif
                <div class="meta-item">
                    <div class="mi-label">Posted</div>
                    <div class="mi-val">{{ $request->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>

        {{-- Offers --}}
        <div class="s-card">
            <div class="s-card-title">
                Offers Received
                <span style="background:var(--blue-lt);color:var(--blue-dk);font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:8px;">{{ $request->offers->count() }}</span>
            </div>

            @forelse($request->offers as $offer)
                @php $payment = \App\Models\Payment::where('offer_id',$offer->id)->first(); @endphp
                <div class="offer-card {{ $offer->status }}">
                    <div class="offer-top">
                        <div class="offer-provider">
                            <div class="prov-av">{{ strtoupper(substr($offer->provider->name,0,2)) }}</div>
                            <div>
                                <div class="prov-name">{{ $offer->provider->name }}</div>
                                <div class="prov-time">
                                    @php
                                        $pRating = \App\Models\Review::where('reviewee_id', $offer->provider_id)->avg('rating') ?? 0;
                                        $pCount  = \App\Models\Review::where('reviewee_id', $offer->provider_id)->count();
                                    @endphp
                                    @if($pCount > 0)
                                        <span style="color:#f59e0b;font-size:12px;">{{ str_repeat('★', round($pRating)) }}{{ str_repeat('☆', 5 - round($pRating)) }}</span>
                                        <span style="color:var(--muted);font-size:11.5px;"> {{ number_format($pRating,1) }} ({{ $pCount }})</span>
                                    @else
                                        <span style="color:var(--muted);font-size:11.5px;">New provider</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('portfolio.show', $offer->provider) }}"
                               style="margin-left:12px;display:inline-flex;align-items:center;gap:4px;font-size:11.5px;font-weight:600;color:var(--blue);text-decoration:none;padding:4px 10px;background:var(--blue-lt);border-radius:20px;border:1px solid #bfdbfe;white-space:nowrap;"
                               target="_blank">
                               <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                               Portfolio
                            </a>
                        </div>
                        <div style="text-align:right;">
                            <div class="offer-price"><small>ETB</small> {{ number_format($offer->price,0) }}</div>
                            @if($offer->status!=='pending')
                                <span class="badge-{{ $offer->status }}">{{ ucfirst($offer->status) }}</span>
                            @endif
                        </div>
                    </div>

                    @if($offer->message)
                        <div class="offer-msg">{{ $offer->message }}</div>
                    @endif

                    <div class="offer-actions">
                        @if($offer->status === 'pending')
                            <form action="{{ route('offers.accept',$offer->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn-accept">Accept Offer</button>
                            </form>
                            <form action="{{ route('offers.reject',$offer->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn-reject">Decline</button>
                            </form>
                        @elseif($offer->status === 'accepted')
                            @if(!$payment)
                                <a href="{{ route('payments.checkout',$offer->id) }}" class="btn-pay">💳 Pay Now</a>
                            @elseif($payment->isPaid())
                                <a href="{{ route('payments.receipt',$payment) }}" class="btn-pay" style="background:var(--green);">✅ Paid · Receipt</a>
                            @elseif($payment->isReleased())
                                <span style="color:var(--green);font-size:13px;font-weight:600;">✅ Service Completed</span>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-offers">
                    <p>No offers yet — providers will send bids soon.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        <div class="sidebar-card">
            <div class="qa-title">Quick Actions</div>
            <a href="{{ route('customer.requests') }}" class="qa-btn qa-ghost">← All Requests</a>
            @if($request->status === 'open')
                <a href="{{ route('messages.inbox') }}" class="qa-btn qa-blue">Message a Provider</a>
            @endif
            @if($request->status === 'completed' && !$request->review)
                <a href="/reviews/create/{{ $request->id }}" class="qa-btn qa-blue">★ Leave a Review</a>
            @endif
        </div>

        <div class="sidebar-card">
            <div class="qa-title">Request Summary</div>
            <div style="font-size:13px;color:var(--muted);line-height:1.8;">
                <div>📁 <strong>{{ $request->category->name }}</strong></div>
                <div>📍 {{ $request->location }}</div>
                <div>💰 ETB {{ number_format($request->budget,0) }}</div>
                <div>🕐 {{ $request->created_at->diffForHumans() }}</div>
                <div>💬 {{ $request->offers->count() }} {{ Str::plural('offer',$request->offers->count()) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
