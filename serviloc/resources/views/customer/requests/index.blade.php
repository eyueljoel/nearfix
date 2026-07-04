@extends('layouts.app')

@section('title', 'My Requests')

@section('content')
<div class="page-header">
    <div>
        <h1>📝 My Service Requests</h1>
        <p>View and manage all your service requests</p>
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
    <div class="content-card">
        @foreach($requests as $request)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f2f5;">
                <div>
                    <div style="font-weight: 600; font-size: 16px; color: #1a1a2e;">
                        <a href="{{ route('customer.requests.show', $request->id) }}" style="color: #1a1a2e; text-decoration: none;">
                            {{ $request->title }}
                        </a>
                    </div>
                    <div style="font-size: 14px; color: #8895aa; margin-top: 4px;">
                        {{ $request->category->name }} • {{ $request->location }}
                    </div>
                    <div style="font-size: 13px; color: #8895aa; margin-top: 4px;">
                        Posted: {{ $request->created_at->diffForHumans() }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="badge badge-{{ $request->status }}">
                        {{ ucfirst($request->status) }}
                    </span>
                    <span style="font-weight: 700; color: #1a1a2e; font-size: 16px;">
                        ETB {{ number_format($request->budget, 2) }}
                    </span>
                    <a href="{{ route('customer.requests.show', $request->id) }}" style="color: #e94560; text-decoration: none; font-weight: 600;">
                        View →
                    </a>
                </div>
            </div>
        @endforeach
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