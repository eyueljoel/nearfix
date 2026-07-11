@extends('layouts.app')
@section('title', 'Messages')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--red:#dc2626;--r:14px;}
    .pg-hd{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;margin-bottom:20px;}
    .inbox-list{background:var(--white);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;}
    .conv-row{display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);text-decoration:none;color:inherit;transition:background 0.15s;}
    .conv-row:last-child{border-bottom:none;}
    .conv-row:hover{background:var(--bg);}
    .conv-row.unread{background:var(--blue-lt);}
    .conv-row.unread:hover{background:#dbeafe;}
    .c-av{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));color:white;font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .c-body{flex:1;min-width:0;}
    .c-name{font-size:14px;font-weight:700;color:var(--text);margin-bottom:2px;}
    .c-service{font-size:12px;color:var(--blue-dk);font-weight:500;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .c-preview{font-size:13px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .unread .c-preview{color:var(--text);font-weight:500;}
    .c-right{text-align:right;flex-shrink:0;}
    .c-time{font-size:12px;color:var(--muted);margin-bottom:6px;}
    .unread-badge{display:inline-flex;align-items:center;justify-content:center;min-width:20px;height:20px;background:var(--blue);color:white;font-size:11px;font-weight:700;border-radius:10px;padding:0 6px;}
    .read-tick{font-size:14px;color:var(--green);}
    .empty-state{background:var(--white);border:1.5px dashed var(--border);border-radius:var(--r);padding:64px 24px;text-align:center;}
    .empty-state h3{font-size:17px;font-weight:700;color:var(--text);margin-bottom:6px;}
    .empty-state p{font-size:13.5px;color:var(--muted);margin-bottom:20px;}
    .btn-go{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;background:var(--blue);color:white;border-radius:var(--r);font-size:13px;font-weight:600;text-decoration:none;transition:background 0.18s;}
    .btn-go:hover{background:var(--blue-dk);}
</style>

<h1 class="pg-hd">Messages</h1>

@if($conversations->count() > 0)
    <div class="inbox-list">
        @foreach($conversations as $conv)
            <a href="{{ route('messages.show', $conv->service_request_id) }}"
               class="conv-row {{ $conv->unread_count > 0 ? 'unread' : '' }}">
                <div class="c-av">{{ strtoupper(substr($conv->other_user->name, 0, 2)) }}</div>
                <div class="c-body">
                    <div class="c-name">{{ $conv->other_user->name }}</div>
                    <div class="c-service">📋 {{ $conv->service_request->title }}</div>
                    <div class="c-preview">{{ $conv->last_message_preview }}</div>
                </div>
                <div class="c-right">
                    <div class="c-time">{{ $conv->last_message_at->diffForHumans(null, true, true) }}</div>
                    @if($conv->unread_count > 0)
                        <span class="unread-badge">{{ $conv->unread_count }}</span>
                    @else
                        <span class="read-tick">✓</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    @if($conversations->hasPages())
        <div style="margin-top:20px;">{{ $conversations->links() }}</div>
    @endif
@else
    <div class="empty-state">
        <div style="width:56px;height:56px;background:var(--blue-lt);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px;">💬</div>
        <h3>No conversations yet</h3>
        <p>Messages with customers and providers will appear here.</p>
        @if(auth()->user()->role === 'customer')
            <a href="{{ route('customer.requests') }}" class="btn-go">View My Requests</a>
        @else
            <a href="{{ route('provider.requests') }}" class="btn-go">Browse Requests</a>
        @endif
    </div>
@endif
@endsection
