@extends('layouts.app')
@section('title', 'Edit Portfolio Item')

@section('content')
<style>
    .form-card { max-width:720px; background:white; border-radius:20px; border:1px solid #e8ecf1; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.04); }
    .section-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#8895aa; margin-bottom:16px; margin-top:28px; padding-bottom:8px; border-bottom:1px solid #f0f2f5; }
    .section-label:first-of-type { margin-top:0; }
    .field { margin-bottom:20px; }
    .field label { display:block; font-size:13px; font-weight:600; color:#555; margin-bottom:7px; }
    .field input, .field textarea { width:100%; padding:11px 14px; border:2px solid #e8ecf1; border-radius:10px; font-size:14px; font-family:inherit; color:#1a1a2e; background:#fafbfc; transition:all 0.2s; outline:none; }
    .field input:focus, .field textarea:focus { border-color:#e94560; background:white; box-shadow:0 0 0 4px rgba(233,69,96,0.06); }
    .field textarea { resize:vertical; min-height:100px; }
    .field-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .field-error { font-size:12px; color:#e94560; margin-top:5px; }
    .image-upload-area { border:2px dashed #e8ecf1; border-radius:12px; padding:24px; text-align:center; cursor:pointer; transition:all 0.2s; background:#fafbfc; position:relative; }
    .image-upload-area:hover { border-color:#e94560; background:#fff8f9; }
    .image-upload-area input[type="file"] { position:absolute; opacity:0; inset:0; cursor:pointer; }
    .featured-toggle { display:flex; align-items:center; gap:12px; padding:14px 16px; background:#f8f9fa; border-radius:10px; cursor:pointer; }
    .toggle-switch { position:relative; width:44px; height:24px; flex-shrink:0; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-slider { position:absolute; inset:0; background:#e8ecf1; border-radius:24px; transition:0.3s; cursor:pointer; }
    .toggle-slider:before { content:''; position:absolute; width:18px; height:18px; left:3px; top:3px; background:white; border-radius:50%; transition:0.3s; }
    .toggle-switch input:checked + .toggle-slider { background:#e94560; }
    .toggle-switch input:checked + .toggle-slider:before { transform:translateX(20px); }
    .btn-submit { width:100%; padding:15px; background:#e94560; color:white; border:none; border-radius:12px; font-size:16px; font-weight:700; cursor:pointer; transition:all 0.3s; font-family:inherit; margin-top:8px; }
    .btn-submit:hover { background:#c73652; transform:translateY(-2px); box-shadow:0 8px 25px rgba(233,69,96,0.35); }
    @media (max-width:640px) { .field-row { grid-template-columns:1fr; } }
</style>

<div style="margin-bottom:24px;">
    <a href="{{ route('portfolio.index') }}" style="color:#8895aa; text-decoration:none; font-size:14px;">← Back to Portfolio</a>
</div>

<div class="form-card">
    <h2 style="font-size:22px; font-weight:700; color:#1a1a2e; margin-bottom:4px;">Edit Portfolio Item</h2>
    <p style="color:#8895aa; font-size:14px; margin-bottom:28px;">Update your work details</p>

    @if($errors->any())
        <div style="background:#fff5f5; border:1px solid #fed7d7; border-left:4px solid #e94560; border-radius:10px; padding:14px; margin-bottom:24px; font-size:14px; color:#c53030;">
            @foreach($errors->all() as $error) <div>⚠ {{ $error }}</div> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('portfolio.update', $item) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="section-label">Work Details</div>

        <div class="field">
            <label for="title">Project / Job Title <span style="color:#e94560;">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $item->title) }}" required>
            @error('title') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $item->description) }}</textarea>
            @error('description') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        <div class="field-row">
            <div class="field">
                <label for="category">Service Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', $item->category) }}" placeholder="e.g. Electrical">
            </div>
            <div class="field">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" value="{{ old('location', $item->location) }}" placeholder="e.g. Bole">
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label for="price_from">Starting Price (ETB)</label>
                <input type="number" id="price_from" name="price_from" value="{{ old('price_from', $item->price_from) }}" min="0" step="0.01">
            </div>
            <div class="field">
                <label for="duration_days">Duration (days)</label>
                <input type="number" id="duration_days" name="duration_days" value="{{ old('duration_days', $item->duration_days) }}" min="1">
            </div>
        </div>

        <div class="section-label">Photo</div>

        @if($item->image_path)
            <div style="margin-bottom:16px;">
                <p style="font-size:13px; color:#555; margin-bottom:8px;">Current image:</p>
                <img src="{{ asset('storage/' . $item->image_path) }}" style="max-height:160px; border-radius:10px;" alt="Current">
            </div>
        @endif

        <div class="field">
            <label>{{ $item->image_path ? 'Replace Image' : 'Upload Image' }} <span style="color:#8895aa; font-weight:400;">(optional)</span></label>
            <div class="image-upload-area" id="uploadArea">
                <input type="file" name="image" accept="image/*" onchange="previewImage(this)">
                <div id="uploadPlaceholder">
                    <div style="font-size:32px; margin-bottom:6px;">📷</div>
                    <div style="font-weight:600; color:#555; font-size:14px;">Click to choose new image</div>
                </div>
                <img id="imagePreview" style="display:none; max-height:160px; border-radius:8px; margin:auto;" alt="Preview">
            </div>
            @error('image') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        <div class="section-label">Visibility</div>

        <label class="featured-toggle">
            <div class="toggle-switch">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </div>
            <div>
                <div style="font-weight:600; color:#1a1a2e; font-size:14px;">Mark as Featured</div>
                <div style="font-size:12px; color:#8895aa;">Featured items appear at the top of your public portfolio</div>
            </div>
        </label>

        <button type="submit" class="btn-submit" style="margin-top:28px;">💾 Save Changes</button>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; placeholder.style.display = 'none'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
