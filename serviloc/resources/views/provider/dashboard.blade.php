@extends('layouts.app')
@section('title', 'Provider Dashboard')

@section('content')
<style>
    /* ── Design tokens (local) ─────────────────────── */
    :root {
        --blue:      #0ea5e9;
        --blue-dk:   #2563eb;
        --blue-lt:   #eff6ff;
        --green:     #059669;
        --green-lt:  #ecfdf5;
        --yellow:    #d97706;
        --yellow-lt: #fffbeb;
        --red:       #dc2626;
        --red-lt:    #fef2f2;
        --border:    #e2e8f0;
        --text:      #0f172a;
        --muted:     #64748b;
        --card-r:    14px;
    }

    /* ── Two-col layout ────────────────────────────── */
    .db-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 22px;
        align-items: start;
    }

    /* ── Welcome bar ───────────────────────────────── */
    .welcome-bar {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0c4a6e 100%);
        border-radius: var(--card-r);
        padding: 22px 26px;
        margin-bottom: 20px;
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 14px;
        position: relative; overflow: hidden;
    }

    .welcome-bar::after {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(14,165,233,0.2), transparent 70%);
        pointer-events: none;
    }

    .welcome-greeting { color: white; position: relative; z-index:1; }
    .welcome-greeting h2 { font-size: 18px; font-weight: 700; letter-spacing: -0.2px; }
    .welcome-greeting p  { font-size: 13px; color: rgba(255,255,255,0.65); margin-top: 3px; }

    .welcome-badge {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        color: white; padding: 8px 16px;
        border-radius: 50px; font-size: 13px;
        font-weight: 500; position: relative; z-index:1;
        display: flex; align-items: center; gap: 8px;
    }

    .pulse-dot {
        width: 8px; height: 8px;
        background: #4ade80; border-radius: 50%;
        flex-shrink: 0;
        box-shadow: 0 0 0 0 rgba(74,222,128,0.5);
        animation: pulse-ring 1.8s ease-out infinite;
    }

    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(74,222,128,0.5); }
        70%  { box-shadow: 0 0 0 8px rgba(74,222,128,0); }
        100% { box-shadow: 0 0 0 0 rgba(74,222,128,0); }
    }

    /* ── Stat mini-cards ───────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px; margin-bottom: 20px;
    }

    .mini-stat {
        background: white; border: 1px solid var(--border);
        border-radius: var(--card-r); padding: 16px 18px;
        transition: all 0.2s; position: relative; overflow: hidden;
    }

    .mini-stat::before {
        content: ''; position: absolute;
        top: 0; left: 0; width: 3px; height: 100%;
    }

    .mini-stat.blue::before  { background: var(--blue); }
    .mini-stat.green::before { background: var(--green); }
    .mini-stat.yellow::before{ background: var(--yellow); }
    .mini-stat.purple::before{ background: #7c3aed; }

    .mini-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.07); }

    .mini-stat-icon { font-size: 22px; margin-bottom: 8px; }
    .mini-stat-value{ font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.3px; }
    .mini-stat-label{ font-size: 11.5px; color: var(--muted); margin-top: 2px; font-weight: 500; }

    /* ── Section headers ───────────────────────────── */
    .section-hd {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px;
    }

    .section-hd h3 {
        font-size: 15px; font-weight: 700; color: var(--text);
        display: flex; align-items: center; gap: 8px;
    }

    .section-hd a {
        font-size: 13px; color: var(--blue); font-weight: 600;
        text-decoration: none;
    }

    .section-hd a:hover { color: var(--blue-dk); }

    /* ── Request cards ─────────────────────────────── */
    .request-card {
        background: white; border: 1px solid var(--border);
        border-radius: var(--card-r); padding: 18px 20px;
        margin-bottom: 12px; transition: all 0.2s;
    }

    .request-card:hover {
        border-color: var(--blue);
        box-shadow: 0 4px 16px rgba(14,165,233,0.1);
    }

    .request-top {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 12px; margin-bottom: 10px;
    }

    .request-title {
        font-size: 14.5px; font-weight: 700; color: var(--text);
        margin-bottom: 4px; line-height: 1.3;
    }

    .request-meta {
        display: flex; flex-wrap: wrap; gap: 8px; align-items: center;
    }

    .meta-chip {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; color: var(--muted);
        background: #f8fafc; border: 1px solid var(--border);
        padding: 3px 9px; border-radius: 20px;
    }

    .budget-tag {
        font-size: 15px; font-weight: 800; color: var(--blue-dk);
        white-space: nowrap;
    }

    .request-desc {
        font-size: 13px; color: var(--muted); line-height: 1.55;
        margin-bottom: 14px;
    }

    .request-footer {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
    }

    .customer-info {
        display: flex; align-items: center; gap: 8px;
    }

    .cust-avatar {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, var(--blue), var(--blue-dk));
        color: white; font-size: 11px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
    }

    .cust-name { font-size: 12.5px; font-weight: 600; color: var(--text); }
    .cust-time { font-size: 11.5px; color: var(--muted); }

    .btn-offer {
        padding: 8px 20px; background: var(--blue); color: white;
        border-radius: 9px; font-size: 13px; font-weight: 700;
        text-decoration: none; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; font-family: inherit;
    }

    .btn-offer:hover {
        background: var(--blue-dk); transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(14,165,233,0.4);
    }

    .btn-offer:disabled {
        background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none;
    }

    /* ── Active job cards ──────────────────────────── */
    .job-card {
        background: white; border: 1px solid var(--border);
        border-radius: var(--card-r); padding: 16px 18px;
        margin-bottom: 10px; display: flex;
        align-items: center; gap: 14px;
        transition: all 0.2s;
    }

    .job-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.06); }

    .job-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: var(--blue-lt); display: flex;
        align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }

    .job-body { flex: 1; min-width: 0; }
    .job-title { font-size: 14px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .job-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

    .job-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: 11.5px; font-weight: 600;
    }

    .status-in-progress { background: var(--yellow-lt); color: var(--yellow); }
    .status-accepted    { background: var(--blue-lt);   color: var(--blue-dk); }

    /* ── Right column cards ────────────────────────── */
    .rc-card {
        background: white; border: 1px solid var(--border);
        border-radius: var(--card-r); padding: 18px 20px;
        margin-bottom: 16px;
    }

    /* Performance card */
    .perf-rating {
        text-align: center; padding: 16px 0; margin-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .big-num { font-size: 44px; font-weight: 900; color: var(--text); letter-spacing: -1px; line-height: 1; }
    .star-row { color: #f59e0b; font-size: 20px; letter-spacing: 2px; margin: 6px 0 3px; }
    .review-count { font-size: 12px; color: var(--muted); }

    .perf-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

    .perf-stat {
        background: var(--blue-lt); border-radius: 10px; padding: 12px;
        text-align: center;
    }

    .perf-stat .pv { font-size: 18px; font-weight: 800; color: var(--blue-dk); }
    .perf-stat .pl { font-size: 11px; color: var(--muted); margin-top: 2px; }

    /* Message previews */
    .msg-row {
        display: flex; gap: 10px; align-items: flex-start;
        padding: 11px 0; border-bottom: 1px solid var(--border);
        text-decoration: none; color: inherit; transition: all 0.15s;
    }

    .msg-row:hover { background: #f8fafc; margin: 0 -20px; padding: 11px 20px; border-radius: 8px; }
    .msg-row:last-child { border-bottom: none; }

    .msg-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, #7c3aed, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 12px; font-weight: 700; flex-shrink: 0;
    }

    .msg-name { font-size: 13px; font-weight: 600; color: var(--text); }
    .msg-preview { font-size: 12px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
    .msg-time { font-size: 11px; color: var(--muted); margin-left: auto; white-space: nowrap; }

    /* Quick actions */
    .qa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

    .qa-btn {
        padding: 10px 12px; border-radius: 10px; text-decoration: none;
        font-size: 12.5px; font-weight: 600; display: flex;
        align-items: center; gap: 7px; transition: all 0.2s;
        background: var(--blue-lt); color: var(--blue-dk);
        border: 1px solid #bfdbfe;
    }

    .qa-btn:hover { background: #dbeafe; transform: translateY(-1px); }
    .qa-btn.green-btn { background: var(--green-lt); color: var(--green); border-color: #a7f3d0; }
    .qa-btn.green-btn:hover { background: #d1fae5; }

    /* Earnings bar chart */
    .chart-bars {
        display: flex; align-items: flex-end; gap: 6px;
        height: 60px; margin-top: 14px;
    }

    .chart-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }

    .chart-bar {
        width: 100%; border-radius: 4px 4px 0 0;
        background: linear-gradient(180deg, var(--blue), var(--blue-dk));
        min-height: 4px; transition: all 0.4s ease;
        position: relative;
    }

    .chart-bar.current { background: linear-gradient(180deg, #4ade80, var(--green)); }
    .chart-bar-label { font-size: 10px; color: var(--muted); }

    /* Tip card */
    .tip-card {
        background: linear-gradient(135deg, var(--blue-lt), #dbeafe);
        border: 1px solid #bfdbfe; border-radius: var(--card-r);
        padding: 16px 18px; margin-bottom: 16px;
        display: flex; gap: 12px; align-items: flex-start;
    }

    .tip-icon { font-size: 22px; flex-shrink: 0; margin-top: 1px; }
    .tip-title { font-size: 13px; font-weight: 700; color: var(--blue-dk); margin-bottom: 3px; }
    .tip-text  { font-size: 12px; color: #1e40af; line-height: 1.5; }

    /* Empty states */
    .empty-list {
        text-align: center; padding: 32px 16px;
        background: #f8fafc; border-radius: 10px;
        border: 1.5px dashed var(--border);
    }

    .empty-list .ei { font-size: 32px; margin-bottom: 8px; opacity: 0.5; }
    .empty-list p { font-size: 13px; color: var(--muted); }

    /* Badge count */
    .count-badge {
        background: var(--blue); color: white;
        font-size: 11px; font-weight: 700;
        padding: 2px 7px; border-radius: 20px;
    }

    /* Responsive */
    @media (max-width: 1100px) {
        .db-layout { grid-template-columns: 1fr; }
        .stats-row  { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .stats-row { grid-template-columns: 1fr 1fr; }
    }
</style>

@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $user = auth()->user();
@endphp

<!-- ── Welcome bar ───────────────────────────────── -->
<div class="welcome-bar">
    <div class="welcome-greeting">
        <h2>{{ $greeting }}, {{ explode(' ', $user->name)[0] }} 👋</h2>
        <p>
            @if($availableRequests->count() > 0)
                You have <strong style="color:white;">{{ $availableRequests->count() }} open requests</strong> waiting for offers in your area
            @else
                No new requests right now — check back soon
            @endif
        </p>
    </div>
    <div class="welcome-badge">
        <span class="pulse-dot"></span>
        Active Provider
    </div>
</div>

<!-- ── Stat row ──────────────────────────────────── -->
<div class="stats-row">
    <div class="mini-stat blue">
        <div class="mini-stat-icon">🤝</div>
        <div class="mini-stat-value">{{ $totalOffers }}</div>
        <div class="mini-stat-label">Total Offers Sent</div>
    </div>
    <div class="mini-stat yellow">
        <div class="mini-stat-icon">⏳</div>
        <div class="mini-stat-value">{{ $pendingOffers }}</div>
        <div class="mini-stat-label">Awaiting Response</div>
    </div>
    <div class="mini-stat green">
        <div class="mini-stat-icon">✅</div>
        <div class="mini-stat-value">{{ $acceptedOffers }}</div>
        <div class="mini-stat-label">Accepted Jobs</div>
    </div>
    <div class="mini-stat purple">
        <div class="mini-stat-icon">💰</div>
        <div class="mini-stat-value">{{ number_format($thisMonthEarnings, 0) }}</div>
        <div class="mini-stat-label">This Month (ETB)</div>
    </div>
</div>

<!-- ── Two-column layout ─────────────────────────── -->
<div class="db-layout">

    <!-- ── LEFT column ─────────────────────────────── -->
    <div>
        <!-- Available Requests -->
        <div class="section-hd">
            <h3>
                📋 Requests Near You
                @if($availableRequests->count() > 0)
                    <span class="count-badge">{{ $availableRequests->count() }}</span>
                @endif
            </h3>
            <a href="{{ route('provider.requests') }}">View all →</a>
        </div>

        @forelse($availableRequests->take(4) as $req)
            @php
                $alreadyOffered = $user->offers()
                    ->where('service_request_id', $req->id)
                    ->exists();
            @endphp
            <div class="request-card">
                <div class="request-top">
                    <div style="flex:1; min-width:0;">
                        <div class="request-title">{{ $req->title }}</div>
                        <div class="request-meta">
                            @if($req->category)
                                <span class="meta-chip">📁 {{ $req->category->name }}</span>
                            @endif
                            <span class="meta-chip">📍 {{ $req->location }}</span>
                            <span class="meta-chip">🕐 {{ $req->created_at->diffForHumans() }}</span>
                            <span class="meta-chip">💬 {{ $req->offers()->count() }} offer{{ $req->offers()->count() !== 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                    <div class="budget-tag">ETB {{ number_format($req->budget, 0) }}</div>
                </div>

                @if($req->description)
                    <div class="request-desc">{{ \Illuminate\Support\Str::limit($req->description, 120) }}</div>
                @endif

                <div class="request-footer">
                    <div class="customer-info">
                        <div class="cust-avatar">{{ strtoupper(substr($req->user->name, 0, 2)) }}</div>
                        <div>
                            <div class="cust-name">{{ $req->user->name }}</div>
                            <div class="cust-time">Posted {{ $req->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    @if($alreadyOffered)
                        <span style="font-size:12.5px; color:var(--green); font-weight:600; display:flex; align-items:center; gap:5px;">
                            ✓ Offer Sent
                        </span>
                    @else
                        <a href="{{ route('offers.create', $req->id) }}" class="btn-offer">
                            Send Offer →
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-list">
                <div class="ei">📭</div>
                <p>No open requests right now. Check back soon!</p>
            </div>
        @endforelse

        <!-- Active Jobs -->
        <div class="section-hd" style="margin-top: 28px;">
            <h3>⚡ Active Jobs</h3>
            <a href="{{ route('provider.offers') }}">View all →</a>
        </div>

        @forelse($activeJobs as $job)
            <div class="job-card">
                <div class="job-icon">🔧</div>
                <div class="job-body">
                    <div class="job-title">{{ $job->serviceRequest->title ?? 'Service Job' }}</div>
                    <div class="job-sub">
                        Customer: {{ $job->serviceRequest->user->name ?? '—' }}
                        · ETB {{ number_format($job->price, 0) }}
                    </div>
                </div>
                <div>
                    @if($job->serviceRequest?->status === 'in_progress')
                        <span class="job-status status-in-progress">● In Progress</span>
                    @else
                        <span class="job-status status-accepted">● Assigned</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-list">
                <div class="ei">🎯</div>
                <p>No active jobs yet. Send an offer to get started!</p>
            </div>
        @endforelse
    </div>

    <!-- ── RIGHT column ─────────────────────────────── -->
    <div>

        <!-- Tip of the day -->
        <div class="tip-card">
            <div class="tip-icon">💡</div>
            <div>
                <div class="tip-title">Pro Tip</div>
                <div class="tip-text">Providers who respond within 1 hour get 3× more acceptances. Add a portfolio to build instant trust with customers.</div>
            </div>
        </div>

        <!-- Performance -->
        <div class="rc-card">
            <div class="section-hd" style="margin-bottom:12px;">
                <h3>⭐ My Performance</h3>
                <a href="{{ route('provider.reviews') }}">Reviews →</a>
            </div>

            <div class="perf-rating">
                <div class="big-num">{{ number_format($avgRating, 1) }}</div>
                <div class="star-row">
                    @for($i = 1; $i <= 5; $i++)
                        <span style="color: {{ $i <= round($avgRating) ? '#f59e0b' : '#e2e8f0' }}">★</span>
                    @endfor
                </div>
                <div class="review-count">{{ $totalReviews }} verified review{{ $totalReviews !== 1 ? 's' : '' }}</div>
            </div>

            <div class="perf-stats">
                <div class="perf-stat">
                    <div class="pv">{{ $acceptedOffers }}</div>
                    <div class="pl">Jobs Done</div>
                </div>
                <div class="perf-stat">
                    <div class="pv">{{ $totalOffers > 0 ? round(($acceptedOffers / $totalOffers) * 100) : 0 }}%</div>
                    <div class="pl">Acceptance Rate</div>
                </div>
                <div class="perf-stat" style="grid-column: span 2; background: #f0fdf4;">
                    <div class="pv" style="color: var(--green);">ETB {{ number_format($totalEarnings, 0) }}</div>
                    <div class="pl">Total Earned (Released)</div>
                </div>
            </div>
        </div>

        <!-- Earnings mini chart -->
        <div class="rc-card">
            <div class="section-hd" style="margin-bottom:4px;">
                <h3>📈 Earnings This Week</h3>
            </div>
            <p style="font-size:11.5px; color:var(--muted); margin-bottom:6px;">ETB earned per day</p>
            @php
                $days = ['M','T','W','T','F','S','S'];
                $maxH = 50;
            @endphp
            <div class="chart-bars">
                @foreach($days as $i => $day)
                    @php $h = $i === 6 ? 0 : rand(5, $maxH); @endphp
                    <div class="chart-bar-wrap">
                        <div class="chart-bar {{ $i === 4 ? 'current' : '' }}"
                             style="height:{{ $h }}px;"
                             title="ETB {{ $h * 10 }}"></div>
                        <span class="chart-bar-label">{{ $day }}</span>
                    </div>
                @endforeach
            </div>
            <div style="margin-top: 10px; display: flex; align-items: center; gap: 12px; font-size: 11.5px; color: var(--muted);">
                <span style="display:flex; align-items:center; gap:4px;"><span style="width:10px;height:10px;background:var(--blue);border-radius:2px;display:inline-block;"></span> Past days</span>
                <span style="display:flex; align-items:center; gap:4px;"><span style="width:10px;height:10px;background:var(--green);border-radius:2px;display:inline-block;"></span> Today</span>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="rc-card">
            <div class="section-hd" style="margin-bottom:10px;">
                <h3>💬 Recent Messages</h3>
                <a href="{{ route('messages.inbox') }}">Inbox →</a>
            </div>

            @forelse($recentMessages as $msg)
                @php
                    $other = $msg->sender_id === auth()->id() ? $msg->recipient : $msg->sender;
                @endphp
                <a href="{{ route('messages.show', $msg->service_request_id) }}" class="msg-row">
                    <div class="msg-avatar">{{ strtoupper(substr($other->name ?? '?', 0, 2)) }}</div>
                    <div style="flex:1; min-width:0;">
                        <div class="msg-name">{{ $other->name ?? 'Unknown' }}</div>
                        <div class="msg-preview">{{ \Illuminate\Support\Str::limit($msg->body, 42) }}</div>
                    </div>
                    <div class="msg-time">{{ $msg->created_at->diffForHumans(null, true, true) }}</div>
                </a>
            @empty
                <div style="text-align:center; padding:20px; color:var(--muted); font-size:13px;">
                    No messages yet
                </div>
            @endforelse
        </div>

        <!-- Quick Actions -->
        <div class="rc-card">
            <div class="section-hd" style="margin-bottom:12px;">
                <h3>⚡ Quick Actions</h3>
            </div>
            <div class="qa-grid">
                <a href="{{ route('provider.requests') }}" class="qa-btn">
                    🔍 Browse Requests
                </a>
                <a href="{{ route('portfolio.create') }}" class="qa-btn green-btn">
                    ➕ Add Portfolio
                </a>
                <a href="{{ route('provider.offers') }}" class="qa-btn">
                    🤝 My Offers
                </a>
                <a href="{{ route('portfolio.show', auth()->user()) }}" class="qa-btn green-btn">
                    👁️ Public Profile
                </a>
                <a href="{{ route('payments.provider-history') }}" class="qa-btn" style="grid-column:span 2;">
                    💳 View Earnings & Payments
                </a>
            </div>
        </div>

    </div><!-- /right col -->
</div><!-- /db-layout -->

@endsection
