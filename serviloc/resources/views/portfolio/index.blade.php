@extends('layouts.app')
@section('title', 'My Portfolio')

@section('content')
<style>
    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .portfolio-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        transition: all 0.25s ease;
        position: relative;
    }

    .portfolio-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }

    .portfolio-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea22, #e9456022);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
    }

    .portfolio-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .portfolio-body { padding: 18px; }

    .portfolio-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 6px;
    }

    .portfolio-desc {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .portfolio-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    .meta-tag {
        background: #f0f2f5;
        color: #555;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .meta-tag.featured {
        background: #fff0f3;
        color: #e94560;
        font-weight: 700;
    }

    .card-actions {
        display: flex;
        gap: 8px;
        padding: 14px 18px;
        border-top: 1px solid #f0f2f5;
    }

    .btn-sm {
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit { background: #f0f2f5; color: #555; }
    .btn-edit:hover { background: #e8ecf1; }
    .btn-delete { background: #fff0f0; color: #e94560; }
    .btn-delete:hover { background: #ffe0e0; }
    .btn-add {
        background: #e94560;
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-add:hover { background: #c73652; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(233,69,96,0.3); }

    .empty-state {
        text-align: center;
        padding: 80px 32px;
        background: white;
        border-radius: 20px;
        border: 2px dashed #e8ecf1;
    }
</style>

<div class="page-header">
    <div>
        <h1>🗂️ My Portfolio</h1>
        <p>Showcase your work to attract more customers</p>
    </div>
    <div style="display:flex; gap:12px; align-items:center;">
        <a href="{{ route('portfolio.show', auth()->user()) }}" style="color:#e94560; font-size:14px; font-weight:600; text-decoration:none;">
            👁️ View Public Profile →
        </a>
        <a href="{{ route('portfolio.create') }}" class="btn-add">+ Add Work</a>
    </div>
</div>

@if(session('success'))
    <div style="background:#e8f5e9; color:#2e7d32; padding:14px 20px; border-radius:10px; margin-bottom:20px; font-weight:500;">
        ✅ {{ session('success') }}
    </div>
@endif

@if($items->count() > 0)
    <div class="portfolio-grid">
        @foreach($items as $item)
            <div class="portfolio-card">
                <div class="portfolio-img">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}">
                    @else
                        🔧
                    @endif
                </div>

                <div class="portfolio-body">
                    <div class="portfolio-title">
                        {{ $item->title }}
                        @if($item->is_featured)
                            <span style="font-size:11px; background:#fff0f3; color:#e94560; padding:2px 8px; border-radius:20px; font-weight:700; margin-left:6px;">★ Featured</span>
                        @endif
                    </div>
                    @if($item->description)
                        <div class="portfolio-desc">{{ Str::limit($item->description, 100) }}</div>
                    @endif
                    <div class="portfolio-meta">
                        @if($item->category)
                            <span class="meta-tag">📁 {{ $item->category }}</span>
                        @endif
                        @if($item->location)
                            <span class="meta-tag">📍 {{ $item->location }}</span>
                        @endif
                        @if($item->price_from)
                            <span class="meta-tag">💰 From ETB {{ number_format($item->price_from, 0) }}</span>
                        @endif
                        @if($item->duration_days)
                            <span class="meta-tag">⏱ {{ $item->duration_days }} day{{ $item->duration_days > 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                    <div style="font-size:12px; color:#aaa;">Added {{ $item->created_at->diffForHumans() }}</div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('portfolio.edit', $item) }}" class="btn-sm btn-edit">✏️ Edit</a>
                    <form action="{{ route('portfolio.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{ $items->links() }}
@else
    <div class="empty-state">
        <div style="font-size:72px; margin-bottom:16px;">🗂️</div>
        <h3 style="font-size:22px; font-weight:700; color:#1a1a2e; margin-bottom:8px;">Your portfolio is empty</h3>
        <p style="color:#8895aa; margin-bottom:24px; max-width:400px; margin-inline:auto;">
            Add your past work, skills, and completed projects to build trust with customers.
        </p>
        <a href="{{ route('portfolio.create') }}" class="btn-add" style="display:inline-flex;">+ Add Your First Item</a>
    </div>
@endif
@endsection
