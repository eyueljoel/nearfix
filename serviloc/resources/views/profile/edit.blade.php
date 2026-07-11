@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--red:#dc2626;--r:12px;}
    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:18px;transition:color 0.18s;}
    .back-link:hover{color:var(--blue);}
    .form-wrap{max-width:680px;}
    .form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:28px 32px;}
    .form-card h2{font-size:18px;font-weight:800;color:var(--text);letter-spacing:-0.2px;margin-bottom:4px;}
    .form-card .sub{font-size:13.5px;color:var(--muted);margin-bottom:24px;}
    .section-sep{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--muted);margin:22px 0 14px;padding-bottom:8px;border-bottom:1px solid var(--border);}
    .f-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    .f-group{margin-bottom:18px;}
    .f-group label{display:block;font-size:12.5px;font-weight:600;color:var(--text);margin-bottom:6px;}
    .f-ctrl{width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:9px;font-size:13.5px;font-family:inherit;color:var(--text);background:var(--bg);transition:all 0.18s;outline:none;box-sizing:border-box;}
    .f-ctrl:focus{border-color:var(--blue);background:var(--white);box-shadow:0 0 0 3px rgba(14,165,233,0.12);}
    textarea.f-ctrl{resize:vertical;min-height:90px;line-height:1.6;}
    .f-error{font-size:12px;color:var(--red);margin-top:4px;}
    .form-actions{display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);}
    .btn-save{padding:10px 26px;background:var(--blue);color:white;border:none;border-radius:var(--r);font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-save:hover{background:var(--blue-dk);transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,0.35);}
    .btn-back{padding:10px 20px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:var(--r);font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;transition:all 0.18s;}
    .btn-back:hover{border-color:var(--muted);color:var(--text);}
    @media(max-width:580px){.f-row{grid-template-columns:1fr;}.form-card{padding:22px 18px;}}
</style>

<a href="{{ route('profile.show') }}" class="back-link">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Profile
</a>

<div class="form-wrap">
    <div class="form-card">
        <h2>Edit Profile</h2>
        <p class="sub">Update your personal information and account details</p>

        @if(session('success'))
            <div style="background:var(--green-lt,#ecfdf5);border:1px solid #a7f3d0;border-left:4px solid var(--green);border-radius:9px;padding:12px 14px;margin-bottom:20px;font-size:13.5px;color:var(--green);font-weight:500;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fca5a5;border-left:4px solid var(--red);border-radius:9px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:var(--red);">
                @foreach($errors->all() as $e)<div>⚠ {{ $e }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')

            <div class="section-sep">Personal Information</div>

            <div class="f-row">
                <div class="f-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" class="f-ctrl" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="f-error">⚠ {{ $message }}</div>@enderror
                </div>
                <div class="f-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="f-ctrl" value="{{ old('phone', $user->phone) }}" placeholder="+251 9XX XXX XXX">
                    @error('phone')<div class="f-error">⚠ {{ $message }}</div>@enderror
                </div>
            </div>

            <div class="f-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="f-ctrl" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="f-group">
                <label for="address">Address / Location</label>
                <input type="text" name="address" id="address" class="f-ctrl" value="{{ old('address', $user->address) }}" placeholder="e.g. Bole, Addis Ababa">
                @error('address')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="f-group">
                <label for="bio">Bio / About Me</label>
                <textarea name="bio" id="bio" class="f-ctrl" rows="3" placeholder="Tell customers about yourself or your skills...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save Changes</button>
                <a href="{{ route('profile.show') }}" class="btn-back">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
