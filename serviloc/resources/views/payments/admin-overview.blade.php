@extends('layouts.app')

@section('title', 'Payments Overview')

@section('content')
<style>
    .stat-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .mini-stat {
        background: white;
        border: 1px solid #e8ecf1;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
    }

    .mini-stat .ms-label { font-size: 12px; color: #8895aa; text-transform: uppercase; letter-spacing: 0.5px; }
    .mini-stat .ms-value { font-size: 24px; font-weight: 800; color: #1a1a2e; margin-top: 4px; }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #8895aa; border-bottom: 2px solid #e8ecf1; }
    td { padding: 14px 16px; font-size: 14px; color: #1a1a2e; border-bottom: 1px solid #f0f2f5; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafbfc; }

    .status-pill {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-paid     { background: #e3f2fd; color: #0d47a1; }
    .status-released { background: #e8f5e9; color: #2e7d32; }
    .status-pending  { background: #fff3e0; color: #e65100; }
    .status-refunded { background: #fce4ec; color: #c62828; }
</style>

<div class="page-header">
    <div>
        <h1>💳 Payments Overview</h1>
        <p>All transactions on the platform</p>
    </div>
</div>

<div class="stat-row">
    <div class="mini-stat">
        <div class="ms-label">Total Transactions</div>
        <div class="ms-value">{{ $stats['total_count'] }}</div>
    </div>
    <div class="mini-stat">
        <div class="ms-label">Held (Paid)</div>
        <div class="ms-value" style="color:#0d47a1;">ETB {{ number_format($stats['total_paid'], 0) }}</div>
    </div>
    <div class="mini-stat">
        <div class="ms-label">Released</div>
        <div class="ms-value" style="color:#2e7d32;">ETB {{ number_format($stats['total_released'], 0) }}</div>
    </div>
    <div class="mini-stat">
        <div class="ms-label">Refunded</div>
        <div class="ms-value" style="color:#c62828;">ETB {{ number_format($stats['total_refunded'], 0) }}</div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>All Transactions</h3>
    </div>

    @if($payments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Service</th>
                    <th>Customer</th>
                    <th>Provider</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>
                            <a href="{{ route('payments.receipt', $payment) }}" style="color:#e94560; font-family:monospace; font-size:13px;">
                                {{ $payment->transaction_id }}
                            </a>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($payment->serviceRequest->title, 30) }}</td>
                        <td>{{ $payment->customer->name }}</td>
                        <td>{{ $payment->provider->name }}</td>
                        <td style="font-weight:700;">ETB {{ number_format($payment->amount, 2) }}</td>
                        <td>
                            @php $methods = ['simulated' => '💵 Cash', 'telebirr' => '📱 TeleBirr', 'cbe_birr' => '🏦 CBE', 'bank_transfer' => '💳 Bank']; @endphp
                            {{ $methods[$payment->payment_method] ?? ucfirst($payment->payment_method) }}
                        </td>
                        <td><span class="status-pill status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                        <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 24px;">{{ $payments->links() }}</div>
    @else
        <div style="text-align:center; padding:40px; color:#8895aa;">
            No transactions yet.
        </div>
    @endif
</div>
@endsection
