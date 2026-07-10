@extends('layouts.app')

@section('title', 'My Service Requests')

@section('content')
<div class="page-header">
    <div>
        <h1>📝 My Service Requests</h1>
        <p>Total: {{ $requests->count() }} requests</p>
    </div>
    <a href="{{ route('customer.requests.create') }}" class="btn-primary">
        ➕ Post New Request
    </a>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #28a745;">
        {{ session('success') }}
    </div>
@endif

@if($requests->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Request Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Offers</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <a href="{{ route('customer.requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $request->title }}
                                </a>
                            </td>
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
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                    {{ $request->offers->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('customer.requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="content-card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 16px;">📭</div>
        <h3 style="color: #1a1a2e; margin-bottom: 8px;">No requests yet</h3>
        <p style="color: #8895aa; margin-bottom: 20px;">Post your first service request to get started!</p>
        <a href="{{ route('customer.requests.create') }}" class="btn-primary" style="display: inline-block;">
            ➕ Post a Request
        </a>
    </div>
@endif
@endsection
