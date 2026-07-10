@extends('layouts.app')

@section('title', 'My Payments')

@section('content')
<style>
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
        color: #e94560;
        text-align: right;
    }

    .pay-status {
        font-size: 12px;
        font-weight: 600;
        text-align: right;
        margin-top: 4px;
    }

    .status-paid     { color: #0d47a1; }
    .status-released { color: #2e7d32; }
    .status-pending  { color: #e65100; }
    .status-refunded { color: #c62828; }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .summary-card {
        background: white;
        border: 1px solid #e8ecf1;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
    }

    .summary-card .s-label { font-size: 12px; color: #8895aa; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-card .s-value { font-size: 26px; font-weight: 800; color: #1a1a2e; margin-top: 4px; }
</style>

<div class="page-header">
    <div>
        <h1>💳 My Payments</h1>
        <p>Track all your payment history</p>
    </div>
    <a href="{{ route('customer.requests') }}" class="page-header btn-primary" style="background:#f0f2f5; color:#1a1a2e; box-shadow:none;">← Back to Requests</a>
</div>

@php
    $totalSpent   = $payments->getCollection()->sum('amount');
    $totalPaid    = $payments->getCollection()->where('status', 'paid')->count();
    $totalDone    = $payments->getCollection()->where('status', 'released')->count();
@endphp

<div class="summary-cards">
    <div class="summary-card">
        <div class="s-label">Total Payments</div>
        <div class="s-value">{{ $payments->total() }}</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Awaiting Completion</div>
        <div class="s-value" style="color:#0d47a1;">{{ $totalPaid }}</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Completed</div>
        <div class="s-value" style="color:#2e7d32;">{{ $totalDone }}</div>
    </div>
</div>

@if($payments->count() > 0)
    @foreach($payments as $payment)
        <div class="payment-row">
            <div class="pay-icon">
                @if($payment->isReleased()) ✅
                @elseif($payment->isPaid()) 💳
                @elseif($payment->isRefunded()) ↩️
                @else ⏳ @endif
            </div>
            <div class="pay-body">
                <div class="pay-title">{{ $payment->serviceRequest->title }}</div>
                <div class="pay-meta">
                    <span>👷 {{ $payment->provider->name }}</span>
                    <span>📅 {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : 'Pending' }}</span>
                    <span>🔖 {{ $payment->transaction_id }}</span>
                </div>
            </div>
            <div>
                <div class="pay-amount">ETB {{ number_format($payment->amount, 2) }}</div>
                <div class="pay-status status-{{ $payment->status }}">
                    @if($payment->isPaid()) 💳 Paid & Held
                    @elseif($payment->isReleased()) ✅ Released
                    @elseif($payment->isRefunded()) ↩️ Refunded
                    @else ⏳ Pending @endif
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
        <div style="font-size:56px; margin-bottom:16px; opacity:0.4;">💳</div>
        <h3 style="font-size:18px; font-weight:600; color:#1a1a2e;">No payments yet</h3>
        <p style="color:#8895aa; margin-top:8px;">Accept an offer to start.</p>
    </div>
@endif
@endsection
