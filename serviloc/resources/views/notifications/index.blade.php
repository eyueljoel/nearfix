@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<style>
    .notification-item {
        display: flex;
        gap: 16px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .notification-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateX(4px);
    }
    
    .notification-item.unread {
        background: #f0f7ff;
        border-color: #e94560;
        border-left: 4px solid #e94560;
    }
    
    .notification-icon {
        font-size: 32px;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f0f2f5;
        flex-shrink: 0;
    }
    
    .notification-item.unread .notification-icon {
        background: #ffe8ed;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 700;
        font-size: 16px;
        color: #1a1a2e;
        margin-bottom: 4px;
    }
    
    .notification-message {
        color: #555;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 8px;
    }
    
    .notification-meta {
        display: flex;
        gap: 16px;
        font-size: 12px;
        color: #8895aa;
    }
    
    .notification-actions {
        display: flex;
        gap: 8px;
        align-items: flex-start;
    }
    
    .btn-icon {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.2s;
        color: #8895aa;
    }
    
    .btn-icon:hover {
        background: #f0f2f5;
        color: #1a1a2e;
    }
    
    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
        cursor: pointer;
    }
    
    .btn-primary-action {
        background: #e94560;
        color: white;
        border: none;
    }
    
    .btn-primary-action:hover {
        background: #c73652;
        transform: translateY(-1px);
    }
    
    .btn-secondary-action {
        background: #f0f2f5;
        color: #555;
        border: none;
    }
    
    .btn-secondary-action:hover {
        background: #e8ecf1;
    }
    
    .empty-state {
        text-align: center;
        padding: 64px 32px;
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
    }
    
    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .header-actions {
        display: flex;
        gap: 12px;
    }
</style>

<div class="page-header">
    <div>
        <h1>📬 Notifications</h1>
        <p>Stay updated with all your activity</p>
    </div>
    
    <div class="header-actions">
        <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-action btn-secondary-action">
                ✓ Mark All Read
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div style="background: #e8f5e9; color: #2e7d32; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

@if($notifications->count() > 0)
    @foreach($notifications as $notification)
        <div class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
            <div class="notification-icon">
                @if($notification->data['type'] === 'offer_received')
                    💰
                @elseif($notification->data['type'] === 'offer_status_changed')
                    @if($notification->data['status'] === 'accepted')
                        🎉
                    @else
                        ℹ️
                    @endif
                @elseif($notification->data['type'] === 'new_message')
                    💬
                @else
                    🔔
                @endif
            </div>
            
            <div class="notification-content">
                <div class="notification-title">
                    {{ $notification->data['title'] ?? 'Notification' }}
                </div>
                <div class="notification-message">
                    {{ $notification->data['message'] ?? 'You have a new notification.' }}
                </div>
                <div class="notification-meta">
                    <span>⏰ {{ $notification->created_at->diffForHumans() }}</span>
                    @if(is_null($notification->read_at))
                        <span style="color: #e94560; font-weight: 600;">● Unread</span>
                    @endif
                </div>
            </div>
            
            <div class="notification-actions">
                @if(isset($notification->data['action_url']))
                    <a href="{{ route('notifications.read', $notification->id) }}" class="btn-action btn-primary-action">
                        View
                    </a>
                @endif
                
                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon" title="Delete" onclick="return confirm('Delete this notification?')">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
    @endforeach
    
    <div style="margin-top: 32px;">
        {{ $notifications->links() }}
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-icon">🔕</div>
        <h3 style="font-size: 20px; font-weight: 600; color: #1a1a2e; margin-bottom: 8px;">
            All Caught Up!
        </h3>
        <p style="color: #8895aa; font-size: 15px;">
            You don't have any notifications at the moment.
        </p>
    </div>
@endif
@endsection
