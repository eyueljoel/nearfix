@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--yellow-lt:#fffbeb;--red:#dc2626;--r:14px;}
    .profile-layout{display:grid;grid-template-columns:280px 1fr;gap:22px;align-items:start;}
    .p-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:24px;margin-bottom:16px;}
    /* Avatar */
    .avatar-wrap{text-align:center;padding-bottom:20px;border-bottom:1px solid var(--border);margin-bottom:18px;}
    .avatar-lg{width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:white;margin:0 auto 14px;border:3px solid var(--blue-lt);}
    .av-name{font-size:18px;font-weight:800;color:var(--text);margin-bottom:4px;}
    .av-role{display:inline-flex;align-items:center;gap:5px;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;margin-bottom:10px;}
    .rp-customer{background:var(--blue-lt);color:var(--blue-dk);}
    .rp-provider{background:var(--green-lt);color:var(--green);}
    .rp-admin{background:#f3e5f5;color:#6a1b9a;}
    .verified-badge{display:inline-flex;align-items:center;gap:5px;background:var(--green-lt);color:var(--green);border:1px solid #a7f3d0;padding:3px 11px;border-radius:20px;font-size:11.5px;font-weight:700;}
    /* Stats */
    .stat-row{display:grid;grid-template-columns:repeat(3,1fr);gap:4px;margin-top:14px;}
    .stat-cell{text-align:center;padding:10px 4px;}
    .sc-val{font-size:20px;font-weight:800;color:var(--text);}
    .sc-lbl{font-size:11px;color:var(--muted);margin-top:1px;}
    /* Quick actions */
    .btn-edit{display:block;width:100%;padding:10px;background:var(--blue);color:white;border:none;border-radius:10px;font-size:13.5px;font-weight:700;text-decoration:none;text-align:center;cursor:pointer;font-family:inherit;transition:all 0.2s;margin-top:16px;}
    .btn-edit:hover{background:var(--blue-dk);transform:translateY(-1px);}
    /* Info grid */
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    .info-item .ii-label{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--muted);margin-bottom:4px;}
    .info-item .ii-val{font-size:14px;font-weight:600;color:var(--text);}
    .info-item .ii-val.empty{color:var(--muted);font-weight:400;font-style:italic;}
    .sec-title{font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);}
    /* Activity rows */
    .act-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);gap:12px;}
    .act-row:last-child{border-bottom:none;}
    .act-title{font-size:13.5px;font-weight:600;color:var(--text);}
    .act-time{font-size:12px;color:var(--muted);margin-top:2px;}
    .s-badge{display:inline-flex;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
    .st-open{background:var(--blue-lt);color:var(--blue-dk);}
    .st-assigned{background:var(--yellow-lt);color:var(--yellow);}
    .st-in_progress{background:#fef3c7;color:#92400e;}
    .st-completed{background:var(--green-lt);color:var(--green);}
    .st-cancelled{background:#fef2f2;color:var(--red);}
    @media(max-width:900px){.profile-layout{grid-template-columns:1fr;}}
    @media(max-width:640px){.info-grid{grid-template-columns:1fr;}}
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;">My Profile</h1>
        <p style="font-size:13.5px;color:var(--muted);margin-top:2px;">Manage your account information</p>
    </div>
    <a href="{{ route('profile.edit') }}" style="display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:var(--blue);color:white;border-radius:12px;font-size:13.5px;font-weight:600;text-decoration:none;transition:all 0.2s;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit Profile
    </a>
</div>

<div class="profile-layout">
    {{-- Left column --}}
    <div>
        <div class="p-card">
            <div class="avatar-wrap">
                <div class="avatar-lg">{{ strtoupper(substr($user->name,0,2)) }}</div>
                <div class="av-name">{{ $user->name }}</div>
                <div>
                    <span class="av-role rp-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </div>
                @if($user->is_verified)
                    <div style="margin-top:6px;">
                        <span class="verified-badge">✓ Verified</span>
                    </div>
                @endif
            </div>

            <div class="stat-row">
                <div class="stat-cell">
                    <div class="sc-val">{{ $user->serviceRequests->count() }}</div>
                    <div class="sc-lbl">Requests</div>
                </div>
                <div class="stat-cell" style="border-left:1px solid var(--border);border-right:1px solid var(--border);">
                    <div class="sc-val">{{ $user->offers->count() }}</div>
                    <div class="sc-lbl">Offers</div>
                </div>
                <div class="stat-cell">
                    <div class="sc-val">{{ $user->reviewsReceived->count() }}</div>
                    <div class="sc-lbl">Reviews</div>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn-edit">Edit Profile</a>

            @if($user->role === 'provider')
                <a href="{{ route('portfolio.index') }}" style="display:block;width:100%;padding:9px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;text-align:center;margin-top:8px;transition:all 0.18s;">
                    🗂️ My Portfolio
                </a>
            @endif
        </div>

        <div class="p-card">
            <div class="sec-title">Account Info</div>
            <div style="font-size:13px;color:var(--muted);line-height:2;">
                <div>📅 Joined {{ $user->created_at->format('M Y') }}</div>
                <div>📧 {{ $user->email }}</div>
                @if($user->phone)<div>📱 {{ $user->phone }}</div>@endif
                @if($user->address)<div>📍 {{ $user->address }}</div>@endif
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div>
        <div class="p-card">
            <div class="sec-title">Personal Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="ii-label">Full Name</div>
                    <div class="ii-val">{{ $user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="ii-label">Email</div>
                    <div class="ii-val">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="ii-label">Phone</div>
                    <div class="ii-val {{ !$user->phone ? 'empty' : '' }}">{{ $user->phone ?? 'Not provided' }}</div>
                </div>
                <div class="info-item">
                    <div class="ii-label">Role</div>
                    <div class="ii-val">{{ ucfirst($user->role) }}</div>
                </div>
                <div class="info-item" style="grid-column:span 2;">
                    <div class="ii-label">Address</div>
                    <div class="ii-val {{ !$user->address ? 'empty' : '' }}">{{ $user->address ?? 'Not provided' }}</div>
                </div>
                @if($user->bio)
                <div class="info-item" style="grid-column:span 2;">
                    <div class="ii-label">Bio</div>
                    <div class="ii-val" style="font-weight:400;line-height:1.65;">{{ $user->bio }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="p-card">
            <div class="sec-title">Recent Activity</div>
            @php $recentRequests = $user->serviceRequests()->latest()->take(5)->get(); @endphp
            @forelse($recentRequests as $req)
                <div class="act-row">
                    <div>
                        <div class="act-title">{{ $req->title }}</div>
                        <div class="act-time">{{ $req->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="s-badge st-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
                </div>
            @empty
                <div style="text-align:center;padding:24px;color:var(--muted);font-size:13.5px;">No activity yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
