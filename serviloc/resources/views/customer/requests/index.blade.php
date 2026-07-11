@extends('layouts.app')
@section('title', 'My Requests')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--r:12px;}
    .pg-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;}
    .pg-hd-l{display:flex;align-items:center;gap:10px;}
    .pg-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;}
    .count-chip{background:var(--blue-lt);color:var(--blue-dk);font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #bfdbfe;}
    .btn-new{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:var(--blue);color:white;border:none;border-radius:var(--r);font-size:13.5px;font-weight:600;text-decoration:none;transition:all 0.2s;font-family:inherit;cursor:pointer;}
    .btn-new:hover{background:var(--blue-dk);transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,0.35);}

    .req-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;margin-bottom:10px;display:flex;align-items:center;gap:16px;transition:all 0.2s;}
    .req-card:hover{border-color:#94a3b8;box-shadow:0 3px 14px rgba(0,0,0,0.06);}
    .req-icon{width:44px;height:44px;border-radius:11px;background:var(--blue-lt);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
    .req-body{flex:1;min-width:0;}
    .req-title{font-size:14.5px;font-weight:700;color:var(--text);text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px;}
    .req-title:hover{color:var(--blue);}
    .req-meta{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
    .meta-item{display:inline-flex;align-items:center;gap:4px;font-size:11.5px;color:var(--muted);}
    .req-right{display:flex;align-items:center;gap:10px;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;}
    .req-budget{font-size:15px;font-weight:800;color:var(--blue-dk);}
    .req-budget small{font-size:11px;font-weight:500;color:var(--muted);}
    .offer-chip{background:var(--bg);border:1px solid var(--border);color:var(--muted);font-size:11.5px;font-weight:600;padding:3px 9px;border-radius:20px;}
    .offer-chip.has-offers{background:var(--blue-lt);border-color:#bfdbfe;color:var(--blue-dk);}
    .view-btn{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;background:var(--bg);border:1.5px solid var(--border);border-radius:8px;font-size:12.5px;font-weight:600;color:var(--muted);text-decoration:none;transition:all 0.18s;}
    .view-btn:hover{border-color:var(--blue);color:var(--blue);background:var(--blue-lt);}
    .status-badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;}
    .st-open{background:var(--blue-lt);color:var(--blue-dk);}
    .st-assigned{background:var(--yellow-lt);color:var(--yellow);}
    .st-in_progress{background:#fef3c7;color:#92400e;}
    .st-completed{background:var(--green-lt);color:var(--green);}
    .st-cancelled{background:#fef2f2;color:var(--red);}
    .empty-state{background:var(--white);border:1.5px dashed var(--border);border-radius:var(--r);padding:64px 24px;text-align:center;}
    .empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
    .empty-state p{font-size:13.5px;color:var(--muted);margin-bottom:20px;}
    @media(max-width:640px){.req-right{width:100%;justify-content:flex-start;}.req-card{flex-wrap:wrap;}}
</style>

<div class="pg-hd">
    <div class="pg-hd-l">
        <h1 class="pg-title">My Requests</h1>
        <span class="count-chip">{{ $requests->count() }}</span>
    </div>
    <a href="{{ route('customer.requests.create') }}" class="btn-new">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Post New Request
    </a>
</div>

@forelse($requests as $req)
    @php
        $offerCount = $req->offers->count();
        $statusClass = 'st-' . str_replace(' ','_',$req->status);
        $icons = ['open'=>'📋','assigned'=>'⚡','in_progress'=>'🔧','completed'=>'✅','cancelled'=>'❌'];
    @endphp
    <div class="req-card">
        <div class="req-icon">{{ $icons[$req->status] ?? '📋' }}</div>
        <div class="req-body">
            <a href="{{ route('customer.requests.show',$req->id) }}" class="req-title">{{ $req->title }}</a>
            <div class="req-meta">
                <span class="meta-item">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $req->created_at->format('M j, Y') }}
                </span>
                <span class="meta-item">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                    {{ $req->location }}
                </span>
                @if($req->category)
                    <span class="meta-item">📁 {{ $req->category->name }}</span>
                @endif
            </div>
        </div>
        <div class="req-right">
            <span class="offer-chip {{ $offerCount > 0 ? 'has-offers' : '' }}">
                {{ $offerCount }} {{ Str::plural('offer',$offerCount) }}
            </span>
            <span class="status-badge {{ $statusClass }}">{{ ucfirst($req->status) }}</span>
            <span class="req-budget"><small>ETB</small> {{ number_format($req->budget,0) }}</span>
            <a href="{{ route('customer.requests.show',$req->id) }}" class="view-btn">
                View
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </div>
@empty
    <div class="empty-state">
        <div style="width:56px;height:56px;background:var(--blue-lt);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px;">📋</div>
        <h3>No requests yet</h3>
        <p>Post your first service request and get offers from verified providers.</p>
        <a href="{{ route('customer.requests.create') }}" class="btn-new" style="display:inline-flex;">Post a Request</a>
    </div>
@endforelse
@endsection
