@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="page-header">
    <div>
        <h1>🔍 Search Results</h1>
        <p>Found {{ $serviceRequests->count() }} service request(s)</p>
    </div>
</div>

<!-- Search Filters -->
<div class="content-card" style="margin-bottom: 24px;">
    <form action="{{ route('search') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Search</label>
            <input type="text" name="q" class="form-control" placeholder="Search..." value="{{ $query }}">
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label>Category</label>
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label>Location</label>
            <input type="text" name="location" class="form-control" placeholder="Location..." value="{{ $location }}">
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label>Min Budget</label>
            <input type="number" name="min_budget" class="form-control" placeholder="Min" value="{{ $minBudget }}">
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label>Max Budget</label>
            <input type="number" name="max_budget" class="form-control" placeholder="Max" value="{{ $maxBudget }}">
        </div>
        
        <div style="display: flex; align-items: end; gap: 8px;">
            <button type="submit" class="btn-primary" style="padding: 10px 24px; font-size: 14px;">🔍 Search</button>
            <a href="{{ route('search') }}" class="btn-secondary" style="padding: 10px 24px; font-size: 14px;">Clear</a>
        </div>
    </form>
</div>

<!-- Results -->
@if($serviceRequests->count() > 0)
    <div class="content-card">
        @foreach($serviceRequests as $request)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f2f5;">
                <div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $request->title }}</div>
                    <div style="font-size: 13px; color: #8895aa;">
                        {{ $request->category->name ?? 'Uncategorized' }} • {{ $request->location }}
                    </div>
                    <div style="font-size: 13px; color: #8895aa;">
                        Posted by: {{ $request->user->name ?? 'Unknown' }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="badge badge-{{ $request->status }}">
                        {{ ucfirst($request->status) }}
                    </span>
                    <span style="font-weight: 600; color: #1a1a2e;">
                        ETB {{ number_format($request->budget, 2) }}
                    </span>
                    <a href="{{ route('customer.requests.show', $request->id) }}" class="btn-primary" style="padding: 6px 16px; font-size: 13px; text-decoration: none; display: inline-block;">
                        View →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="content-card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 16px;">🔍</div>
        <h3 style="color: #1a1a2e; margin-bottom: 8px;">No results found</h3>
        <p style="color: #8895aa;">Try adjusting your search filters.</p>
    </div>
@endif

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
    display: inline-block;
}
.btn-secondary:hover {
    background: #5a6268;
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 2px solid #e8ecf1;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.3s;
    background: #f8f9fa;
    box-sizing: border-box;
}
.form-control:focus {
    border-color: #e94560;
    outline: none;
}
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 4px;
    color: #1a1a2e;
    font-size: 13px;
}
</style>
@endsection