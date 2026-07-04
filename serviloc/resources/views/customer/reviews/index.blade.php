@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="page-header">
    <div>
        <h1>⭐ My Reviews</h1>
        <p>Reviews you've given to providers</p>
    </div>
</div>

<div class="content-card" style="text-align: center; padding: 60px 20px;">
    <div style="font-size: 64px; margin-bottom: 16px;">⭐</div>
    <h3 style="color: #1a1a2e; margin-bottom: 8px;">No reviews yet</h3>
    <p style="color: #8895aa;">Complete a request and leave a review for the provider.</p>
    <a href="{{ route('customer.dashboard') }}" class="btn-primary" style="display: inline-block; margin-top: 16px;">
        Go to Dashboard
    </a>
</div>
@endsection