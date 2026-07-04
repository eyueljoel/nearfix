@extends('layouts.app')

@section('title', 'Post a Service Request')

@section('content')
<div class="page-header">
    <div>
        <h1>📝 Post a Service Request</h1>
        <p>Describe what service you need and get offers from providers</p>
    </div>
    <a href="{{ route('customer.requests') }}" class="btn-secondary">
        ← Back to My Requests
    </a>
</div>

<div style="max-width: 800px;">
    <div class="content-card">
        <form action="{{ route('customer.requests.store') }}" method="POST">
            @csrf

            <!-- Title -->
            <div class="form-group">
                <label for="title">Request Title *</label>
                <input type="text" name="title" id="title" class="form-control" 
                       placeholder="e.g., Need a plumber to fix kitchen sink"
                       value="{{ old('title') }}" required>
                @error('title')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category_id">Category *</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea name="description" id="description" class="form-control" 
                          rows="5" placeholder="Describe your service request in detail..." required>{{ old('description') }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Budget & Location Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="budget">Budget (ETB) *</label>
                    <input type="number" name="budget" id="budget" class="form-control" 
                           placeholder="e.g., 1500" value="{{ old('budget') }}" required>
                    @error('budget')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" name="location" id="location" class="form-control" 
                           placeholder="e.g., Addis Ababa, Bole" value="{{ old('location') }}" required>
                    @error('location')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Scheduled Date -->
            <div class="form-group">
                <label for="scheduled_date">Preferred Date</label>
                <input type="date" name="scheduled_date" id="scheduled_date" class="form-control" 
                       value="{{ old('scheduled_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                @error('scheduled_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-primary">✅ Post Request</button>
                <a href="{{ route('customer.requests') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

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
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #1a1a2e;
    font-size: 14px;
}
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e8ecf1;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s;
    background: #f8f9fa;
    box-sizing: border-box;
}
.form-control:focus {
    border-color: #e94560;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(233, 69, 96, 0.06);
    outline: none;
}
.error {
    color: #dc3545;
    font-size: 13px;
    margin-top: 4px;
    display: block;
}
</style>
@endsection