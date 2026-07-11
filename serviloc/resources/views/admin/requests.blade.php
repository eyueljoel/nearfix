@extends('layouts.app')
@section('title', 'All Requests')

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
    .f-field select,.f-field input{width:100%;padding:8px 11px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text);background:var(--bg);font-family:inherit;outline:none;transition:border-color 0.18s;}
    .f-field select:focus,.f-field input:focus{border-color:var(--blue);}
    .btn-f{padding:8px 18px;background:var(--blue);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;white-space:nowrap;}
    .btn-f:hover{background:var(--blue-dk);}
    .btn-cl{padding:8px 13px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;white-space:nowrap;}
    .btn-cl:hover{border-color:var(--muted);color:var(--text);}
    .data-table{width:100%;border-collapse:collapse;}
    .data-table th{text-align:left;padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--muted);border-bottom:2px solid var(--border);background:var(--bg);white-space:nowrap;}
    .data-table td{padding:13px 16px;font-size:13.5px;color:var(--text);border-bottom:1px solid var(--border);vertical-align:middle;}
    .data-table tr:last-child td{border-bottom:none;}
    .data-table tr:hover td{background:#fafbfc;}
    .t-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;}
    .t-card .overflow{overflow-x:auto;}
    .st-badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;}
    .st-open{background:var(--blue-lt);color:var(--blue-dk);}
    .st-assigned{background:var(--yellow-lt);color:var(--yellow);}
    .st-in_progress{background:#fef3c7;color:#92400e;}
    .st-completed{background:var(--green-lt);color:var(--green);}
    .st-cancelled{background:#fef2f2;color:var(--red);}
    .t-title-cell{max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:600;}
    .t-budget{font-weight:700;color:var(--blue-dk);}
    .empty-cell{text-align:center;padding:40px;color:var(--muted);font-size:14px;}
</style>

<div class="pg-hd">
    <div class="pg-hd-l">
        <h1 class="pg-title">All Requests</h1>
        <span class="count-chip">{{ $requests->total() }}</span>
    </div>
</div>

<div class="filter-bar">
    <form action="{{ route('admin.requests') }}" method="GET">
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
                <label>Category</label>
                <select name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category')==$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
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
                <a href="{{ route('admin.requests') }}" class="btn-cl">Clear</a>
            </div>
        </div>
    </form>
</div>

<div class="t-card">
    <div class="overflow">
        <table class="data-table">
            <thead><tr>
                <th>#</th><th>Title</th><th>Customer</th><th>Category</th>
                <th>Budget</th><th>Offers</th><th>Status</th><th>Posted</th>
            </tr></thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td style="color:var(--muted);font-size:12px;">#{{ $req->id }}</td>
                        <td class="t-title-cell" title="{{ $req->title }}">{{ $req->title }}</td>
                        <td>{{ $req->user->name ?? '—' }}<br><span style="font-size:11.5px;color:var(--muted);">{{ $req->location }}</span></td>
                        <td style="color:var(--muted);">{{ $req->category->name ?? '—' }}</td>
                        <td class="t-budget">ETB {{ number_format($req->budget,0) }}</td>
                        <td style="font-weight:600;color:var(--text);">{{ $req->offers_count ?? $req->offers->count() }}</td>
                        <td><span class="st-badge st-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                        <td style="color:var(--muted);font-size:12.5px;">{{ $req->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty-cell">No requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($requests->hasPages())
    <div style="margin-top:20px;">{{ $requests->withQueryString()->links() }}</div>
@endif
@endsection
