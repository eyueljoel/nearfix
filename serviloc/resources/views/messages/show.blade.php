@extends('layouts.app')
@section('title', 'Chat with ' . $otherUser->name)

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--r:14px;}
    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:16px;transition:color 0.18s;}
    .back-link:hover{color:var(--blue);}
    .chat-layout{display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start;}
    /* Chat container */
    .chat-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;}
    /* Chat header */
    .chat-hd{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;background:var(--bg);}
    .ch-av{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));color:white;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .ch-name{font-size:14.5px;font-weight:700;color:var(--text);}
    .ch-service{font-size:12px;color:var(--muted);}
    .st-badge{display:inline-flex;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:600;margin-left:auto;white-space:nowrap;}
    .st-open{background:var(--blue-lt);color:var(--blue-dk);}
    .st-assigned{background:var(--yellow-lt);color:var(--yellow);}
    .st-in_progress{background:#fef3c7;color:#92400e;}
    .st-completed{background:var(--green-lt);color:var(--green);}
    .st-cancelled{background:#fef2f2;color:var(--red);}
    /* Message thread */
    .msg-thread{padding:20px;max-height:520px;overflow-y:auto;display:flex;flex-direction:column;gap:16px;}
    .msg-thread::-webkit-scrollbar{width:5px;}
    .msg-thread::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px;}
    .msg-wrap{display:flex;gap:10px;align-items:flex-end;}
    .msg-wrap.mine{flex-direction:row-reverse;}
    .msg-av{width:32px;height:32px;border-radius:50%;color:white;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .msg-av.theirs{background:linear-gradient(135deg,#6366f1,#8b5cf6);}
    .msg-av.mine{background:linear-gradient(135deg,var(--blue),var(--blue-dk));}
    .msg-bubble{max-width:75%;}
    .bubble-name{font-size:11.5px;font-weight:600;color:var(--muted);margin-bottom:3px;}
    .mine .bubble-name{text-align:right;}
    .bubble-body{padding:10px 14px;border-radius:14px;font-size:13.5px;line-height:1.55;word-break:break-word;}
    .theirs .bubble-body{background:var(--bg);color:var(--text);border-bottom-left-radius:4px;}
    .mine .bubble-body{background:var(--blue);color:white;border-bottom-right-radius:4px;}
    .bubble-time{font-size:11px;color:var(--muted);margin-top:3px;}
    .mine .bubble-time{text-align:right;}
    .read-tick{color:var(--green);font-size:11px;}
    /* Empty */
    .no-msgs{text-align:center;padding:40px;color:var(--muted);font-size:13.5px;}
    /* Compose */
    .compose{padding:16px 20px;border-top:1px solid var(--border);background:var(--bg);}
    .compose textarea{width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:10px;font-size:13.5px;font-family:inherit;color:var(--text);background:var(--white);resize:none;outline:none;transition:border-color 0.18s;box-sizing:border-box;min-height:72px;line-height:1.5;}
    .compose textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(14,165,233,0.12);}
    .compose-footer{display:flex;align-items:center;justify-content:space-between;margin-top:8px;}
    .char-count{font-size:12px;color:var(--muted);}
    .btn-send{padding:9px 22px;background:var(--blue);color:white;border:none;border-radius:9px;font-size:13.5px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;display:flex;align-items:center;gap:7px;}
    .btn-send:hover:not(:disabled){background:var(--blue-dk);transform:translateY(-1px);}
    .btn-send:disabled{background:#94a3b8;cursor:not-allowed;}
    /* Sidebar */
    .sidebar-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;margin-bottom:14px;position:sticky;top:80px;}
    .sc-title{font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:12px;}
    .sc-row{display:flex;flex-direction:column;gap:3px;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border);}
    .sc-row:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0;}
    .sc-label{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--muted);}
    .sc-val{font-size:13.5px;font-weight:600;color:var(--text);}
    .sc-budget{font-size:16px;font-weight:800;color:var(--blue-dk);}
    .btn-full{display:block;width:100%;padding:9px;text-align:center;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.18s;margin-top:14px;}
    .btn-full:hover{border-color:var(--blue);color:var(--blue);background:var(--blue-lt);}
    @media(max-width:900px){.chat-layout{grid-template-columns:1fr;}.sidebar-card{position:static;}}
