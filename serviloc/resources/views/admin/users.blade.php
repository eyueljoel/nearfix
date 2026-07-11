@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<style>
    :root{--blue:#0ea5e9;--blue-dk:#2563eb;--blue-lt:#eff6ff;--white:#fff;--bg:#f8fafc;--border:#e2e8f0;--text:#0f172a;--muted:#64748b;--green:#059669;--green-lt:#ecfdf5;--yellow:#d97706;--red:#dc2626;--r:12px;}
    .pg-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;}
    .pg-hd-l{display:flex;align-items:center;gap:10px;}
    .pg-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.3px;}
    .count-chip{background:var(--blue-lt);color:var(--blue-dk);font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #bfdbfe;}
    .filter-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:18px;display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;}
    .f-field{flex:1;min-width:140px;display:flex;flex-direction:column;gap:5px;}
    .f-field label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--muted);}
    .f-field select,.f-field input{padding:8px 11px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text);background:var(--bg);font-family:inherit;outline:none;transition:border-color 0.18s;}
    .f-field select:focus,.f-field input:focus{border-color:var(--blue);}
    .btn-f{padding:8px 18px;background:var(--blue);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;white-space:nowrap;}
    .btn-f:hover{background:var(--blue-dk);}
    .btn-cl{padding:8px 13px;background:var(--bg);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;white-space:nowrap;}
    .btn-cl:hover{border-color:var(--muted);color:var(--text);}
    .t-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;}
    .users-table{width:100%;border-collapse:collapse;}
    .users-table th{text-align:left;padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--muted);border-bottom:2px solid var(--border);background:var(--bg);white-space:nowrap;}
    .users-table td{padding:12px 16px;font-size:13.5px;color:var(--text);border-bottom:1px solid var(--border);vertical-align:middle;}
    .users-table tr:last-child td{border-bottom:none;}
    .users-table tr:hover td{background:#fafbfc;}
    .u-av{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dk));color:white;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .role-pill{display:inline-flex;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;}
    .rp-customer{background:var(--blue-lt);color:var(--blue-dk);}
    .rp-provider{background:var(--green-lt);color:var(--green);}
    .rp-admin{background:#f3e5f5;color:#6a1b9a;}
    .role-sel{padding:6px 10px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;background:var(--white);outline:none;transition:border-color 0.18s;}
    .role-sel:focus{border-color:var(--blue);}
    .btn-save{padding:6px 14px;background:var(--blue);color:white;border:none;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer;font-family:inherit;transition:background 0.18s;white-space:nowrap;}
    .btn-save:hover{background:var(--blue-dk);}
    .btn-del{padding:6px 12px;background:var(--white);color:var(--red);border:1.5px solid #fca5a5;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.18s;white-space:nowrap;}
    .btn-del:hover{background:#fef2f2;}
    .you-badge{font-size:11px;color:var(--blue);font-weight:600;}
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
            <button type="submit" class="btn-f" style="padding:9px 20px;">Filter</button>
            <a href="{{ route('admin.users') }}" class="btn-cl">Clear</a>
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
                            <div class="u-av">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $user->name }}</div>
                            @if($user->id === auth()->id())
                                <div class="you-badge">● You</div>
                            @endif
                        </td>
                        <td style="color:var(--muted);">{{ $user->email }}</td>
                        <td style="color:var(--muted);">{{ $user->phone ?? '—' }}</td>
                        <td>
                            <span class="role-pill rp-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="color:var(--muted);font-size:12.5px;">{{ $user->created_at->format('M d, Y') }}</td>

                        {{-- Change Role --}}
                        <td>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.update-role', $user) }}" method="POST"
                                      style="display:flex;gap:6px;align-items:center;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="role-sel">
                                        <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="provider" {{ $user->role === 'provider' ? 'selected' : '' }}>Provider</option>
                                        <option value="admin"    {{ $user->role === 'admin'    ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-save">Save</button>
                                </form>
                            @else
                                <span style="font-size:12px;color:var(--muted);">Cannot change own role</span>
                            @endif
                        </td>

                        {{-- Delete --}}
                        <td>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-del">Delete</button>
                                </form>
                            @else
                                <span style="font-size:12px;color:var(--muted);">—</span>
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
