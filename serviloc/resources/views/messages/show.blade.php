@extends('layouts.app')

@section('title', 'Message with ' . $otherUser->name)

@section('content')
<div class="page-header">
    <div>
        <h1>💬 Message with {{ $otherUser->name }}</h1>
        <p class="text-sm text-gray-500">{{ $serviceRequest->title }}</p>
    </div>
    <a href="{{ route('messages.inbox') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Messages
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Message Thread -->
    <div class="lg:col-span-2">
        <!-- Message Thread Container -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
            <!-- Header with request info -->
            <div class="pb-6 border-b border-gray-100 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Service Request</p>
                        <p class="font-semibold text-gray-900">{{ $serviceRequest->title }}</p>
                    </div>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                        {{ $serviceRequest->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $serviceRequest->status === 'assigned' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $serviceRequest->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $serviceRequest->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                    ">{{ ucfirst($serviceRequest->status) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-600">Budget</p>
                        <p class="font-semibold text-gray-900">ETB {{ number_format($serviceRequest->budget, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Location</p>
                        <p class="font-semibold text-gray-900">{{ $serviceRequest->location }}</p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="space-y-6 mb-6" style="max-height: 600px; overflow-y: auto;">
                @forelse($messages as $message)
                    <div class="flex gap-4 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $message->sender_id === auth()->id() ? 'from-blue-400 to-blue-600' : 'from-green-400 to-green-600' }} flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr($message->sender->name, 0, 2)) }}
                            </div>
                        </div>

                        <!-- Message Bubble -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-semibold text-gray-900 text-sm">{{ $message->sender->name }}</p>
                                <p class="text-xs text-gray-500">{{ $message->created_at->format('M d, g:i A') }}</p>
                                @if($message->sender_id !== auth()->id() && $message->isReadBy(auth()->user()))
                                    <p class="text-xs text-green-600 ml-auto">✓ Read</p>
                                @endif
                            </div>
                            <div class="p-4 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-100 text-gray-900' : 'bg-gray-100 text-gray-900' }}">
                                <p class="text-sm break-words">{{ $message->body }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-gray-500">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination for messages (if many) -->
            @if($messages->hasPages())
                <div class="border-t border-gray-200 pt-4">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>

        <!-- Message Form -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form action="{{ route('messages.store') }}" method="POST">
                @csrf
                <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                <input type="hidden" name="recipient_id" value="{{ $otherUser->id }}">

                <div class="mb-4">
                    <label for="body" class="block text-sm font-medium text-gray-900 mb-2">Your Message</label>
                    <textarea 
                        id="body"
                        name="body"
                        rows="4"
                        placeholder="Type your message here..."
                        class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all {{ $errors->has('body') ? 'border-red-500' : '' }}"
                        required
                    ></textarea>

                    @if($errors->has('body'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->first('body') }}</p>
                    @endif

                    <div class="flex items-center justify-between mt-2">
                        <p class="text-xs text-gray-500">
                            <span id="charCount">0</span> / 2000 characters
                        </p>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" id="sendBtn" disabled>
                            Send Message
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar: Request Details -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sticky top-24">
            <h4 class="font-semibold text-gray-900 mb-4">Request Details</h4>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wider">Category</p>
                    <p class="font-medium text-gray-900">{{ $serviceRequest->category->name }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wider">Budget</p>
                    <p class="font-semibold text-blue-600 text-lg">ETB {{ number_format($serviceRequest->budget, 2) }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wider">Status</p>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                        {{ $serviceRequest->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $serviceRequest->status === 'assigned' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $serviceRequest->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $serviceRequest->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                    ">{{ ucfirst($serviceRequest->status) }}</span>
                </div>

                @if($serviceRequest->scheduled_date)
                    <div>
                        <p class="text-xs text-gray-600 uppercase tracking-wider">Preferred Date</p>
                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($serviceRequest->scheduled_date)->format('M d, Y') }}</p>
                    </div>
                @endif

                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('customer.requests.show', $serviceRequest->id) }}" class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        View Full Request
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to bottom
            const threadContainer = document.querySelector('[style*="max-height"]');
            if (threadContainer) {
                threadContainer.scrollTop = threadContainer.scrollHeight;
            }
        });
    </script>
@endif

<script>
    // Character counter
    const textarea = document.getElementById('body');
    const charCount = document.getElementById('charCount');
    const sendBtn = document.getElementById('sendBtn');

    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
        sendBtn.disabled = this.value.trim().length === 0;
    });

    // Disable send button on load if empty
    sendBtn.disabled = textarea.value.trim().length === 0;
</script>
@endsection
