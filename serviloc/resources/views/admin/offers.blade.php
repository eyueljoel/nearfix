@extends('layouts.app')
@section('title', 'All Offers')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--r:12px;}
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
    .t-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;}
    .data-table{width:100%;border-collapse:collapse;}
    .data-table th{text-align:left;padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--muted);border-bottom:2px solid var(--border);background:var(--bg);white-space:nowrap;}
    .data-table td{padding:13px 16px;font-size:13.5px;color:var(--text);border-bottom:1px solid var(--border);vertical-align:middle;}
    .data-table tr:last-child td{border-bottom:none;}
    .data-table tr:hover td{background:#fafbfc;}
    .st-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;}
    .st-badge::before{content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0;}
    .sp-pending{background:var(--yellow-lt);color:var(--yellow);}.sp-pending::before{background:var(--yellow);}
    .sp-accepted{background:var(--green-lt);color:var(--green);}.sp-accepted::before{background:var(--green);}
    .sp-rejected{background:#fef2f2;color:var(--red);}.sp-rejected::before{background:var(--red);}
    .sp-completed{background:var(--blue-lt);color:var(--blue-dk);}.sp-completed::before{background:var(--blue);}
    .t-price{font-weight:700;color:var(--blue-dk);}
    .t-trunc{max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:600;}
</style>

<div class="pg-hd">
    <h1 class="pg-title">All Offers</h1>
    <span class="count-chip">{{ $offers->total() }}</span>
</div>

<div class="filter-bar">
    <form action="{{ route('admin.offers') }}" method="GET">
        <div class="filter-row">
            <div class="f-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
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
                <a href="{{ route('admin.offers') }}" class="btn-cl">Clear</a>
            </div>
        </div>
    </form>
</div>

<div class="t-card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr>
                <th>#</th><th>Service Request</th><th>Provider</th>
                <th>Customer</th><th>Price</th><th>Status</th><th>Date</th>
            </tr></thead>
            <tbody>
                @forelse($offers as $offer)
                    <tr>
                        <td style="color:var(--muted);font-size:12px;">#{{ $offer->id }}</td>
                        <td class="t-trunc" title="{{ $offer->serviceRequest->title }}">{{ $offer->serviceRequest->title ?? '—' }}</td>
                        <td>{{ $offer->provider->name ?? '—' }}</td>
                        <td style="color:var(--muted);">{{ $offer->serviceRequest->user->name ?? '—' }}</td>
                        <td class="t-price">ETB {{ number_format($offer->price,0) }}</td>
                        <td><span class="st-badge sp-{{ $offer->status }}">{{ ucfirst($offer->status) }}</span></td>
                        <td style="color:var(--muted);font-size:12.5px;">{{ $offer->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted);">No offers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($offers->hasPages())
    <div style="margin-top:20px;">{{ $offers->withQueryString()->links() }}</div>
@endif
@endsection
