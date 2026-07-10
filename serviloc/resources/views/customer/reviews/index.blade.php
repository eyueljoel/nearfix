@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="page-header">
    <div>
        <h1>⭐ My Reviews</h1>
        <p>Reviews you've given to providers</p>
    </div>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #28a745;">
        {{ session('success') }}
    </div>
@endif

<!-- Filters and Sorting -->
@if($reviews->total() > 0)
    <div class="content-card" style="margin-bottom: 20px;">
        <form method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <!-- Rating Filter -->
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #666; margin-bottom: 6px;">
                    Rating
                </label>
                <select name="rating" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars</option>
                    <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Stars</option>
                    <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>⭐⭐⭐ 3 Stars</option>
                    <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>⭐⭐ 2 Stars</option>
                    <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>⭐ 1 Star</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #666; margin-bottom: 6px;">
                    Sort By
                </label>
                <select name="sort" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>

            <button type="submit" style="padding: 8px 20px; background: #e94560; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
                Filter
            </button>

            @if(request('rating') || request('sort') !== 'latest')
                <a href="{{ route('customer.reviews') }}" style="padding: 8px 20px; background: #6c757d; color: white; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none; transition: all 0.3s;">
                    Clear
                </a>
            @endif
        </form>
    </div>
@endif

@if($reviews->count() > 0)
    <div style="display: grid; gap: 16px;">
        @foreach($reviews as $review)
            <div class="content-card">
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                    <div style="flex: 1;">
                        <!-- Provider Info -->
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div style="width: 48px; height: 48px; background: #e8ecf1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600; color: #e94560;">
                                {{ strtoupper(substr($review->reviewee->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 style="font-size: 16px; margin: 0; color: #1a1a2e;">{{ $review->reviewee->name }}</h3>
                                <div style="font-size: 13px; color: #8895aa;">Provider</div>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div style="margin-bottom: 8px;">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= $review->rating ? '#ffc107' : '#e8ecf1' }}; font-size: 16px;">⭐</span>
                            @endfor
                            <span style="margin-left: 8px; font-weight: 600; color: #1a1a2e;">{{ $review->rating }}/5</span>
                        </div>

                        <!-- Service Request -->
                        <div style="font-size: 13px; color: #666; margin-bottom: 8px;">
                            <strong>Service:</strong> {{ $review->serviceRequest->title }}
                        </div>

                        <!-- Review Comment -->
                        <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-top: 12px; border-left: 3px solid #e94560;">
                            <p style="font-size: 14px; color: #333; margin: 0; line-height: 1.5;">
                                {{ $review->comment }}
                            </p>
                        </div>

                        <!-- Review Date -->
                        <div style="font-size: 13px; color: #8895aa; margin-top: 8px;">
                            📅 {{ $review->created_at->format('M d, Y') }} at {{ $review->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $reviews->links() }}
    </div>
@else
    <div class="content-card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 16px;">⭐</div>
        <h3 style="color: #1a1a2e; margin-bottom: 8px; margin-top: 0;">
            @if(request('rating'))
                No reviews with this rating
            @else
                No reviews yet
            @endif
        </h3>
        <p style="color: #8895aa; margin: 0;">
            @if(request('rating'))
                Try adjusting your filters
            @else
                Complete a service request and leave a review for the provider
            @endif
        </p>
        @if(!request('rating'))
            <a href="{{ route('customer.dashboard') }}" class="btn-primary" style="display: inline-block; margin-top: 16px; text-decoration: none;">
                Go to Dashboard
            </a>
        @endif
    </div>
@endif

<style>
.btn-primary {
    background: #e94560;
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary:hover {
    background: #d63547;
    transform: translateY(-2px);
}
</style>
@endsection
