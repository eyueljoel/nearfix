@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>👋 Welcome back, {{ auth()->user()->name }}</h1>
        <p>Here's what's happening with your service requests</p>
    </div>
    <a href="{{ route('customer.requests.create') }}" class="btn-primary">
        ➕ Post New Request
    </a>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon">📋</span>
        <div class="stat-label">Total Requests</div>
        <div class="stat-value">{{ $totalRequests }}</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-icon">🟢</span>
        <div class="stat-label">Open Requests</div>
        <div class="stat-value">{{ $openRequests }}</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-icon">✅</span>
        <div class="stat-label">Completed</div>
        <div class="stat-value">{{ $completedRequests }}</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-icon">💬</span>
        <div class="stat-label">Offers Received</div>
        <div class="stat-value">{{ $totalOffers }}</div>
    </div>
</div>

<!-- Recent Requests -->
<div class="content-card">
    <div class="card-header">
        <h3>📝 Recent Requests</h3>
        <a href="{{ route('customer.requests') }}" class="view-all">View All →</a>
    </div>
    
    @if($recentRequests->count() > 0)
        @foreach($recentRequests as $request)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f2f5;">
                <div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $request->title }}</div>
                    <div style="font-size: 13px; color: #8895aa;">
                        {{ $request->category->name }} • {{ $request->location }}
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
        <div style="text-align: center; padding: 40px 0; color: #8895aa;">
            <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
            <p>You haven't posted any requests yet.</p>
            <a href="{{ route('customer.requests.create') }}" style="color: #e94560; font-weight: 600; text-decoration: none;">
                Post your first request →
            </a>
        </div>
    @endif
</div>

<!-- Recent Offers -->
<div class="content-card">
    <div class="card-header">
        <h3>💬 Recent Offers</h3>
        <a href="{{ route('customer.requests') }}" class="view-all">View All →</a>
    </div>
    
    @if($recentOffers->count() > 0)
        @foreach($recentOffers as $offer)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f2f5;">
                <div>
                    <div style="font-weight: 600; color: #1a1a2e;">
                        {{ $offer->serviceRequest->title }}
                    </div>
                    <div style="font-size: 13px; color: #8895aa;">
                        From: {{ $offer->provider->name }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="badge badge-{{ $offer->status }}">
                        {{ ucfirst($offer->status) }}
                    </span>
                    <span style="font-weight: 600; color: #1a1a2e;">
                        ETB {{ number_format($offer->price, 2) }}
                    </span>
                </div>
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 40px 0; color: #8895aa;">
            <div style="font-size: 48px; margin-bottom: 12px;">💬</div>
            <p>No offers received yet.</p>
            <p style="font-size: 14px;">Post a request to start receiving offers from providers.</p>
        </div>
    @endif
</div>
@endsection