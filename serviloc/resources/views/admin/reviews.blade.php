@extends('layouts.app')

@section('title', 'All Reviews')

@section('content')
<div class="page-header">
    <div>
        <h1>⭐ All Reviews</h1>
        <p>Total: {{ $reviews->total() }} reviews</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
    <form action="{{ route('admin.reviews') }}" method="GET" class="flex gap-4 flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
            <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Ratings</option>
                @foreach($ratings as $rating)
                    <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                        {{ str_repeat('⭐', $rating) }} ({{ $rating }} stars)
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Sort</label>
            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest First</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.reviews') }}" class="px-6 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Reviews Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Reviewer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Reviewee</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Comment</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">#{{ $review->id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $review->reviewer->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $review->reviewee->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-yellow-500 font-semibold">{{ str_repeat('⭐', $review->rating) }}</span>
                            <span class="text-gray-600 text-xs ml-2">{{ $review->rating }}/5</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $review->comment }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $review->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No reviews found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $reviews->links() }}
</div>
@endsection
