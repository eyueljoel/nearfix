@extends('layouts.app')
@section('title', 'Post a Request')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--red:#dc2626;--r:12px;}
    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:18px;transition:color 0.18s;}
    .back-link:hover{color:var(--blue);}
    .form-wrap{max-width:720px;}
    .form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:28px 32px;}
    .form-head{margin-bottom:24px;}
    .form-head h2{font-size:18px;font-weight:800;color:var(--text);letter-spacing:-0.2px;}
    .form-head p{font-size:13.5px;color:var(--muted);margin-top:3px;}
    .section-sep{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--muted);margin:22px 0 14px;padding-bottom:8px;border-bottom:1px solid var(--border);}
    .f-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    .f-group{margin-bottom:18px;}
    .f-group label{display:block;font-size:12.5px;font-weight:600;color:var(--text);margin-bottom:6px;}
    .f-group label .req{color:var(--blue);}
    .f-group label .hint{color:var(--muted);font-weight:400;font-size:11.5px;margin-left:4px;}
    .f-ctrl{width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:9px;font-size:13.5px;font-family:inherit;color:var(--text);background:var(--bg);transition:all 0.18s;outline:none;box-sizing:border-box;}
    .f-ctrl:focus{border-color:var(--blue);background:var(--white);box-shadow:0 0 0 3px rgba(14,165,233,0.12);}
    .f-ctrl::placeholder{color:var(--muted);}
    textarea.f-ctrl{resize:vertical;min-height:100px;line-height:1.6;}
    .f-error{font-size:12px;color:var(--red);margin-top:4px;display:flex;align-items:center;gap:4px;}
    .form-actions{display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);}
    .btn-submit{padding:11px 28px;background:var(--blue);color:white;border:none;border-radius:var(--r);font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;}
    .btn-submit:hover{background:var(--blue-dk);transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,0.35);}
    .btn-cancel{padding:11px 20px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:var(--r);font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;transition:all 0.18s;}
    .btn-cancel:hover{border-color:var(--muted);color:var(--text);}
    .tip-box{background:var(--blue-lt);border:1px solid #bfdbfe;border-radius:9px;padding:12px 14px;margin-bottom:22px;font-size:12.5px;color:#1e40af;display:flex;gap:10px;align-items:flex-start;}
    .tip-box svg{flex-shrink:0;margin-top:1px;}
    @media(max-width:580px){.f-row{grid-template-columns:1fr;}.form-card{padding:22px 18px;}}
</style>

<a href="{{ route('customer.requests') }}" class="back-link">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back to My Requests
</a>

<div class="form-wrap">
    <div class="tip-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>The more detail you provide, the better offers you'll receive. Providers can see your budget, so set it realistically.</div>
    </div>

    <div class="form-card">
        <div class="form-head">
            <h2>Post a Service Request</h2>
            <p>Describe what you need and get competitive offers from verified providers</p>
        </div>

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fca5a5;border-left:4px solid var(--red);border-radius:9px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:var(--red);">
                @foreach($errors->all() as $e)<div>⚠ {{ $e }}</div>@endforeach
            </div>
        @endif

        <form action="{{ route('customer.requests.store') }}" method="POST">
            @csrf

            <div class="section-sep">Service Details</div>

            <div class="f-group">
                <label for="title">Request Title <span class="req">*</span></label>
                <input type="text" name="title" id="title" class="f-ctrl"
                       placeholder="e.g., Need a plumber to fix kitchen sink"
                       value="{{ old('title') }}" required>
                @error('title')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="f-group">
                <label for="category_id">Category <span class="req">*</span></label>
                <select name="category_id" id="category_id" class="f-ctrl" required>
                    <option value="">Select a service category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="f-group">
                <label for="description">Description <span class="req">*</span> <span class="hint">(min 20 characters)</span></label>
                <textarea name="description" id="description" class="f-ctrl" rows="5"
                          placeholder="Describe what you need in detail: what's the problem, what materials are required, any specific requirements..." required>{{ old('description') }}</textarea>
                @error('description')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="section-sep">Budget & Location</div>

            <div class="f-row">
                <div class="f-group">
                    <label for="budget">Budget <span class="req">*</span> <span class="hint">(ETB)</span></label>
                    <input type="number" name="budget" id="budget" class="f-ctrl"
                           placeholder="e.g. 1500" value="{{ old('budget') }}" min="1" required>
                    @error('budget')<div class="f-error">⚠ {{ $message }}</div>@enderror
                </div>
                <div class="f-group">
                    <label for="location">Location <span class="req">*</span></label>
                    <input type="text" name="location" id="location" class="f-ctrl"
                           placeholder="e.g. Bole, Addis Ababa" value="{{ old('location') }}" required>
                    @error('location')<div class="f-error">⚠ {{ $message }}</div>@enderror
                </div>
            </div>

            <div class="f-group">
                <label for="scheduled_date">Preferred Date <span class="hint">(optional)</span></label>
                <input type="date" name="scheduled_date" id="scheduled_date" class="f-ctrl"
                       value="{{ old('scheduled_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                @error('scheduled_date')<div class="f-error">⚠ {{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Post Request →</button>
                <a href="{{ route('customer.requests') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
