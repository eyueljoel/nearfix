<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServiLoc - Local Service Marketplace')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ===== FONTS & BASE ===== */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            min-height: 100vh;
        }
        
        /* ===== TOP NAVBAR ===== */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e8ecf1;
            padding: 0 32px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 800;
            color: #1a1a2e;
            text-decoration: none;
        }
        
        .navbar-brand span {
            color: #e94560;
        }
        
        .navbar-search {
            flex: 1;
            max-width: 480px;
            margin: 0 40px;
            position: relative;
        }
        
        .navbar-search input {
            width: 100%;
            padding: 10px 16px 10px 44px;
            border: 2px solid #e8ecf1;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .navbar-search input:focus {
            border-color: #e94560;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(233, 69, 96, 0.06);
            outline: none;
        }
        
        .navbar-search .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8895aa;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .navbar-actions .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #555;
            transition: color 0.3s;
        }
        
        .navbar-actions .notification-btn:hover {
            color: #e94560;
        }
        
        .navbar-actions .notification-badge {
            position: absolute;
            top: -4px;
            right: -6px;
            background: #e94560;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50%;
            min-width: 18px;
            text-align: center;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 6px 12px 6px 6px;
            border-radius: 50px;
            transition: background 0.3s;
        }
        
        .user-menu:hover {
            background: #f0f2f5;
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #1a1a2e;
        }
        
        .user-role {
            font-size: 12px;
            color: #8895aa;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        /* ===== SIDEBAR ===== */
        .app-layout {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e8ecf1;
            padding: 24px 16px;
            flex-shrink: 0;
            position: sticky;
            top: 70px;
            height: calc(100vh - 70px);
            overflow-y: auto;
        }
        
        .sidebar .sidebar-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8895aa;
            padding: 0 12px;
            margin-bottom: 12px;
        }
        
        .sidebar .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }
        
        .sidebar .nav-item:hover {
            background: #f0f2f5;
            color: #1a1a2e;
        }
        
        .sidebar .nav-item.active {
            background: #e94560;
            color: white;
            box-shadow: 0 4px 12px rgba(233, 69, 96, 0.25);
        }
        
        .sidebar .nav-item .nav-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }
        
        .sidebar .nav-item .nav-badge {
            margin-left: auto;
            background: #e8ecf1;
            color: #555;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        
        .sidebar .nav-item.active .nav-badge {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            padding: 32px;
            max-width: calc(100% - 260px);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
        }
        
        .page-header p {
            color: #666;
            font-size: 15px;
            margin-top: 4px;
        }
        
        .page-header .btn-primary {
            background: #e94560;
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .page-header .btn-primary:hover {
            background: #c73652;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 69, 96, 0.3);
        }
        
        /* ===== STATS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #e8ecf1;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        }
        
        .stat-card .stat-label {
            font-size: 13px;
            font-weight: 500;
            color: #8895aa;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            margin-top: 6px;
        }
        
        .stat-card .stat-icon {
            float: right;
            font-size: 32px;
            opacity: 0.6;
        }
        
        /* ===== CONTENT CARDS ===== */
        .content-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e8ecf1;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .content-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .content-card .card-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
        }
        
        .content-card .card-header .view-all {
            color: #e94560;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* ===== STATUS BADGES ===== */
        .badge {
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-open {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .badge-assigned {
            background: #e3f2fd;
            color: #0d47a1;
        }
        
        .badge-completed {
            background: #f1f8e9;
            color: #33691e;
        }
        
        .badge-cancelled {
            background: #fbe9e7;
            color: #bf360c;
        }
        
        .badge-pending {
            background: #fff3e0;
            color: #e65100;
        }
        
        .badge-accepted {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .badge-rejected {
            background: #fce4ec;
            color: #c62828;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-content {
                max-width: 100%;
                padding: 20px;
            }
            .navbar-search {
                display: none;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            .navbar {
                padding: 0 16px;
            }
            .user-name, .user-role {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
    <a href="{{ route('customer.dashboard') }}" class="navbar-brand">
        🛠️ Servi<span>Loc</span>
    </a>
    
    <div class="navbar-search">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Search services, providers..." id="globalSearch">
    </div>
    
    <div class="navbar-actions">
        <button class="notification-btn">
            🔔
            <span class="notification-badge">3</span>
        </button>
        
        <div class="user-menu" onclick="window.location.href='{{ route('profile.edit') }}'">
            <div class="user-avatar">
                {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'U' }}
            </div>
            <div>
                <div class="user-name">{{ auth()->user() ? auth()->user()->name : 'Guest' }}</div>
                <div class="user-role">{{ auth()->user() ? auth()->user()->role : 'Guest' }}</div>
            </div>
        </div>
    </div>
</nav>

<!-- ===== APP LAYOUT ===== -->
<div class="app-layout">
    
 <!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
    <div class="sidebar-label">Main Menu</div>
    
    @php $user = auth()->user(); @endphp
    
    @if($user && $user->role === 'admin')
        <!-- ===== ADMIN MENU ===== -->
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span>
            Dashboard
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">👤</span>
            Users
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">📝</span>
            All Requests
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">💬</span>
            All Offers
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">📂</span>
            Categories
        </a>
        
    @elseif($user && $user->role === 'provider')
        <!-- ===== PROVIDER MENU ===== -->
        <a href="{{ route('provider.dashboard') }}" class="nav-item {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span>
            Dashboard
        </a>
        <a href="{{ route('provider.dashboard') }}" class="nav-item">
            <span class="nav-icon">📋</span>
            Available Requests
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">💬</span>
            My Offers
            <span class="nav-badge">{{ $user->offers()->count() }}</span>
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">✅</span>
            Accepted Jobs
        </a>
        
    @else
        <!-- ===== CUSTOMER MENU ===== -->
        <a href="{{ route('customer.dashboard') }}" class="nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span>
            Dashboard
        </a>
        <a href="{{ route('customer.requests') }}" class="nav-item {{ request()->routeIs('customer.requests*') ? 'active' : '' }}">
            <span class="nav-icon">📝</span>
            My Requests
            <span class="nav-badge">{{ $user ? $user->serviceRequests()->count() : 0 }}</span>
        </a>
        <a href="{{ route('offers.index') }}" class="nav-item {{ request()->routeIs('offers*') ? 'active' : '' }}">
            <span class="nav-icon">💬</span>
            Offers Received
            <span class="nav-badge">{{ $user ? $user->serviceRequests()->with('offers')->get()->sum(function($r) { return $r->offers->count(); }) : 0 }}</span>
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">⭐</span>
            My Reviews
        </a>
    @endif
    
    <div style="margin-top: 24px; border-top: 1px solid #e8ecf1; padding-top: 16px;">
        <div class="sidebar-label">Account</div>
        
        <a href="{{ route('profile.edit') }}" class="nav-item">
            <span class="nav-icon">👤</span>
            Profile Settings
        </a>
        
        <a href="{{ route('logout') }}" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="nav-icon">🚪</span>
            Logout
        </a>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>
    
    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>