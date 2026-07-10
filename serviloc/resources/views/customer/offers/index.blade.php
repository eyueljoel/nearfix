@extends('layouts.app')

@section('title', 'Offers Received')

@section('content')
<div class="page-header">
    <div>
        <h1>🤝 Offers Received</h1>
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

<!-- Filters and Sorting -->
<div class="content-card" style="margin-bottom: 20px;">
    <form method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
        <!-- Status Filter -->
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #666; margin-bottom: 6px;">
                Status
            </label>
            <select name="status" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <!-- Sort -->
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #666; margin-bottom: 6px;">
                Sort By
            </label>
            <select name="sort" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>
        </div>

        <button type="submit" style="padding: 8px 20px; background: #e94560; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
            Filter
        </button>

        @if(request('status') || request('sort') !== 'latest')
            <a href="{{ route('customer.offers') }}" style="padding: 8px 20px; background: #6c757d; color: white; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none; transition: all 0.3s;">
                Clear
            </a>
        @endif
    </form>
</div>

@if($offers->count() > 0)
    <div style="display: grid; gap: 16px;">
        @foreach($offers as $offer)
            <div class="content-card">
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <h3 style="font-size: 18px; margin: 0; color: #1a1a2e;">{{ $offer->serviceRequest->title }}</h3>
                            <span class="badge badge-{{ $offer->status }}" style="font-size: 12px; padding: 4px 12px; border-radius: 6px; font-weight: 600;">
                                {{ ucfirst($offer->status) }}
                            </span>
                        </div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 4px;">
                            <strong>Provider:</strong> {{ $offer->provider->name }}
                        </div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 4px;">
                            <strong>Message:</strong> {{ Str::limit($offer->message, 100) }}
                        </div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 4px;">
                            <strong>Location:</strong> {{ $offer->serviceRequest->location }}
                        </div>
                        <div style="font-size: 13px; color: #8895aa; margin-top: 8px;">
                            📅 {{ $offer->created_at->format('M d, Y') }} at {{ $offer->created_at->format('H:i') }}
                        </div>
                    </div>
                    <div style="text-align: right; min-width: 140px;">
                        <div style="font-size: 28px; font-weight: 700; color: #e94560;">
                            ETB {{ number_format($offer->price, 2) }}
                        </div>
                        
                        @if($offer->status === 'pending')
                            <div style="display: flex; gap: 8px; margin-top: 16px; flex-direction: column;">
                                <form action="{{ route('offers.accept', $offer->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" style="width: 100%; padding: 8px 12px; background: #28a745; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px; transition: all 0.3s;">
                                        ✅ Accept
                                    </button>
                                </form>
                                <form action="{{ route('offers.reject', $offer->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" style="width: 100%; padding: 8px 12px; background: #dc3545; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px; transition: all 0.3s;">
                                        ❌ Reject
                                    </button>
                                </form>
                            </div>
                        @elseif($offer->status === 'accepted')
                            @php $payment = \App\Models\Payment::where('offer_id', $offer->id)->first(); @endphp
                            <div style="margin-top: 16px;">
                                @if(!$payment)
                                    <a href="{{ route('payments.checkout', $offer->id) }}"
                                       style="display:block; width:100%; padding:10px 12px; background:#e94560; color:white; border:none; border-radius:8px; font-weight:700; font-size:14px; text-align:center; text-decoration:none; transition:all 0.3s;">
                                        💳 Pay Now
                                    </a>
                                @elseif($payment->isPaid())
                                    <a href="{{ route('payments.receipt', $payment) }}"
                                       style="display:block; width:100%; padding:10px 12px; background:#0d47a1; color:white; border:none; border-radius:8px; font-weight:600; font-size:13px; text-align:center; text-decoration:none;">
                                        ✅ Paid · View Receipt
                                    </a>
                                @elseif($payment->isReleased())
                                    <span style="display:block; width:100%; padding:10px 12px; background:#e8f5e9; color:#2e7d32; border-radius:8px; font-weight:600; font-size:13px; text-align:center;">
                                        ✅ Completed
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $offers->links() }}
    </div>
@else
    <div class="content-card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 16px;">🤝</div>
        <h3 style="color: #1a1a2e; margin-bottom: 8px; margin-top: 0;">No offers yet</h3>
        <p style="color: #8895aa; margin: 0;">Providers will send offers on your requests.</p>
    </div>
@endif

<style>
.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
}

.badge-pending {
    background: #fff3cd;
    color: #856404;
}

.badge-accepted {
    background: #d4edda;
    color: #155724;
}

.badge-rejected {
    background: #f8d7da;
    color: #721c24;
}

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
