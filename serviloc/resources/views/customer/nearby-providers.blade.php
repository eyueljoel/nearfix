@extends('layouts.app')
@section('title', 'Nearby Providers')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--star-on:#f59e0b;--star-off:#e2e8f0;--red:#dc2626;--r:14px;}

    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:18px;transition:color 0.18s;}
    .back-link:hover{color:var(--blue);}

    /* Hero banner */
    .loc-banner{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 55%,#0c4a6e 100%);border-radius:var(--r);padding:22px 26px;color:white;margin-bottom:22px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
    .loc-icon{width:48px;height:48px;background:rgba(14,165,233,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;}
    .loc-text h2{font-size:17px;font-weight:700;margin-bottom:3px;}
    .loc-text p{font-size:13px;color:rgba(255,255,255,0.6);}
    .loc-chip{margin-left:auto;background:rgba(14,165,233,0.2);border:1px solid rgba(14,165,233,0.4);color:#7dd3fc;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;white-space:nowrap;}

    /* Request summary */
    .req-summary{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;margin-bottom:22px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
    .rs-title{font-size:14.5px;font-weight:700;color:var(--text);flex:1;}
    .rs-meta{display:flex;gap:10px;flex-wrap:wrap;font-size:12px;color:var(--muted);}
    .rs-budget{font-size:16px;font-weight:800;color:var(--blue-dk);}

    /* Provider cards */
    .provider-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:20px 22px;margin-bottom:14px;display:grid;grid-template-columns:1fr auto;gap:16px;transition:all 0.2s;}
    .provider-card:hover{border-color:var(--blue);box-shadow:0 4px 18px rgba(14,165,233,0.1);}
    .pc-left{display:flex;gap:14px;align-items:flex-start;}
    .prov-av{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));color:white;font-size:18px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .prov-info{flex:1;min-width:0;}
    .prov-name{font-size:15px;font-weight:800;color:var(--text);margin-bottom:3px;}
    .prov-bio{font-size:13px;color:var(--muted);line-height:1.5;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .prov-chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;}
    .chip{display:inline-flex;align-items:center;gap:4px;font-size:12px;color:var(--muted);background:var(--bg);border:1px solid var(--border);padding:3px 9px;border-radius:20px;}
    .chip-blue{background:var(--blue-lt);color:var(--blue-dk);border-color:#bfdbfe;font-weight:600;}
    .stars-mini{display:flex;gap:1px;}
    .star{font-size:13px;}
    .star-on{color:var(--star-on);}
    .star-off{color:var(--star-off);}
    .rating-text{font-size:12.5px;font-weight:700;color:var(--text);margin-left:5px;}
    .review-count{font-size:12px;color:var(--muted);margin-left:3px;}

    /* Right side actions */
    .pc-right{display:flex;flex-direction:column;gap:8px;align-items:flex-end;flex-shrink:0;}
    .btn-portfolio{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:9px;font-size:12.5px;font-weight:600;text-decoration:none;transition:all 0.18s;white-space:nowrap;}
    .btn-portfolio:hover{border-color:var(--blue);color:var(--blue);background:var(--blue-lt);}
    .btn-message{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:var(--blue);color:white;border-radius:9px;font-size:13px;font-weight:700;text-decoration:none;transition:all 0.2s;cursor:pointer;border:none;font-family:inherit;white-space:nowrap;}
    .btn-message:hover{background:var(--blue-dk);transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,0.35);}

    /* Modal backdrop */
    .modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:500;align-items:center;justify-content:center;}
    .modal-bg.open{display:flex;}
    .modal{background:var(--white);border-radius:18px;padding:28px 30px;max-width:500px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);}
    .modal-title{font-size:17px;font-weight:800;color:var(--text);margin-bottom:4px;}
    .modal-sub{font-size:13px;color:var(--muted);margin-bottom:18px;}
    .modal textarea{width:100%;padding:11px 13px;border:1.5px solid var(--border);border-radius:10px;font-size:13.5px;font-family:inherit;color:var(--text);background:var(--bg);resize:none;outline:none;min-height:100px;line-height:1.55;box-sizing:border-box;transition:border-color 0.18s;}
    .modal textarea:focus{border-color:var(--blue);background:var(--white);box-shadow:0 0 0 3px rgba(14,165,233,0.12);}
    .modal-actions{display:flex;gap:10px;margin-top:14px;}
    .btn-send-msg{padding:10px 22px;background:var(--blue);color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-send-msg:hover{background:var(--blue-dk);}
    .btn-cancel-modal{padding:10px 16px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.18s;}
    .btn-cancel-modal:hover{border-color:var(--muted);color:var(--text);}

    /* Empty state */
    .empty-state{background:var(--white);border:1.5px dashed var(--border);border-radius:var(--r);padding:56px 24px;text-align:center;}
    .empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
    .empty-state p{font-size:13.5px;color:var(--muted);max-width:400px;margin:0 auto 20px;}
    .btn-search{display:inline-flex;align-items:center;gap:6px;padding:10px 22px;background:var(--blue);color:white;border-radius:var(--r);font-size:13.5px;font-weight:600;text-decoration:none;transition:background 0.18s;}
    .btn-search:hover{background:var(--blue-dk);}

    @media(max-width:700px){.provider-card{grid-template-columns:1fr;}.pc-right{flex-direction:row;align-items:center;flex-wrap:wrap;}}
</style>

<a href="{{ route('customer.requests.show', $serviceRequest->id) }}" class="back-link">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Request
</a>

{{-- Location banner --}}
<div class="loc-banner">
    <div class="loc-icon">📍</div>
    <div class="loc-text">
        <h2>Nearby Providers</h2>
        <p>Providers whose service area matches your request location</p>
    </div>
    <div class="loc-chip">📍 {{ $location }}</div>
</div>

{{-- Request summary --}}
<div class="req-summary">
    <div>
        <div class="rs-title">{{ $serviceRequest->title }}</div>
        <div class="rs-meta">
            @if($serviceRequest->category)<span>📁 {{ $serviceRequest->category->name }}</span>@endif
            <span>📍 {{ $serviceRequest->location }}</span>
            <span>🕐 Posted {{ $serviceRequest->created_at->diffForHumans() }}</span>
        </div>
    </div>
    <div class="rs-budget">ETB {{ number_format($serviceRequest->budget, 0) }}</div>
</div>

@if(session('success'))
    <div style="background:var(--green-lt);border:1px solid #a7f3d0;border-left:4px solid var(--green);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13.5px;color:var(--green);font-weight:500;">
        ✓ {{ session('success') }}
    </div>
@endif

@if($providers->count() > 0)
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <h2 style="font-size:16px;font-weight:700;color:var(--text);">{{ $providers->count() }} provider{{ $providers->count() !== 1 ? 's' : '' }} found near "{{ $location }}"</h2>
        <span style="background:var(--blue-lt);color:var(--blue-dk);font-size:12px;font-weight:700;padding:2px 9px;border-radius:20px;border:1px solid #bfdbfe;">Sorted by rating</span>
    </div>

    @foreach($providers as $item)
        @php $provider = $item['provider']; @endphp
        <div class="provider-card">
            <div class="pc-left">
                <div class="prov-av">{{ strtoupper(substr($provider->name, 0, 2)) }}</div>
                <div class="prov-info">
                    <div class="prov-name">{{ $provider->name }}</div>
                    @if($provider->bio)
                        <div class="prov-bio">{{ $provider->bio }}</div>
                    @endif
                    <div class="prov-chips">
                        @if($provider->address)
                            <span class="chip chip-blue">📍 {{ $provider->address }}</span>
                        @endif
                        <span class="chip">✅ {{ $item['completed_jobs'] }} jobs done</span>
                        @if($item['portfolio_count'] > 0)
                            <span class="chip">🗂️ {{ $item['portfolio_count'] }} portfolio items</span>
                        @endif
                    </div>
                    <div style="display:flex;align-items:center;gap:4px;">
                        <div class="stars-mini">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= round($item['avg_rating']) ? 'star-on' : 'star-off' }}">★</span>
                            @endfor
                        </div>
                        <span class="rating-text">{{ $item['avg_rating'] }}</span>
                        <span class="review-count">({{ $item['total_reviews'] }} {{ Str::plural('review', $item['total_reviews']) }})</span>
                    </div>
                </div>
            </div>
            <div class="pc-right">
                <a href="{{ route('portfolio.show', $provider) }}" class="btn-portfolio" target="_blank">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View Portfolio
                </a>
                <button type="button" class="btn-message"
                        onclick="openModal('{{ $provider->id }}', '{{ addslashes($provider->name) }}')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Send Message
                </button>
            </div>
        </div>
    @endforeach
