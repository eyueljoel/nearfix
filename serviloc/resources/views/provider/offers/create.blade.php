@extends('layouts.app')

@section('title', 'Send Offer')

@section('content')
<div class="page-header">
    <div>
        <h1>💬 Send an Offer</h1>
        <p>Respond to: {{ $serviceRequest->title }}</p>
    </div>
    <a href="{{ route('provider.dashboard') }}" class="btn-secondary">
        ← Back to Dashboard
    </a>
</div>

<div style="max-width: 700px;">
    <div class="content-card">
        <!-- Request Info -->
        <div style="background: #f8f9fa; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Customer</div>
                    <div style="font-weight: 600;">{{ $serviceRequest->user->name }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Category</div>
                    <div style="font-weight: 600;">{{ $serviceRequest->category->name }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Budget</div>
                    <div style="font-weight: 700; color: #e94560;">ETB {{ number_format($serviceRequest->budget, 2) }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Location</div>
                    <div style="font-weight: 600;">{{ $serviceRequest->location }}</div>
                </div>
            </div>
        </div>

        <!-- Offer Form -->
        <form action="{{ route('offers.store', $serviceRequest->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="price">Your Price (ETB) *</label>
                <input type="number" name="price" id="price" class="form-control" 
                       placeholder="e.g., 1200" value="{{ old('price') }}" required>
                @error('price')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="message">Message *</label>
                <textarea name="message" id="message" class="form-control" 
                          rows="5" placeholder="Describe why you're the best fit..." required>{{ old('message') }}</textarea>
                @error('message')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" class="btn-primary">✅ Send Offer</button>
                <a href="{{ route('provider.dashboard') }}" class="btn-secondary">Cancel</a>
            </div>
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
</style>
@endsection