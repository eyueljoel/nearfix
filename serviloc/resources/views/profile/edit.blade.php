@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="page-header">
    <div>
        <h1>✏️ Edit Profile</h1>
        <p>Update your profile information</p>
    </div>
    <a href="{{ route('profile.show') }}" class="btn-secondary">
        ← Back to Profile
    </a>
</div>

<div style="max-width: 700px;">
    <div class="content-card">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}">
                @error('address')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="bio">About Me (Bio)</label>
                <textarea name="bio" id="bio" class="form-control" rows="4">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-primary">💾 Save Changes</button>
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