</style>

<a href="{{ route('messages.inbox') }}" class="back-link">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Inbox
</a>

<div class="chat-layout">
    {{-- Chat --}}
    <div>
        <div class="chat-card">
            {{-- Header --}}
            <div class="chat-hd">
                <div class="ch-av">{{ strtoupper(substr($otherUser->name,0,2)) }}</div>
                <div>
                    <div class="ch-name">{{ $otherUser->name }}</div>
                    <div class="ch-service">{{ $serviceRequest->title }}</div>
                </div>
                <span class="st-badge st-{{ $serviceRequest->status }}">{{ ucfirst($serviceRequest->status) }}</span>
            </div>

            {{-- Messages --}}
            <div class="msg-thread" id="msgThread">
                @forelse($messages as $msg)
                    @php $mine = $msg->sender_id === auth()->id(); @endphp
                    <div class="msg-wrap {{ $mine ? 'mine' : 'theirs' }}">
                        <div class="msg-av {{ $mine ? 'mine' : 'theirs' }}">
                            {{ strtoupper(substr($msg->sender->name,0,2)) }}
                        </div>
                        <div class="msg-bubble">
                            <div class="bubble-name">{{ $msg->sender->name }}</div>
                            <div class="bubble-body">{{ $msg->body }}</div>
                            <div class="bubble-time">
                                {{ $msg->created_at->format('M d, g:i A') }}
                                @if($mine && $msg->isReadBy($otherUser))
                                    <span class="read-tick">✓ Read</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-msgs">No messages yet — start the conversation!</div>
                @endforelse
            </div>

            @if($messages->hasPages())
                <div style="padding:12px 20px;border-top:1px solid var(--border);">
                    {{ $messages->links() }}
                </div>
            @endif

            {{-- Compose --}}
            <div class="compose">
                <form action="{{ route('messages.store') }}" method="POST" id="msgForm">
                    @csrf
                    <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                    <input type="hidden" name="recipient_id" value="{{ $otherUser->id }}">
                    <textarea name="body" id="msgBody" placeholder="Type your message…" required
                              oninput="updateCompose(this)"></textarea>
                    @error('body')
                        <div style="font-size:12px;color:var(--red);margin-top:4px;">⚠ {{ $message }}</div>
                    @enderror
                    <div class="compose-footer">
                        <span class="char-count"><span id="charCount">0</span> / 2000</span>
                        <button type="submit" class="btn-send" id="sendBtn" disabled>
                            Send
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        <div class="sidebar-card">
            <div class="sc-title">Request Details</div>
            <div class="sc-row">
                <div class="sc-label">Service</div>
                <div class="sc-val">{{ $serviceRequest->title }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-label">Category</div>
                <div class="sc-val">{{ $serviceRequest->category->name ?? '—' }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-label">Budget</div>
                <div class="sc-budget">ETB {{ number_format($serviceRequest->budget,0) }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-label">Location</div>
                <div class="sc-val">{{ $serviceRequest->location }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-label">Status</div>
                <span class="st-badge st-{{ $serviceRequest->status }}">{{ ucfirst($serviceRequest->status) }}</span>
            </div>
            @if($serviceRequest->scheduled_date)
            <div class="sc-row">
                <div class="sc-label">Preferred Date</div>
                <div class="sc-val">{{ \Carbon\Carbon::parse($serviceRequest->scheduled_date)->format('M d, Y') }}</div>
            </div>
            @endif
            <a href="{{ route('customer.requests.show', $serviceRequest->id) }}" class="btn-full">View Full Request</a>
        </div>
    </div>
</div>

<script>
function updateCompose(el) {
    document.getElementById('charCount').textContent = el.value.length;
    document.getElementById('sendBtn').disabled = el.value.trim().length === 0;
}

// Auto-scroll to latest message
document.addEventListener('DOMContentLoaded', function() {
    const thread = document.getElementById('msgThread');
    if (thread) thread.scrollTop = thread.scrollHeight;
});
</script>
@endsection
