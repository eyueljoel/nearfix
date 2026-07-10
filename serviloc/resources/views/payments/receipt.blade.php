@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('content')
<style>
    .receipt-container {
        max-width: 620px;
        margin: 0 auto;
    }

    .receipt-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    }

    .receipt-header {
        padding: 36px 32px;
        text-align: center;
        border-bottom: 2px dashed #e8ecf1;
    }

    .receipt-status-icon {
        font-size: 64px;
        margin-bottom: 12px;
    }

    .receipt-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 4px;
    }

    .receipt-txn {
        font-size: 13px;
        color: #8895aa;
        font-family: monospace;
    }

    .receipt-body {
        padding: 28px 32px;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
        font-size: 14px;
    }

    .receipt-row:last-child { border-bottom: none; }

    .receipt-row .r-label {
        color: #8895aa;
        font-weight: 500;
    }

    .receipt-row .r-value {
        font-weight: 600;
        color: #1a1a2e;
        text-align: right;
    }

    .receipt-total {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
    }

    .receipt-total .amount {
        font-size: 36px;
        font-weight: 800;
        color: #e94560;
    }

    .receipt-total .currency {
        font-size: 16px;
        color: #8895aa;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
    }

    .status-paid     { background: #e3f2fd; color: #0d47a1; }
    .status-released { background: #e8f5e9; color: #2e7d32; }
    .status-pending  { background: #fff3e0; color: #e65100; }
    .status-refunded { background: #fce4ec; color: #c62828; }

    .receipt-actions {
        padding: 24px 32px;
        border-top: 2px dashed #e8ecf1;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-release {
        flex: 1;
        padding: 14px;
        background: #2e7d32;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .btn-release:hover {
        background: #1b5e20;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(46,125,50,0.3);
    }

    .btn-outline {
        flex: 1;
        padding: 14px;
        background: white;
        color: #555;
        border: 2px solid #e8ecf1;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        text-align: center;
    }

    .btn-outline:hover { border-color: #e94560; color: #e94560; }

    .receipt-footer {
        padding: 16px 32px;
        background: #f8f9fa;
        text-align: center;
        font-size: 12px;
        color: #8895aa;
    }
</style>

<div class="receipt-container">
    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 500;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="receipt-card">
        <div class="receipt-header">
            <div class="receipt-status-icon">
                @if($payment->isReleased()) 🎉
                @elseif($payment->isPaid()) ✅
                @elseif($payment->isRefunded()) ↩️
                @else ⏳ @endif
            </div>
            <div class="receipt-title">
                @if($payment->isReleased()) Payment Released
                @elseif($payment->isPaid()) Payment Successful
                @elseif($payment->isRefunded()) Payment Refunded
                @else Payment Pending @endif
            </div>
            <div class="receipt-txn">{{ $payment->transaction_id }}</div>
        </div>

        <div class="receipt-body">
            <div class="receipt-total">
                <div class="currency">ETB</div>
                <div class="amount">{{ number_format($payment->amount, 2) }}</div>
                <div style="margin-top: 8px;">
                    <span class="status-pill status-{{ $payment->status }}">
                        @if($payment->isPaid()) 💳 Paid & Held
                        @elseif($payment->isReleased()) ✅ Released to Provider
                        @elseif($payment->isRefunded()) ↩️ Refunded
                        @else ⏳ Pending @endif
                    </span>
                </div>
            </div>

            <div class="receipt-row">
                <span class="r-label">Service</span>
                <span class="r-value">{{ $payment->serviceRequest->title }}</span>
            </div>
            <div class="receipt-row">
                <span class="r-label">Customer</span>
                <span class="r-value">{{ $payment->customer->name }}</span>
            </div>
            <div class="receipt-row">
                <span class="r-label">Provider</span>
                <span class="r-value">{{ $payment->provider->name }}</span>
            </div>
            <div class="receipt-row">
                <span class="r-label">Payment Method</span>
                <span class="r-value">
                    @php $methods = ['simulated' => '💵 Cash on Delivery', 'telebirr' => '📱 TeleBirr', 'cbe_birr' => '🏦 CBE Birr', 'bank_transfer' => '💳 Bank Transfer']; @endphp
                    {{ $methods[$payment->payment_method] ?? ucfirst($payment->payment_method) }}
                </span>
            </div>
            <div class="receipt-row">
                <span class="r-label">Paid At</span>
                <span class="r-value">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y H:i') : '—' }}</span>
            </div>
            @if($payment->released_at)
            <div class="receipt-row">
                <span class="r-label">Released At</span>
                <span class="r-value">{{ $payment->released_at->format('M d, Y H:i') }}</span>
            </div>
            @endif
        </div>

        {{-- Actions: only customer can release, only when status = paid --}}
        @if(auth()->id() === $payment->customer_id && $payment->isPaid())
        <div class="receipt-actions">
            <div style="width: 100%; margin-bottom: 8px;">
                <p style="font-size: 13px; color: #555; background: #fff8e1; padding: 12px; border-radius: 8px; border: 1px solid #ffe082;">
                    ⚠️ Once you mark the service as complete, the payment will be released to the provider and cannot be undone.
                </p>
            </div>
            <form action="{{ route('payments.release', $payment) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure the service is complete? This will release the payment to the provider.')">
                @csrf
                <button type="submit" class="btn-release" style="width:100%;">
                    ✅ Mark Service Complete & Release Payment
                </button>
            </form>
            <a href="{{ route('customer.requests') }}" class="btn-outline">View Requests</a>
        </div>
        @else
        <div class="receipt-actions">
            @if(auth()->id() === $payment->customer_id)
                <a href="{{ route('payments.customer-history') }}" class="btn-outline">My Payments</a>
                <a href="{{ route('customer.requests') }}" class="btn-outline">My Requests</a>
            @else
                <a href="{{ route('payments.provider-history') }}" class="btn-outline">My Earnings</a>
                <a href="{{ route('provider.dashboard') }}" class="btn-outline">Dashboard</a>
            @endif
        </div>
        @endif

        <div class="receipt-footer">
            ServiLoc · Transaction ID: {{ $payment->transaction_id }}
        </div>
    </div>
</div>
@endsection
