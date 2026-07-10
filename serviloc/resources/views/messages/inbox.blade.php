@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="page-header">
    <div>
        <h1>💬 Messages</h1>
        <p>Your conversations with customers and providers</p>
    </div>
</div>

@if($conversations->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <!-- Conversations List -->
        <div class="divide-y divide-gray-200">
            @foreach($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation->service_request_id) }}" 
                   class="block hover:bg-gray-50 transition-colors duration-150 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <!-- Left side: Avatar and message info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($conversation->other_user->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900">{{ $conversation->other_user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $conversation->service_request->title }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm truncate">{{ $conversation->last_message_preview }}</p>
                        </div>

                        <!-- Right side: Time and unread count -->
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-gray-500 mb-2">{{ $conversation->last_message_at->diffForHumans() }}</p>
                            @if($conversation->unread_count > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white">
                                    {{ $conversation->unread_count }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs text-gray-400">
                                    ✓
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($conversations->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $conversations->links() }}
            </div>
        @endif
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
        <div class="text-6xl mb-4">💬</div>
        <h3 class="text-2xl font-semibold text-gray-900 mb-2">No conversations yet</h3>
        <p class="text-gray-600 mb-6">Start messaging with providers or customers about service requests.</p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('customer.requests') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                📋 View Requests
            </a>
            <a href="{{ route('offers.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                🤝 View Offers
            </a>
        </div>
    </div>
@endif
@endsection