@else
    <div class="empty-state">
        <div style="width:56px;height:56px;background:var(--blue-lt);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px;">📍</div>
        <h3>No providers found near "{{ $location }}"</h3>
        <p>We couldn't find providers in this specific area. Try browsing all providers or editing your request location.</p>
        <a href="{{ route('search') }}?q={{ urlencode($serviceRequest->title) }}" class="btn-search">🔍 Search All Providers</a>
    </div>
@endif

{{-- Message Modal --}}
<div class="modal-bg" id="messageModal" onclick="closeModalOutside(event)">
    <div class="modal">
        <div class="modal-title" id="modalTitle">Contact Provider</div>
        <div class="modal-sub" id="modalSub">Send a direct message about your service request</div>

        <form action="{{ route('customer.requests.contact-provider', $serviceRequest->id) }}" method="POST" id="modalForm">
            @csrf
            <input type="hidden" name="provider_id" id="modalProviderId">
            <textarea name="body" id="modalBody" placeholder="Hi! I posted a request about '{{ $serviceRequest->title }}'. Are you available to take on this job? My budget is ETB {{ number_format($serviceRequest->budget, 0) }}." rows="5">Hi! I posted a request about "{{ $serviceRequest->title }}". Are you available to take on this job? My budget is ETB {{ number_format($serviceRequest->budget, 0) }}.</textarea>
            @error('body')
                <div style="font-size:12px;color:var(--red);margin-top:4px;">⚠ {{ $message }}</div>
            @enderror
            <div class="modal-actions">
                <button type="submit" class="btn-send-msg">
                    Send Message →
                </button>
                <button type="button" class="btn-cancel-modal" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(providerId, providerName) {
    document.getElementById('modalProviderId').value = providerId;
    document.getElementById('modalTitle').textContent = 'Message ' + providerName;
    document.getElementById('modalSub').textContent = 'Send a direct message about "{{ addslashes($serviceRequest->title) }}"';
    document.getElementById('messageModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('messageModal').classList.remove('open');
    document.body.style.overflow = '';
}

function closeModalOutside(e) {
    if (e.target === document.getElementById('messageModal')) closeModal();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
@endsection
