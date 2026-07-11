@extends('layouts.app')
@section('title', 'Leave a Review')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--red:#dc2626;--star-on:#f59e0b;--r:12px;}
    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:18px;transition:color 0.18s;}
    .back-link:hover{color:var(--blue);}
    .form-wrap{max-width:580px;}
    .form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:28px 32px;}
    .form-card h2{font-size:18px;font-weight:800;color:var(--text);letter-spacing:-0.2px;margin-bottom:4px;}
    .form-card .sub{font-size:13.5px;color:var(--muted);margin-bottom:24px;}
    .req-info{background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:14px 16px;margin-bottom:22px;}
    .ri-title{font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px;}
    .ri-meta{font-size:13px;color:var(--muted);display:flex;gap:12px;flex-wrap:wrap;}
    .f-group{margin-bottom:20px;}
    .f-group label{display:block;font-size:12.5px;font-weight:600;color:var(--text);margin-bottom:8px;}
    .f-ctrl{width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:9px;font-size:13.5px;font-family:inherit;color:var(--text);background:var(--bg);transition:all 0.18s;outline:none;box-sizing:border-box;resize:vertical;}
    .f-ctrl:focus{border-color:var(--blue);background:var(--white);box-shadow:0 0 0 3px rgba(14,165,233,0.12);}
    .f-error{font-size:12px;color:var(--red);margin-top:4px;}
    /* Star rating */
    .stars-input{display:flex;gap:6px;margin-bottom:6px;}
    .star-btn{font-size:34px;cursor:pointer;transition:all 0.15s;color:var(--border);background:none;border:none;padding:0;line-height:1;}
    .star-btn:hover,.star-btn.active{color:var(--star-on);transform:scale(1.15);}
    .star-label{font-size:13px;color:var(--muted);margin-top:4px;min-height:18px;}
    .btn-submit{padding:11px 28px;background:var(--blue);color:white;border:none;border-radius:var(--r);font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-submit:hover{background:var(--blue-dk);transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,0.35);}
    .btn-cancel{padding:11px 20px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:var(--r);font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;transition:all 0.18s;margin-left:8px;}
    .btn-cancel:hover{border-color:var(--muted);color:var(--text);}
</style>

<a href="{{ route('customer.dashboard') }}" class="back-link">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Dashboard
</a>

<div class="form-wrap">
    <div class="form-card">
        <h2>Leave a Review</h2>
        <p class="sub">Rate your experience with {{ $serviceRequest->assignedProvider->name ?? 'the provider' }}</p>

        <div class="req-info">
            <div class="ri-title">{{ $serviceRequest->title }}</div>
            <div class="ri-meta">
                <span>👷 {{ $serviceRequest->assignedProvider->name ?? 'N/A' }}</span>
                <span>💰 ETB {{ number_format($serviceRequest->budget, 0) }}</span>
                <span>📍 {{ $serviceRequest->location }}</span>
            </div>
        </div>

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fca5a5;border-left:4px solid var(--red);border-radius:9px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:var(--red);">
                @foreach($errors->all() as $e)<div>⚠ {{ $e }}</div>@endforeach
            </div>
        @endif

        <form action="{{ route('reviews.store', $serviceRequest->id) }}" method="POST">
            @csrf

            <div class="f-group">
                <label>Your Rating <span style="color:var(--blue);">*</span></label>
                <div class="stars-input" id="starContainer">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="star-btn {{ old('rating') >= $i ? 'active' : '' }}" data-value="{{ $i }}">★</button>
                    @endfor
                </div>
                <div class="star-label" id="starLabel">
                    @php $labels = [1=>'Poor',2=>'Fair',3=>'Good',4=>'Very Good',5=>'Excellent']; @endphp
                    {{ old('rating') ? $labels[old('rating')] : 'Click a star to rate' }}
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}" required>
                @error('rating')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="f-group">
                <label for="comment">Your Review <span style="color:var(--blue);">*</span></label>
                <textarea name="comment" id="comment" class="f-ctrl" rows="5"
                          placeholder="Describe your experience — was the work done well? Was the provider professional and on time?">{{ old('comment') }}</textarea>
                @error('comment')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin-top:8px;">
                <button type="submit" class="btn-submit">Submit Review</button>
                <a href="{{ route('customer.dashboard') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars  = document.querySelectorAll('.star-btn');
    const input  = document.getElementById('ratingInput');
    const label  = document.getElementById('starLabel');
    const labels = {1:'Poor', 2:'Fair', 3:'Good', 4:'Very Good', 5:'Excellent'};

    function setStars(val) {
        stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.value) <= val));
        label.textContent = val ? labels[val] : 'Click a star to rate';
        input.value = val || '';
    }

    stars.forEach(star => {
        star.addEventListener('click', () => setStars(parseInt(star.dataset.value)));
        star.addEventListener('mouseenter', () => {
            stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.value) <= parseInt(star.dataset.value)));
        });
        star.addEventListener('mouseleave', () => setStars(parseInt(input.value) || 0));
    });

    // Restore old value
    if (input.value) setStars(parseInt(input.value));
});
</script>
@endsection
