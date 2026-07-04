@extends('layouts.app')

@section('title', 'Leave a Review')

@section('content')
<div class="page-header">
    <div>
        <h1>⭐ Leave a Review</h1>
        <p>Rate your experience with {{ $serviceRequest->assignedProvider->name ?? 'the provider' }}</p>
    </div>
    <a href="{{ route('customer.dashboard') }}" class="btn-secondary">
        ← Back to Dashboard
    </a>
</div>

<div style="max-width: 600px;">
    <div class="content-card">
        <!-- Request Info -->
        <div style="background: #f8f9fa; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px;">
            <div style="font-weight: 600; font-size: 16px;">{{ $serviceRequest->title }}</div>
            <div style="font-size: 14px; color: #666;">Provider: {{ $serviceRequest->assignedProvider->name ?? 'N/A' }}</div>
            <div style="font-size: 14px; color: #666;">Budget: ETB {{ number_format($serviceRequest->budget, 2) }}</div>
        </div>

        <!-- Review Form -->
        <form action="{{ route('reviews.store', $serviceRequest->id) }}" method="POST">
            @csrf

            <!-- Rating -->
            <div class="form-group">
                <label for="rating">Rating *</label>
                <div style="display: flex; gap: 8px; font-size: 32px; cursor: pointer;" id="starContainer">
                    <span class="star" data-value="1">☆</span>
                    <span class="star" data-value="2">☆</span>
                    <span class="star" data-value="3">☆</span>
                    <span class="star" data-value="4">☆</span>
                    <span class="star" data-value="5">☆</span>
                </div>
                <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}" required>
                @error('rating')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Comment -->
            <div class="form-group">
                <label for="comment">Your Review *</label>
                <textarea name="comment" id="comment" class="form-control" 
                          rows="5" placeholder="Describe your experience with the provider..." required>{{ old('comment') }}</textarea>
                @error('comment')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary">⭐ Submit Review</button>
        </form>
    </div>
</div>

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
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #1a1a2e;
    font-size: 14px;
}
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e8ecf1;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s;
    background: #f8f9fa;
    box-sizing: border-box;
}
.form-control:focus {
    border-color: #e94560;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(233, 69, 96, 0.06);
    outline: none;
}
.error {
    color: #dc3545;
    font-size: 13px;
    margin-top: 4px;
    display: block;
}
.star {
    transition: all 0.2s;
}
.star.active {
    color: #ffc107;
}
.star:hover {
    transform: scale(1.2);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            ratingInput.value = value;
            
            stars.forEach(s => {
                s.classList.toggle('active', parseInt(s.dataset.value) <= value);
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const value = parseInt(this.dataset.value);
            stars.forEach(s => {
                s.classList.toggle('active', parseInt(s.dataset.value) <= value);
            });
        });
        
        star.addEventListener('mouseleave', function() {
            const selected = parseInt(ratingInput.value) || 0;
            stars.forEach(s => {
                s.classList.toggle('active', parseInt(s.dataset.value) <= selected);
            });
        });
    });
});
</script>
@endsection