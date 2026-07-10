@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .stats-grid-6 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    @media (max-width: 1200px) { .stats-grid-6 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px)  { .stats-grid-6 { grid-template-columns: 1fr; } }

    .stat-card-v2 {
        background: white;
        padding: 22px 24px;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.25s;
    }

    .stat-card-v2:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.07);
    }

    .stat-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-card-v2 .s-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8895aa;
    }

    .stat-card-v2 .s-value {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a2e;
        line-height: 1.1;
        margin-top: 2px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        padding: 24px;
    }

    .chart-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 20px;
    }

    .chart-full {
        grid-column: span 2;
    }

    @media (max-width: 900px) {
        .charts-grid { grid-template-columns: 1fr; }
        .chart-full  { grid-column: span 1; }
    }

    .table-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 13px 0;
        border-bottom: 1px solid #f0f2f5;
        font-size: 14px;
    }

    .table-row:last-child { border-bottom: none; }
</style>

<div class="page-header">
    <div>
        <h1>📊 Admin Dashboard</h1>
        <p>Platform overview · {{ now()->format('F j, Y') }}</p>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div style="background:#e8f5e9;color:#2e7d32;padding:14px 20px;border-radius:10px;margin-bottom:20px;font-weight:500;">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fff5f5;color:#c53030;padding:14px 20px;border-radius:10px;margin-bottom:20px;font-weight:500;">
        ⚠ {{ session('error') }}
    </div>
@endif

{{-- ── Stat Cards ── --}}
<div class="stats-grid-6">
    <div class="stat-card-v2">
        <div class="stat-icon-box" style="background:#e8f0fe;">👥</div>
        <div>
            <div class="s-label">Total Users</div>
            <div class="s-value">{{ $totalUsers }}</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-icon-box" style="background:#e8f5e9;">👤</div>
        <div>
            <div class="s-label">Customers</div>
            <div class="s-value" style="color:#2e7d32;">{{ $totalCustomers }}</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-icon-box" style="background:#fff3e0;">🔧</div>
        <div>
            <div class="s-label">Providers</div>
            <div class="s-value" style="color:#e65100;">{{ $totalProviders }}</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-icon-box" style="background:#fce4ec;">📋</div>
        <div>
            <div class="s-label">Requests</div>
            <div class="s-value" style="color:#c62828;">{{ $totalRequests }}</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-icon-box" style="background:#f3e5f5;">🤝</div>
        <div>
            <div class="s-label">Total Offers</div>
            <div class="s-value" style="color:#6a1b9a;">{{ $totalOffers }}</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-icon-box" style="background:#e0f2f1;">💳</div>
        <div>
            <div class="s-label">Revenue (ETB)</div>
            <div class="s-value" style="color:#00695c;">{{ number_format($totalPayments, 0) }}</div>
        </div>
    </div>
    <div class="stat-card-v2">
        <div class="stat-icon-box" style="background:#e8ecf1;">📂</div>
        <div>
            <div class="s-label">Categories</div>
            <div class="s-value">{{ $totalCategories }}</div>
        </div>
    </div>
</div>

{{-- ── Charts Row 1 ── --}}
<div class="charts-grid">
    {{-- Registrations Line Chart --}}
    <div class="chart-card chart-full">
        <h3>👤 New User Registrations — Last 30 Days</h3>
        <canvas id="regChart" height="90"></canvas>
    </div>

    {{-- Requests by Status Doughnut --}}
    <div class="chart-card">
        <h3>📋 Requests by Status</h3>
        <canvas id="statusChart" height="220"></canvas>
    </div>

    {{-- Requests per day --}}
    <div class="chart-card">
        <h3>📈 Service Requests — Last 30 Days</h3>
        <canvas id="reqChart" height="220"></canvas>
    </div>
</div>

{{-- ── Charts Row 2: Revenue ── --}}
<div class="chart-card" style="margin-bottom:24px;">
    <h3>💰 Revenue Released (ETB) — Last 30 Days</h3>
    <canvas id="revChart" height="80"></canvas>
</div>

{{-- ── Recent Requests ── --}}
<div class="content-card">
    <div class="card-header">
        <h3>📋 Recent Service Requests</h3>
        <a href="{{ route('admin.requests') }}" class="view-all">View All →</a>
    </div>

    @forelse($recentRequests as $req)
        <div class="table-row">
            <div>
                <div style="font-weight:600;color:#1a1a2e;">{{ $req->title }}</div>
                <div style="font-size:13px;color:#8895aa;">
                    {{ $req->user->name ?? '—' }} · {{ $req->category->name ?? '—' }} · {{ $req->location }}
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <span class="badge badge-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
                <span style="font-weight:700;color:#1a1a2e;">ETB {{ number_format($req->budget, 0) }}</span>
            </div>
        </div>
    @empty
        <p style="color:#8895aa;text-align:center;padding:24px 0;">No requests yet.</p>
    @endforelse
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const chartDefaults = {
        font: { family: 'Inter, sans-serif' },
        color: '#8895aa',
    };
    Chart.defaults.font.family = 'Inter, sans-serif';

    // Shared gradient helper
    function gradient(ctx, color1, color2) {
        const g = ctx.createLinearGradient(0, 0, 0, 300);
        g.addColorStop(0, color1);
        g.addColorStop(1, color2);
        return g;
    }

    // ── 1. Registrations ──────────────────────────────────────────────
    (function() {
        const ctx = document.getElementById('regChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($regLabels),
                datasets: [{
                    label: 'New Users',
                    data: @json($regData),
                    borderColor: '#667eea',
                    backgroundColor: gradient(ctx, 'rgba(102,126,234,0.18)', 'rgba(102,126,234,0)'),
                    borderWidth: 2.5,
                    pointRadius: 3,
                    pointBackgroundColor: '#667eea',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f2f5' } },
                    x: { grid: { display: false } }
                }
            }
        });
    })();

    // ── 2. Requests by Status (Doughnut) ─────────────────────────────
    (function() {
        const raw = @json($requestsByStatus);
        const labels = Object.keys(raw).map(s => s.charAt(0).toUpperCase() + s.slice(1));
        const data   = Object.values(raw);
        const colors = ['#667eea','#e94560','#f6c90e','#4ecca3','#ff9800','#9c27b0'];

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{ data, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, font: { size: 13 } } }
                },
                cutout: '65%',
            }
        });
    })();

    // ── 3. Requests per day ───────────────────────────────────────────
    (function() {
        const ctx = document.getElementById('reqChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($reqLabels),
                datasets: [{
                    label: 'Requests',
                    data: @json($reqData),
                    backgroundColor: gradient(ctx, 'rgba(233,69,96,0.7)', 'rgba(233,69,96,0.2)'),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f2f5' } },
                    x: { grid: { display: false } }
                }
            }
        });
    })();

    // ── 4. Revenue per day ────────────────────────────────────────────
    (function() {
        const ctx = document.getElementById('revChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($revLabels),
                datasets: [{
                    label: 'Revenue (ETB)',
                    data: @json($revData),
                    borderColor: '#4ecca3',
                    backgroundColor: gradient(ctx, 'rgba(78,204,163,0.18)', 'rgba(78,204,163,0)'),
                    borderWidth: 2.5,
                    pointRadius: 3,
                    pointBackgroundColor: '#4ecca3',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f2f5' } },
                    x: { grid: { display: false } }
                }
            }
        });
    })();
</script>
@endpush

@endsection
