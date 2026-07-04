@extends('layouts.app')

@section('title', 'Offers Received')

@section('content')
<div class="page-header">
    <div>
        <h1>💬 Offers Received</h1>
        <p>View all offers on your service requests</p>
    </div>
    <a href="{{ route('customer.requests') }}" class="btn-secondary">
        ← Back to My Requests
    </a>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #28a745;">
        {{ session('success') }}
    </div>
@endif

@if($offers->count() > 0)
    @foreach($offers as $offer)
        <div class="content-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <h3 style="font-size: 18px; margin: 0;">{{ $offer->serviceRequest->title }}</h3>
                        <span class="badge badge-{{ $offer->status }}">
                            {{ ucfirst($offer->status) }}
                        </span>
                    </div>
                    <div style="font-size: 14px; color: #666; margin-bottom: 4px;">
                        <strong>Provider:</strong> {{ $offer->provider->name }}
                    </div>
                    <div style="font-size: 14px; color: #666; margin-bottom: 4px;">
                        <strong>Message:</strong> {{ $offer->message }}
                    </div>
                    <div style="font-size: 14px; color: #666;">
                        <strong>Location:</strong> {{ $offer->serviceRequest->location }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 28px; font-weight: 700; color: #e94560;">
                        ETB {{ number_format($offer->price, 2) }}
                    </div>
                    <div style="font-size: 13px; color: #8895aa; margin-top: 4px;">
                        {{ $offer->created_at->diffForHumans() }}
                    </div>
                    
                    @if($offer->status === 'pending')
                        <div style="display: flex; gap: 8px; margin-top: 12px;">
                            <form action="{{ route('offers.accept', $offer->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-primary" style="padding: 6px 20px; font-size: 13px;">
                                    ✅ Accept
                                </button>
                            </form>
                            <form action="{{ route('offers.reject', $offer->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-secondary" style="padding: 6px 20px; font-size: 13px;">
                                    ❌ Reject
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="content-card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 16px;">💬</div>
        <h3 style="color: #1a1a2e; margin-bottom: 8px;">No offers yet</h3>
        <p style="color: #8895aa;">Providers will send offers on your requests.</p>
    </div>
@endif

<style>
.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 10px 24px;
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
.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}
</style>
@endsection
