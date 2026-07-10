@extends('layouts.app')

@section('title', 'My Earnings')

@section('content')
<style>
    .earnings-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
        border-radius: 20px;
        padding: 32px;
        color: white;
        margin-bottom: 28px;
        display: flex;
        gap: 32px;
        align-items: center;
        flex-wrap: wrap;
    }

    .earnings-stat { text-align: center; }
    .earnings-stat .e-label { font-size: 12px; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.5px; }
    .earnings-stat .e-value { font-size: 32px; font-weight: 800; margin-top: 4px; }
    .earnings-stat .e-currency { font-size: 14px; opacity: 0.8; }

    .divider { width: 1px; background: rgba(255,255,255,0.15); align-self: stretch; }

    .payment-row {
        background: white;
        border: 1px solid #e8ecf1;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.2s;
    }

    .payment-row:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        transform: translateX(4px);
    }

    .pay-icon {
        font-size: 32px;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pay-body { flex: 1; }

    .pay-title {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 15px;
        margin-bottom: 4px;
    }

    .pay-meta {
        font-size: 13px;
        color: #8895aa;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .pay-amount {
        font-size: 20px;
        font-weight: 800;
        text-align: right;
    }

    .amount-released { color: #2e7d32; }
    .amount-held     { color: #0d47a1; }
    .amount-pending  { color: #e65100; }
</style>

<div class="page-header">
    <div>
        <h1>💰 My Earnings</h1>
        <p>Your payment history from completed services</p>
    </div>
</div>

{{-- Earnings summary --}}
<div class="earnings-header">
    <div class="earnings-stat">
        <div class="e-label">Total Earned</div>
        <div class="e-currency">ETB</div>
        <div class="e-value" style="color: #4ecca3;">{{ number_format($totalEarned, 2) }}</div>
    </div>
    <div class="divider"></div>
    <div class="earnings-stat">
        <div class="e-label">Pending Release</div>
        <div class="e-currency">ETB</div>
        <div class="e-value" style="color: #f6c90e;">{{ number_format($pendingAmount, 2) }}</div>
    </div>
    <div class="divider"></div>
    <div class="earnings-stat">
        <div class="e-label">Total Jobs</div>
        <div class="e-value">{{ $payments->total() }}</div>
    </div>
</div>

@if($payments->count() > 0)
    @foreach($payments as $payment)
        <div class="payment-row">
            <div class="pay-icon">
                @if($payment->isReleased()) ✅
                @elseif($payment->isPaid()) ⏳
                @else 💳 @endif
            </div>
            <div class="pay-body">
                <div class="pay-title">{{ $payment->serviceRequest->title }}</div>
                <div class="pay-meta">
                    <span>👤 {{ $payment->customer->name }}</span>
                    <span>📅 {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : 'Pending' }}</span>
                    <span>🔖 {{ $payment->transaction_id }}</span>
                </div>
            </div>
            <div>
                <div class="pay-amount {{ $payment->isReleased() ? 'amount-released' : ($payment->isPaid() ? 'amount-held' : 'amount-pending') }}">
                    ETB {{ number_format($payment->amount, 2) }}
                </div>
                <div style="font-size:12px; font-weight:600; text-align:right; margin-top:4px; color:#8895aa;">
                    @if($payment->isReleased()) ✅ In your account
                    @elseif($payment->isPaid()) ⏳ Waiting for customer
                    @else {{ ucfirst($payment->status) }} @endif
                </div>
                <div style="margin-top:8px; text-align:right;">
                    <a href="{{ route('payments.receipt', $payment) }}" style="font-size:13px; color:#e94560; font-weight:600; text-decoration:none;">
                        View Receipt →
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    <div style="margin-top: 24px;">{{ $payments->links() }}</div>
@else
    <div style="text-align:center; padding: 64px; background:white; border-radius:16px; border:1px solid #e8ecf1;">
        <div style="font-size:56px; margin-bottom:16px; opacity:0.4;">💰</div>
        <h3 style="font-size:18px; font-weight:600; color:#1a1a2e;">No earnings yet</h3>
        <p style="color:#8895aa; margin-top:8px;">Complete services to see your earnings here.</p>
    </div>
@endif
@endsection
