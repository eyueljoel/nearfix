<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServiLoc - Local Service Marketplace')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ── Design Tokens ─────────────────────────────── */
        :root {
            --blue:        #0ea5e9;
            --blue-dark:   #2563eb;
            --blue-light:  #eff6ff;
            --blue-ring:   rgba(14,165,233,0.15);
            --white:       #ffffff;
            --bg:          #f0f2f5;
            --border:      #e8ecf1;
            --text:        #0f172a;
            --text-muted:  #8895aa;
            --text-soft:   #475569;
            --green:       #059669;
            --green-bg:    #ecfdf5;
            --yellow:      #d97706;
            --yellow-bg:   #fffbeb;
            --red:         #dc2626;
            --red-bg:      #fef2f2;
            --sidebar-w:   260px;
            --navbar-h:    64px;
            --radius-sm:   8px;
            --radius:      12px;
            --radius-lg:   16px;
            --radius-xl:   20px;
            --shadow-sm:   0 1px 3px rgba(0,0,0,0.06);
            --shadow:      0 4px 16px rgba(0,0,0,0.07);
            --shadow-lg:   0 12px 40px rgba(0,0,0,0.12);
        }

        /* ── Reset & Base ──────────────────────────────── */
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Navbar ────────────────────────────────────── */
        .navbar {
            height: var(--navbar-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
            gap: 16px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -0.3px;
            flex-shrink: 0;
        }

        .navbar-brand .brand-icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; color: white;
        }

        .navbar-brand .brand-text span { color: var(--blue); }

        .navbar-search {
            flex: 1; max-width: 440px; position: relative;
        }

        .navbar-search form { display: flex; align-items: center; }

        .navbar-search .search-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); pointer-events: none;
        }

        .navbar-search svg { width: 16px; height: 16px; }

        .navbar-search input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            font-size: 13.5px;
            font-family: inherit;
            background: var(--bg);
            color: var(--text);
            transition: all 0.2s;
            outline: none;
        }

        .navbar-search input:focus {
            border-color: var(--blue);
            background: var(--white);
            box-shadow: 0 0 0 3px var(--blue-ring);
        }

        .navbar-search input::placeholder { color: var(--text-muted); }

        .navbar-actions { display: flex; align-items: center; gap: 8px; }

        /* ── Notification button & dropdown ────────────── */
        .notif-wrapper { position: relative; }

        .notification-btn {
            position: relative;
            background: none; border: none;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            border-radius: var(--radius);
            cursor: pointer;
            color: var(--text-soft);
            transition: all 0.2s;
            font-size: 18px;
        }

        .notification-btn:hover { background: var(--blue-light); color: var(--blue); }

        .notification-badge {
            position: absolute; top: 4px; right: 4px;
            background: var(--red); color: white;
            font-size: 10px; font-weight: 700;
            min-width: 16px; height: 16px;
            border-radius: 8px; padding: 0 4px;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--white);
        }

        .notif-dropdown {
            display: none;
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 360px;
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            z-index: 2000; overflow: hidden;
        }

        .notif-dropdown.open { display: block; }

        .notif-dropdown-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            background: var(--bg);
        }

        .notif-dropdown-header h4 { font-size: 14px; font-weight: 700; color: var(--text); }

        .notif-dropdown-header button, .notif-dropdown-header a {
            font-size: 12px; color: var(--blue); font-weight: 600;
            background: none; border: none; cursor: pointer; text-decoration: none;
            padding: 0; font-family: inherit;
        }

        .notif-list { max-height: 340px; overflow-y: auto; }

        .notif-row {
            display: flex; gap: 10px; align-items: flex-start;
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            text-decoration: none; color: inherit;
            transition: background 0.15s;
        }

        .notif-row:hover { background: var(--bg); }
        .notif-row.unread { background: var(--blue-light); }
        .notif-row.unread:hover { background: #dbeafe; }

        .notif-row-icon {
            width: 32px; height: 32px; border-radius: var(--radius-sm);
            background: var(--bg); display: flex; align-items: center;
            justify-content: center; font-size: 16px; flex-shrink: 0; margin-top: 1px;
        }

        .notif-row.unread .notif-row-icon { background: white; }

        .notif-row-title { font-size: 13px; font-weight: 600; color: var(--text); }
        .notif-row-msg   { font-size: 12px; color: var(--text-soft); line-height: 1.4; margin-top: 1px; }
        .notif-row-time  { font-size: 11px; color: var(--text-muted); margin-top: 3px; }

        .notif-dropdown-footer {
            padding: 12px 18px; text-align: center; border-top: 1px solid var(--border);
        }

        .notif-dropdown-footer a {
            color: var(--blue); font-weight: 600; font-size: 13px; text-decoration: none;
        }

        .notif-empty { padding: 28px 18px; text-align: center; color: var(--text-muted); font-size: 13px; }

        /* ── User avatar menu ──────────────────────────── */
        .user-menu {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; padding: 5px 10px 5px 5px;
            border-radius: 50px;
            transition: background 0.2s;
            border: 1.5px solid transparent;
        }

        .user-menu:hover { background: var(--blue-light); border-color: var(--border); }

        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 13px; flex-shrink: 0;
        }

        .user-name  { font-weight: 600; font-size: 13px; color: var(--text); line-height: 1.2; }
        .user-role  { font-size: 11px; color: var(--text-muted); text-transform: capitalize; }

        /* ── Sidebar ───────────────────────────────────── */
        .app-layout {
            display: flex;
            min-height: calc(100vh - var(--navbar-h));
        }

        .sidebar {
            width: var(--sidebar-w);
            background: var(--white);
            border-right: 1px solid var(--border);
            padding: 20px 12px;
            flex-shrink: 0;
            position: sticky;
            top: var(--navbar-h);
            height: calc(100vh - var(--navbar-h));
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .sidebar-section { margin-bottom: 24px; }

        .sidebar-label {
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.9px;
            color: var(--text-muted);
            padding: 0 12px; margin-bottom: 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius);
            color: var(--text-soft);
            text-decoration: none;
            font-weight: 500; font-size: 13.5px;
            transition: all 0.18s ease;
            margin-bottom: 2px;
            white-space: nowrap;
            background: none; border: none; cursor: pointer;
            width: 100%; text-align: left; font-family: inherit;
        }

        .nav-item:hover {
            background: var(--blue-light);
            color: var(--blue-dark);
        }

        .nav-item.active {
            background: var(--blue);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(14,165,233,0.35);
        }

        .nav-icon {
            width: 20px; text-align: center; font-size: 16px; flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--blue-light);
            color: var(--blue-dark);
            padding: 1px 8px;
            border-radius: 20px;
            font-size: 11px; font-weight: 700;
        }

        .nav-item.active .nav-badge {
            background: rgba(255,255,255,0.25);
            color: white;
        }

        .sidebar-divider {
            height: 1px; background: var(--border);
            margin: 16px 8px;
        }

        /* ── Main Content ──────────────────────────────── */
        .main-content {
            flex: 1;
            padding: 28px 32px;
            min-width: 0;
        }

        /* ── Page Header ───────────────────────────────── */
        .page-header {
            display: flex; justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            gap: 16px;
        }

        .page-header h1 {
            font-size: 24px; font-weight: 700;
            color: var(--text); letter-spacing: -0.3px; line-height: 1.25;
        }

        .page-header p {
            color: var(--text-muted); font-size: 14px; margin-top: 3px;
        }

        .btn-primary {
            background: var(--blue);
            color: white;
            padding: 9px 20px;
            border: none; border-radius: var(--radius);
            font-weight: 600; font-size: 13.5px;
            cursor: pointer; transition: all 0.2s;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 7px;
            font-family: inherit; white-space: nowrap;
        }

        .btn-primary:hover {
            background: var(--blue-dark);
            box-shadow: 0 4px 16px rgba(14,165,233,0.35);
            transform: translateY(-1px);
        }

        /* ── Stats Grid ────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--white);
            padding: 20px 22px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--blue-dark));
            opacity: 0;
            transition: opacity 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .stat-card:hover::before { opacity: 1; }

        .stat-card .stat-label {
            font-size: 12px; font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.6px;
        }

        .stat-card .stat-value {
            font-size: 28px; font-weight: 800;
            color: var(--text); margin-top: 6px;
            letter-spacing: -0.5px;
        }

        .stat-card .stat-icon {
            float: right; font-size: 28px; opacity: 0.55;
        }

        /* ── Content Cards ─────────────────────────────── */
        .content-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 22px 24px;
            margin-bottom: 20px;
        }

        .content-card .card-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .content-card .card-header h3 {
            font-size: 15px; font-weight: 700; color: var(--text);
        }

        .content-card .card-header .view-all {
            color: var(--blue); text-decoration: none;
            font-weight: 600; font-size: 13px;
            transition: color 0.2s;
        }

        .content-card .card-header .view-all:hover { color: var(--blue-dark); }

        /* ── Status Badges ─────────────────────────────── */
        .badge {
            padding: 3px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 4px;
        }

        .badge-open       { background: var(--green-bg); color: var(--green); }
        .badge-assigned   { background: var(--blue-light); color: var(--blue-dark); }
        .badge-in_progress{ background: #fef3c7; color: #92400e; }
        .badge-completed  { background: var(--green-bg); color: var(--green); }
        .badge-cancelled  { background: var(--red-bg); color: var(--red); }
        .badge-pending    { background: var(--yellow-bg); color: var(--yellow); }
        .badge-accepted   { background: var(--green-bg); color: var(--green); }
        .badge-rejected   { background: var(--red-bg); color: var(--red); }
        .badge-paid       { background: var(--blue-light); color: var(--blue-dark); }
        .badge-released   { background: var(--green-bg); color: var(--green); }

        /* ── Flash alerts ──────────────────────────────── */
        .alert-success {
            background: var(--green-bg); color: var(--green);
            border: 1px solid #a7f3d0; border-left: 4px solid var(--green);
            border-radius: var(--radius); padding: 12px 16px;
            margin-bottom: 20px; font-size: 13.5px; font-weight: 500;
        }

        .alert-error {
            background: var(--red-bg); color: var(--red);
            border: 1px solid #fecaca; border-left: 4px solid var(--red);
            border-radius: var(--radius); padding: 12px 16px;
            margin-bottom: 20px; font-size: 13.5px; font-weight: 500;
        }

        /* ── Responsive ────────────────────────────────── */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { padding: 20px 16px; }
            .navbar-search { display: none; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .page-header { flex-direction: column; }
            .user-name, .user-role { display: none; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ── Navbar ─────────────────────────────────────────── -->
<nav class="navbar">
    <a href="@php
        $user = auth()->user();
        if ($user && $user->role === 'admin')         echo route('admin.dashboard');
        elseif ($user && $user->role === 'provider')  echo route('provider.dashboard');
        else                                           echo route('customer.dashboard');
    @endphp" class="navbar-brand">
        <div class="brand-icon">🛠️</div>
        <div class="brand-text">Servi<span>Loc</span></div>
    </a>

    <div class="navbar-search">
        <form action="{{ route('search') }}" method="GET">
            <span class="search-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="8.5" cy="8.5" r="5.5"/><path d="M13.5 13.5L18 18"/>
                </svg>
            </span>
            <input type="text" name="q" placeholder="Search services, providers…" value="{{ request('q') }}">
        </form>
    </div>

    <div class="navbar-actions">
        @php
            $notifUnread  = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
            $recentNotifs = auth()->check() ? auth()->user()->notifications()->latest()->take(5)->get() : collect();
        @endphp

        <div class="notif-wrapper" id="notifWrapper">
            <button class="notification-btn" id="notifBtn" onclick="toggleNotifDropdown(event)" title="Notifications">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                @if($notifUnread > 0)
                    <span class="notification-badge">{{ $notifUnread > 9 ? '9+' : $notifUnread }}</span>
                @endif
            </button>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dropdown-header">
                    <h4>Notifications</h4>
                    @if($notifUnread > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Mark all read</button>
                        </form>
                    @else
                        <span style="font-size:12px;color:var(--text-muted);">All caught up ✓</span>
                    @endif
                </div>
                <div class="notif-list">
                    @forelse($recentNotifs as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           class="notif-row {{ is_null($notif->read_at) ? 'unread' : '' }}">
                            <div class="notif-row-icon">
                                @if(($notif->data['type'] ?? '') === 'offer_received')         💰
                                @elseif(($notif->data['type'] ?? '') === 'offer_status_changed')
                                    @if(($notif->data['status'] ?? '') === 'accepted') 🎉 @else ℹ️ @endif
                                @elseif(($notif->data['type'] ?? '') === 'new_message')        💬
                                @elseif(($notif->data['type'] ?? '') === 'payment_received')   💳
                                @else 🔔 @endif
                            </div>
                            <div class="notif-row-body">
                                <div class="notif-row-title">{{ $notif->data['title'] ?? 'Notification' }}</div>
                                <div class="notif-row-msg">{{ \Illuminate\Support\Str::limit($notif->data['message'] ?? '', 65) }}</div>
                                <div class="notif-row-time">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </a>
                    @empty
                        <div class="notif-empty">No notifications yet</div>
                    @endforelse
                </div>
                <div class="notif-dropdown-footer">
                    <a href="{{ route('notifications.index') }}">View all notifications →</a>
                </div>
            </div>
        </div>

        <div class="user-menu" onclick="window.location.href='{{ route('profile.edit') }}'">
            <div class="user-avatar">{{ auth()->user() ? strtoupper(substr(auth()->user()->name,0,2)) : 'U' }}</div>
            <div>
                <div class="user-name">{{ auth()->user()?->name ?? 'Guest' }}</div>
                <div class="user-role">{{ auth()->user()?->role ?? '' }}</div>
            </div>
        </div>
    </div>
</nav>

<!-- ── App Layout ──────────────────────────────────────── -->
<div class="app-layout">

<!-- ── Sidebar ────────────────────────────────────────── -->
<aside class="sidebar">
    @php $user = auth()->user(); @endphp

    @if($user && $user->role === 'admin')
        <div class="sidebar-section">
            <div class="sidebar-label">Admin</div>
            <a href="{{ route('admin.dashboard') }}"       class="nav-item {{ request()->routeIs('admin.dashboard')  ? 'active' : '' }}"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="{{ route('admin.requests') }}"         class="nav-item {{ request()->routeIs('admin.requests*')  ? 'active' : '' }}"><span class="nav-icon">📋</span> All Requests</a>
            <a href="{{ route('admin.offers') }}"           class="nav-item {{ request()->routeIs('admin.offers*')    ? 'active' : '' }}"><span class="nav-icon">🤝</span> All Offers</a>
            <a href="{{ route('admin.reviews') }}"          class="nav-item {{ request()->routeIs('admin.reviews*')   ? 'active' : '' }}"><span class="nav-icon">⭐</span> Reviews</a>
            <a href="{{ route('admin.users') }}"            class="nav-item {{ request()->routeIs('admin.users*')     ? 'active' : '' }}"><span class="nav-icon">👥</span> Users</a>
            <a href="{{ route('payments.admin-overview') }}" class="nav-item {{ request()->routeIs('payments.admin*') ? 'active' : '' }}"><span class="nav-icon">💳</span> Payments</a>
            <a href="{{ route('messages.inbox') }}"         class="nav-item {{ request()->routeIs('messages.*')       ? 'active' : '' }}">
                <span class="nav-icon">💬</span> Messages
                @php $mc = $user ? \App\Models\Message::unreadCountForUser($user) : 0; @endphp
                @if($mc > 0)<span class="nav-badge">{{ $mc }}</span>@endif
            </a>
        </div>

    @elseif($user && $user->role === 'provider')
        <div class="sidebar-section">
            <div class="sidebar-label">Provider</div>
            <a href="{{ route('provider.dashboard') }}"  class="nav-item {{ request()->routeIs('provider.dashboard')  ? 'active' : '' }}"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="{{ route('provider.requests') }}"   class="nav-item {{ request()->routeIs('provider.requests*')  ? 'active' : '' }}"><span class="nav-icon">📋</span> Available Requests</a>
            <a href="{{ route('provider.offers') }}"     class="nav-item {{ request()->routeIs('provider.offers*')    ? 'active' : '' }}">
                <span class="nav-icon">🤝</span> My Offers
                @php $oc = $user->offers()->count(); @endphp
                @if($oc > 0)<span class="nav-badge">{{ $oc }}</span>@endif
            </a>
            <a href="{{ route('provider.reviews') }}"    class="nav-item {{ request()->routeIs('provider.reviews*')   ? 'active' : '' }}"><span class="nav-icon">⭐</span> My Reviews</a>
            <a href="{{ route('portfolio.index') }}"     class="nav-item {{ request()->routeIs('portfolio.*')         ? 'active' : '' }}"><span class="nav-icon">🗂️</span> My Portfolio</a>
            <a href="{{ route('messages.inbox') }}"      class="nav-item {{ request()->routeIs('messages.*')          ? 'active' : '' }}">
                <span class="nav-icon">💬</span> Messages
                @php $mc = $user ? \App\Models\Message::unreadCountForUser($user) : 0; @endphp
                @if($mc > 0)<span class="nav-badge">{{ $mc }}</span>@endif
            </a>
            <a href="{{ route('payments.provider-history') }}" class="nav-item {{ request()->routeIs('payments.provider*') ? 'active' : '' }}"><span class="nav-icon">💰</span> My Earnings</a>
        </div>

    @else
        <div class="sidebar-section">
            <div class="sidebar-label">Customer</div>
            <a href="{{ route('customer.dashboard') }}"  class="nav-item {{ request()->routeIs('customer.dashboard')  ? 'active' : '' }}"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="{{ route('customer.requests') }}"   class="nav-item {{ request()->routeIs('customer.requests*')  ? 'active' : '' }}">
                <span class="nav-icon">📋</span> My Requests
                @php $rc = $user ? $user->serviceRequests()->count() : 0; @endphp
                @if($rc > 0)<span class="nav-badge">{{ $rc }}</span>@endif
            </a>
            <a href="{{ route('customer.offers') }}"     class="nav-item {{ request()->routeIs('customer.offers*')    ? 'active' : '' }}"><span class="nav-icon">🤝</span> Offers Received</a>
            <a href="{{ route('payments.customer-history') }}" class="nav-item {{ request()->routeIs('payments.customer*') ? 'active' : '' }}"><span class="nav-icon">💳</span> My Payments</a>
            <a href="{{ route('customer.reviews') }}"    class="nav-item {{ request()->routeIs('customer.reviews*')   ? 'active' : '' }}"><span class="nav-icon">⭐</span> My Reviews</a>
            <a href="{{ route('messages.inbox') }}"      class="nav-item {{ request()->routeIs('messages.*')          ? 'active' : '' }}">
                <span class="nav-icon">💬</span> Messages
                @php $mc = $user ? \App\Models\Message::unreadCountForUser($user) : 0; @endphp
                @if($mc > 0)<span class="nav-badge">{{ $mc }}</span>@endif
            </a>
        </div>
    @endif

    <div class="sidebar-divider"></div>

    <div class="sidebar-section">
        <div class="sidebar-label">Account</div>
        <a href="{{ route('notifications.index') }}" class="nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <span class="nav-icon">🔔</span> Notifications
            @php $nc = $user ? $user->unreadNotifications()->count() : 0; @endphp
            @if($nc > 0)<span class="nav-badge">{{ $nc }}</span>@endif
        </a>
        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> My Profile
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;font-family:inherit;color:inherit;">
                <span class="nav-icon">🚪</span> Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- ── Main Content ────────────────────────────────────── -->
<main class="main-content">
    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">✕ {{ session('error') }}</div>
    @endif
    @yield('content')
</main>
</div>

@stack('scripts')
<script>
function toggleNotifDropdown(e) {
    e.stopPropagation();
    document.getElementById('notifDropdown').classList.toggle('open');
}
document.addEventListener('click', function(e) {
    const w = document.getElementById('notifWrapper');
    if (w && !w.contains(e.target)) {
        document.getElementById('notifDropdown')?.classList.remove('open');
    }
});
</script>
</body>
</html>
