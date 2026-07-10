@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<style>
    .users-table { width: 100%; border-collapse: collapse; }
    .users-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #8895aa;
        border-bottom: 2px solid #e8ecf1;
        background: #fafbfc;
    }
    .users-table td {
        padding: 14px 16px;
        font-size: 14px;
        color: #1a1a2e;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
    }
    .users-table tr:last-child td { border-bottom: none; }
    .users-table tr:hover td { background: #fafbfc; }

    .avatar-cell {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .role-pill {
        display: inline-flex;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }

    .role-customer { background: #e3f2fd; color: #0d47a1; }
    .role-provider { background: #e8f5e9; color: #2e7d32; }
    .role-admin    { background: #f3e5f5; color: #6a1b9a; }

    .role-select {
        padding: 6px 10px;
        border: 2px solid #e8ecf1;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        cursor: pointer;
        background: white;
        transition: border-color 0.2s;
    }
    .role-select:focus { border-color: #e94560; outline: none; }

    .btn-role-save {
        padding: 6px 14px;
        background: #e94560;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }
    .btn-role-save:hover { background: #c73652; }

    .btn-delete {
        padding: 6px 12px;
        background: white;
        color: #c53030;
        border: 2px solid #fed7d7;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }
    .btn-delete:hover { background: #fff5f5; border-color: #e94560; }

    .filter-bar {
        background: white;
        border: 1px solid #e8ecf1;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 20px;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-field { display: flex; flex-direction: column; gap: 6px; min-width: 160px; }
    .filter-field label { font-size: 12px; font-weight: 700; color: #8895aa; text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-field select,
    .filter-field input {
        padding: 9px 12px;
        border: 2px solid #e8ecf1;
        border-radius: 9px;
        font-size: 13px;
        font-family: inherit;
        outline: none;
        transition: border-color 0.2s;
    }
    .filter-field select:focus,
    .filter-field input:focus { border-color: #e94560; }
</style>

<div class="page-header">
    <div>
        <h1>👥 User Management</h1>
        <p>{{ $users->total() }} total users · change roles or remove accounts</p>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div style="background:#e8f5e9;color:#2e7d32;padding:14px 20px;border-radius:10px;margin-bottom:20px;font-weight:500;">
        ✅ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fff5f5;color:#c53030;padding:14px 20px;border-radius:10px;margin-bottom:20px;font-weight:500;">
        ⚠ {{ session('error') }}
    </div>
@endif

{{-- Filters --}}
<div class="filter-bar">
    <form action="{{ route('admin.users') }}" method="GET" style="display:contents;">
        <div class="filter-field">
            <label>Role</label>
            <select name="role">
                <option value="">All Roles</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field" style="flex:1; min-width:200px;">
            <label>Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email…">
        </div>
        <div class="filter-field">
            <label>Sort</label>
            <select name="sort">
                <option value="latest" {{ request('sort','latest') === 'latest' ? 'selected' : '' }}>Newest first</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest first</option>
            </select>
        </div>
        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn-role-save" style="padding:9px 20px;">Filter</button>
            <a href="{{ route('admin.users') }}" style="padding:9px 20px;background:#f0f2f5;color:#555;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Clear</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="content-card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="users-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Current Role</th>
                    <th>Joined</th>
                    <th>Change Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td style="padding-left:20px;">
                            <div class="avatar-cell">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $user->name }}</div>
                            @if($user->id === auth()->id())
                                <div style="font-size:11px;color:#e94560;font-weight:600;">● You</div>
                            @endif
                        </td>
                        <td style="color:#555;">{{ $user->email }}</td>
                        <td style="color:#8895aa;">{{ $user->phone ?? '—' }}</td>
                        <td>
                            <span class="role-pill role-{{ $user->role }}">
                                @if($user->role === 'admin') 🛡️
                                @elseif($user->role === 'provider') 🔧
                                @else 👤 @endif
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="color:#8895aa;">{{ $user->created_at->format('M d, Y') }}</td>

                        {{-- Change Role --}}
                        <td>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.update-role', $user) }}" method="POST"
                                      style="display:flex;gap:6px;align-items:center;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="role-select">
                                        <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="provider" {{ $user->role === 'provider' ? 'selected' : '' }}>Provider</option>
                                        <option value="admin"    {{ $user->role === 'admin'    ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-role-save">Save</button>
                                </form>
                            @else
                                <span style="font-size:12px;color:#8895aa;">Cannot change own role</span>
                            @endif
                        </td>

                        {{-- Delete --}}
                        <td>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">🗑 Delete</button>
                                </form>
                            @else
                                <span style="font-size:12px;color:#e8ecf1;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:#8895aa;">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:20px;">{{ $users->links() }}</div>

@endsection
