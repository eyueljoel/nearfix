@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <div>
        <h1>👤 My Profile</h1>
        <p>View and manage your profile information</p>
    </div>
    <a href="{{ route('profile.edit') }}" class="btn-primary">
        ✏️ Edit Profile
    </a>
</div>

<div style="display: grid; grid-template-columns: 300px 1fr; gap: 24px;">
    <!-- Profile Card -->
    <div class="content-card" style="text-align: center;">
        <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 48px; color: white; font-weight: 700;">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <h2 style="font-size: 22px; color: #1a1a2e;">{{ $user->name }}</h2>
        <p style="color: #8895aa; font-size: 14px;">{{ ucfirst($user->role) }}</p>
        
        @if($user->is_verified)
            <span style="display: inline-block; background: #28a745; color: white; padding: 2px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-top: 4px;">
                ✅ Verified
            </span>
        @endif
        
        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f2f5;">
            <div style="display: flex; justify-content: space-around;">
                <div>
                    <div style="font-size: 20px; font-weight: 700; color: #1a1a2e;">{{ $user->serviceRequests->count() }}</div>
                    <div style="font-size: 12px; color: #8895aa;">Requests</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: 700; color: #1a1a2e;">{{ $user->offers->count() }}</div>
                    <div style="font-size: 12px; color: #8895aa;">Offers</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: 700; color: #1a1a2e;">{{ $user->reviewsReceived->count() }}</div>
                    <div style="font-size: 12px; color: #8895aa;">Reviews</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div>
        <div class="content-card">
            <h3 style="margin-bottom: 16px;">📋 Personal Information</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Full Name</div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $user->name }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Email</div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Phone</div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $user->phone ?? 'Not provided' }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #8895aa;">Role</div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ ucfirst($user->role) }}</div>
                </div>
                <div style="grid-column: 1 / -1;">
                    <div style="font-size: 12px; color: #8895aa;">Address</div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $user->address ?? 'Not provided' }}</div>
                </div>
                @if($user->bio)
                <div style="grid-column: 1 / -1;">
                    <div style="font-size: 12px; color: #8895aa;">About Me</div>
                    <div style="font-weight: 400; color: #444; line-height: 1.6;">{{ $user->bio }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="content-card">
            <h3 style="margin-bottom: 16px;">📝 Recent Activity</h3>
            
            @php
                $recentRequests = $user->serviceRequests()->latest()->take(3)->get();
            @endphp
            
            @if($recentRequests->count() > 0)
                @foreach($recentRequests as $request)
                    <div style="padding: 10px 0; border-bottom: 1px solid #f0f2f5;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-weight: 600; font-size: 14px;">{{ $request->title }}</div>
                                <div style="font-size: 13px; color: #8895aa;">{{ $request->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="badge badge-{{ $request->status }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color: #8895aa; text-align: center; padding: 20px 0;">No recent activity</p>
            @endif
        </div>
    </div>
</div>
@endsection