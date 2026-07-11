@extends('layouts.app')
@section('title', 'Offers Received')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--r:12px;}
    .pg-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;}
    .pg-hd-l{display:flex;align-items:center;gap:10px;}
    .pg-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;}
    .count-chip{background:var(--blue-lt);color:var(--blue-dk);font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #bfdbfe;}

    .filter-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:18px;}
    .filter-row{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;}
    .f-field{flex:1;min-width:140px;}
    .f-field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--muted);margin-bottom:5px;}
    .f-field select{width:100%;padding:8px 11px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text);background:var(--bg);font-family:inherit;outline:none;transition:border-color 0.18s;}
    .f-field select:focus{border-color:var(--blue);}
    .btn-filter{padding:8px 18px;background:var(--blue);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:background 0.18s;white-space:nowrap;}
    .btn-filter:hover{background:var(--blue-dk);}
    .btn-clear{padding:8px 13px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;transition:all 0.18s;}
    .btn-clear:hover{border-color:var(--muted);color:var(--text);}

    .offer-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;margin-bottom:12px;transition:all 0.2s;}
    .offer-card:hover{box-shadow:0 4px 16px rgba(0,0,0,0.07);}
    .offer-top{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:12px;}
    .offer-left{flex:1;min-width:0;}
    .offer-svc{font-size:15px;font-weight:700;color:var(--text);margin-bottom:3px;}
    .offer-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:12px;color:var(--muted);}
    .meta-dot{width:3px;height:3px;background:var(--border);border-radius:50%;}
    .offer-right{text-align:right;flex-shrink:0;}
    .offer-price{font-size:20px;font-weight:800;color:var(--blue-dk);}
    .offer-price small{font-size:11px;font-weight:500;color:var(--muted);}
    .offer-status{margin-top:4px;}
    .status-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
    .status-pill::before{content:'';width:6px;height:6px;border-radius:50%;flex-shrink:0;}
    .sp-pending{background:var(--yellow-lt);color:var(--yellow);}.sp-pending::before{background:var(--yellow);}
    .sp-accepted{background:var(--green-lt);color:var(--green);}.sp-accepted::before{background:var(--green);}
    .sp-rejected{background:#fef2f2;color:var(--red);}.sp-rejected::before{background:var(--red);}
    .offer-prov{display:flex;align-items:center;gap:9px;margin-bottom:10px;}
    .prov-av{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));color:white;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .prov-name{font-size:13px;font-weight:600;color:var(--text);}
    .prov-msg{font-size:13px;color:var(--muted);background:var(--bg);padding:10px 12px;border-radius:8px;line-height:1.55;margin-bottom:12px;}
    .offer-footer{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding-top:12px;border-top:1px solid var(--border);}
    .offer-loc{font-size:12px;color:var(--muted);display:flex;align-items:center;gap:5px;}
    .offer-actions{display:flex;gap:8px;flex-wrap:wrap;}
    .btn-accept{padding:8px 18px;background:var(--green);color:white;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-accept:hover{background:#047857;transform:translateY(-1px);}
    .btn-decline{padding:8px 13px;background:var(--bg);color:var(--red);border:1.5px solid #fca5a5;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-decline:hover{background:#fef2f2;}
    .btn-pay{padding:8px 18px;background:var(--blue);color:white;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;text-decoration:none;transition:all 0.2s;display:inline-flex;align-items:center;gap:6px;}
    .btn-pay:hover{background:var(--blue-dk);transform:translateY(-1px);}
    .btn-paid{padding:8px 16px;background:var(--green-lt);color:var(--green);border:1px solid #a7f3d0;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
    .done-badge{padding:6px 14px;background:var(--green-lt);color:var(--green);border-radius:8px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:5px;}

    .empty-state{background:var(--white);border:1.5px dashed var(--border);border-radius:var(--r);padding:60px 24px;text-align:center;}
    .empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
    .empty-state p{font-size:13.5px;color:var(--muted);margin-bottom:18px;}
    .btn-cta{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;background:var(--blue);color:white;border-radius:var(--r);font-size:13px;font-weight:600;text-decoration:none;transition:background 0.18s;}
    .btn-cta:hover{background:var(--blue-dk);}
    @media(max-width:640px){.offer-top{flex-wrap:wrap;}.offer-right{text-align:left;}}
</style>

<div class="pg-hd">
    <div class="pg-hd-l">
        <h1 class="pg-title">Offers Received</h1>
        <span class="count-chip">{{ $offers->total() }}</span>
    </div>
</div>

<div class="filter-bar">
    <form action="{{ route('customer.offers') }}" method="GET">
        <div class="filter-row">
            <div class="f-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="pending"  {{ request('status')==='pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ request('status')==='accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status')==='rejected' ? 'selected' : '' }}>Rejected</option>
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
                <a href="{{ route('customer.offers') }}" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

@forelse($offers as $offer)
    @php $payment = \App\Models\Payment::where('offer_id',$offer->id)->first(); @endphp
    <div class="offer-card">
        <div class="offer-top">
            <div class="offer-left">
                <div class="offer-svc">{{ $offer->serviceRequest->title }}</div>
                <div class="offer-meta">
                    <span>📍 {{ $offer->serviceRequest->location }}</span>
                    <span class="meta-dot"></span>
                    <span>{{ $offer->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <div class="offer-right">
                <div class="offer-price"><small>ETB</small> {{ number_format($offer->price,0) }}</div>
                <div class="offer-status">
                    <span class="status-pill sp-{{ $offer->status }}">{{ ucfirst($offer->status) }}</span>
                </div>
            </div>
        </div>

        <div class="offer-prov">
            <div class="prov-av">{{ strtoupper(substr($offer->provider->name,0,2)) }}</div>
            <div>
                <div class="prov-name">{{ $offer->provider->name }}</div>
            </div>
            <a href="{{ route('portfolio.show', $offer->provider) }}"
               style="margin-left:auto;display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:var(--blue);text-decoration:none;padding:4px 10px;background:var(--blue-lt);border-radius:20px;border:1px solid #bfdbfe;white-space:nowrap;transition:all 0.18s;"
               target="_blank">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View Portfolio
            </a>
        </div>

        @if($offer->message)
            <div class="prov-msg">{{ \Illuminate\Support\Str::limit($offer->message,160) }}</div>
        @endif

        <div class="offer-footer">
            <div class="offer-loc">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $offer->created_at->format('M d, Y') }}
            </div>
            <div class="offer-actions">
                @if($offer->status === 'pending')
                    <form action="{{ route('offers.accept',$offer->id) }}" method="POST" style="display:inline;">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-accept">Accept</button>
                    </form>
                    <form action="{{ route('offers.reject',$offer->id) }}" method="POST" style="display:inline;">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-decline">Decline</button>
                    </form>
                @elseif($offer->status === 'accepted')
                    @if(!$payment)
                        <a href="{{ route('payments.checkout',$offer->id) }}" class="btn-pay">💳 Pay Now</a>
                    @elseif($payment->isPaid())
                        <a href="{{ route('payments.receipt',$payment) }}" class="btn-paid">✅ Paid · View Receipt</a>
                    @elseif($payment->isReleased())
                        <span class="done-badge">✅ Completed</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <div style="width:52px;height:52px;background:var(--blue-lt);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;">🤝</div>
        <h3>No offers yet</h3>
        <p>Post a service request and providers will send you competitive offers.</p>
        <a href="{{ route('customer.requests.create') }}" class="btn-cta">Post a Request</a>
    </div>
@endforelse

@if($offers->hasPages())
    <div style="margin-top:24px;">{{ $offers->withQueryString()->links() }}</div>
@endif
@endsection
