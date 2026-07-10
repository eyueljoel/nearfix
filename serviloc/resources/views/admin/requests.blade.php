@extends('layouts.app')

@section('title', 'All Service Requests')

@section('content')
<div class="page-header">
    <div>
        <h1>📋 All Service Requests</h1>
        <p>Total: {{ $requests->total() }} requests</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
    <form action="{{ route('admin.requests') }}" method="GET" class="flex gap-4 flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
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
            <a href="{{ route('admin.requests') }}" class="px-6 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Requests Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Budget</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">#{{ $request->id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs truncate">{{ $request->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $request->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $request->category->name }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600">ETB {{ number_format($request->budget, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                {{ $request->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $request->status === 'assigned' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $request->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $request->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                            ">{{ ucfirst($request->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No requests found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $requests->links() }}
</div>
@endsection
