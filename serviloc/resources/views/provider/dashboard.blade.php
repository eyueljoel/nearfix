@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>👋 Welcome back, {{ auth()->user()->name }}</h1>
        <p>Find and bid on service requests</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon">💬</span>
        <div class="stat-label">Total Offers</div>
        <div class="stat-value">{{ $totalOffers }}</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-icon">⏳</span>
        <div class="stat-label">Pending Offers</div>
        <div class="stat-value">{{ $pendingOffers }}</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-icon">✅</span>
        <div class="stat-label">Accepted Offers</div>
        <div class="stat-value">{{ $acceptedOffers }}</div>
    </div>
</div>

<!-- Available Requests -->
<div class="content-card">
    <div class="card-header">
        <h3>📋 Available Service Requests</h3>
        <a href="#" class="view-all">View All →</a>
    </div>
    
    @if($availableRequests->count() > 0)
        @foreach($availableRequests as $request)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f2f5;">
                <div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $request->title }}</div>
                    <div style="font-size: 13px; color: #8895aa;">
                        {{ $request->category->name ?? 'Uncategorized' }} • {{ $request->location }}
                    </div>
                    <div style="font-size: 13px; color: #8895aa;">
                        Customer: {{ $request->user->name ?? 'Unknown' }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-weight: 600; color: #1a1a2e;">
                        ETB {{ number_format($request->budget, 2) }}
                    </span>
                    <a href="{{ route('offers.create', $request->id) }}" class="btn-primary" style="padding: 8px 20px; font-size: 13px; text-decoration: none; display: inline-block;">
                        Send Offer →
                    </a>
                </div>
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 40px 0; color: #8895aa;">
            <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
            <p>No available requests at the moment.</p>
            <p style="font-size: 14px;">Check back later for new requests.</p>
        </div>
    @endif
</div>

<!-- Recent Offers -->
<div class="content-card">
    <div class="card-header">
        <h3>💬 Your Recent Offers</h3>
        <a href="#" class="view-all">View All →</a>
    </div>
    
    @if($recentOffers->count() > 0)
        @foreach($recentOffers as $offer)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f2f5;">
                <div>
                    <div style="font-weight: 600; color: #1a1a2e;">
                        {{ $offer->serviceRequest->title }}
                    </div>
                    <div style="font-size: 13px; color: #8895aa;">
                        Customer: {{ $offer->serviceRequest->user->name }}
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
            <p>You haven't sent any offers yet.</p>
            <p style="font-size: 14px;">Browse available requests and start bidding!</p>
        </div>
    @endif
</div>
@endsection