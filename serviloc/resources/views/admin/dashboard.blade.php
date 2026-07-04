@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>👋 Welcome back, {{ auth()->user()->name }}</h1>
        <p>Manage your platform</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon">👤</span>
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">👤</span>
        <div class="stat-label">Customers</div>
        <div class="stat-value">{{ $totalCustomers ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">🛠️</span>
        <div class="stat-label">Providers</div>
        <div class="stat-value">{{ $totalProviders ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">📝</span>
        <div class="stat-label">Total Requests</div>
        <div class="stat-value">{{ $totalRequests ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">💬</span>
        <div class="stat-label">Total Offers</div>
        <div class="stat-value">{{ $totalOffers ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">📂</span>
        <div class="stat-label">Categories</div>
        <div class="stat-value">{{ $totalCategories ?? 0 }}</div>
    </div>
</div>

<!-- Recent Requests -->
<div class="content-card">
    <div class="card-header">
        <h3>📋 Recent Service Requests</h3>
        <a href="#" class="view-all">View All →</a>
    </div>
    
    @if(isset($recentRequests) && $recentRequests->count() > 0)
        @foreach($recentRequests as $request)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f2f5;">
                <div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $request->title }}</div>
                    <div style="font-size: 13px; color: #8895aa;">
                        {{ $request->user->name ?? 'Unknown' }} • {{ $request->category->name ?? 'Uncategorized' }} • {{ $request->location }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="badge badge-{{ $request->status }}">
                        {{ ucfirst($request->status) }}
                    </span>
                    <span style="font-weight: 600; color: #1a1a2e;">
                        ETB {{ number_format($request->budget, 2) }}
                    </span>
                </div>
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 30px 0; color: #8895aa;">
            <p>No requests yet.</p>
        </div>
    @endif
</div>
@endsection