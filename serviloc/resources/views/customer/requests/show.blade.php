@extends('layouts.app')

@section('title', $request->title)

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $request->title }}</h1>
        <p class="text-sm text-gray-500">Posted {{ $request->created_at->diffForHumans() }}</p>
    </div>
    <a href="{{ route('customer.requests') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to My Requests
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Request Details -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h3>
            <p class="text-gray-600 leading-relaxed">{{ $request->description }}</p>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Category</p>
                    <p class="font-medium text-gray-900">{{ $request->category->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Location</p>
                    <p class="font-medium text-gray-900">{{ $request->location }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Budget</p>
                    <p class="font-bold text-blue-600 text-lg">ETB {{ number_format($request->budget, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                        {{ $request->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $request->status === 'assigned' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $request->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $request->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                    ">{{ ucfirst($request->status) }}</span>
                </div>
                @if($request->scheduled_date)
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Preferred Date</p>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($request->scheduled_date)->format('M d, Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Posted</p>
                    <p class="font-medium text-gray-900">{{ $request->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Offers Section -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Offers ({{ $request->offers->count() }})</h3>
            </div>

            @if($request->offers->count() > 0)
                <div class="space-y-4">
                    @foreach($request->offers as $offer)
                        <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $offer->provider->name }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $offer->message }}</p>
                                    <p class="text-xs text-gray-400 mt-2">Sent {{ $offer->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-blue-600 text-lg">ETB {{ number_format($offer->price, 2) }}</p>
                                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                        {{ $offer->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $offer->status === 'accepted' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $offer->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                    ">{{ ucfirst($offer->status) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">No offers received yet</p>
                    <p class="text-sm text-gray-400">Providers will send offers soon!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sticky top-24">
            <h4 class="font-semibold text-gray-900 mb-4">Quick Actions</h4>

            @if($request->status === 'open')
                <button onclick="alert('Are you sure you want to cancel this request?')" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                    Cancel Request
                </button>
            @endif

            <a href="{{ route('customer.requests') }}" class="block w-full mt-3 px-4 py-2 text-center bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                View All Requests
            </a>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400"><span class="font-medium">Need help?</span> Contact support if you have any questions.</p>
            </div>
        </div>
    </div>
</div>

@endsection
