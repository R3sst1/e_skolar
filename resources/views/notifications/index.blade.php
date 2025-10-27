@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Notifications</h2>
    @if($notifications->isNotEmpty())
    <button onclick="markAllAsRead()" class="btn btn-outline-secondary">
        <i data-lucide="check" class="w-4 h-4 mr-2"></i>
        Mark all as read
    </button>
    @endif
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            @if($notifications->isEmpty())
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i data-lucide="bell" class="w-12 h-12 mx-auto text-slate-400"></i>
                </div>
                <div class="text-slate-500 mb-4">No notifications found</div>
                <div class="text-slate-400 text-sm">You're all caught up!</div>
            </div>
            @else
            <div class="p-5">
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                    <div id="notification-{{ $notification->id }}" 
                         class="flex items-start p-4 {{ is_null($notification->read_at) ? 'bg-primary/5 border-primary/20' : 'bg-slate-50 border-slate-200' }} border rounded-lg">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ is_null($notification->read_at) ? 'bg-primary text-white' : 'bg-slate-200 text-slate-500' }} mr-3">
                            @if(isset($notification->data['type']))
                                @if($notification->data['type'] === 'application_approved')
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                @elseif($notification->data['type'] === 'application_rejected')
                                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                                @elseif($notification->data['type'] === 'application_under_review')
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                @elseif($notification->data['type'] === 'additional_requirements')
                                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                @else
                                    <i data-lucide="bell" class="w-5 h-5"></i>
                                @endif
                            @else
                                <i data-lucide="bell" class="w-5 h-5"></i>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-medium text-slate-900">
                                    {{ $notification->data['title'] ?? 'Application Update' }}
                                </h3>
                                <div class="flex items-center">
                                    <small class="text-slate-500 mr-3">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    @if(is_null($notification->read_at))
                                    <button onclick="markAsRead('{{ $notification->id }}')" 
                                            class="text-slate-400 hover:text-slate-600 transition-colors">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="text-slate-600 mb-3">
                                {{ $notification->data['message'] ?? 'There has been an update to your application.' }}
                            </p>
                            
                            @if(isset($notification->data['application_id']))
                            <div class="flex items-center">
                                <a href="{{ route('applications.status', $notification->data['application_id']) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    View Application
                                </a>
                                @if(isset($notification->data['status']) && $notification->data['status'] === 'needs_additional_requirements')
                                <a href="{{ route('applications.create') }}" 
                                   class="btn btn-sm btn-warning ml-2">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                    Update Application
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function markAsRead(id) {
    fetch(`/notifications/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.getElementById(`notification-${id}`);
            notification.classList.remove('bg-primary/5', 'border-primary/20');
            notification.classList.add('bg-slate-50', 'border-slate-200');
            
            // Update the icon background
            const iconContainer = notification.querySelector('.w-10.h-10');
            iconContainer.classList.remove('bg-primary', 'text-white');
            iconContainer.classList.add('bg-slate-200', 'text-slate-500');
            
            // Remove the mark as read button
            const markReadButton = notification.querySelector('button');
            if (markReadButton) markReadButton.remove();
            
            updateUnreadCount();
        }
    });
}

function markAllAsRead() {
    fetch(`/notifications/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all notifications to read state
            document.querySelectorAll('.bg-primary\\/5, .border-primary\\/20').forEach(el => {
                el.classList.remove('bg-primary/5', 'border-primary/20');
                el.classList.add('bg-slate-50', 'border-slate-200');
                
                const iconContainer = el.querySelector('.w-10.h-10');
                if (iconContainer) {
                    iconContainer.classList.remove('bg-primary', 'text-white');
                    iconContainer.classList.add('bg-slate-200', 'text-slate-500');
                }
                
                const markReadButton = el.querySelector('button');
                if (markReadButton) markReadButton.remove();
            });
            
            updateUnreadCount();
        }
    });
}

function updateUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notification-count');
            if (countElement) {
                countElement.textContent = data.count;
                countElement.style.display = data.count > 0 ? 'block' : 'none';
            }
            
            // Update sidebar badge
            const sidebarBadge = document.querySelector('.side-menu__badge');
            if (sidebarBadge) {
                if (data.count > 0) {
                    sidebarBadge.textContent = data.count;
                    sidebarBadge.style.display = 'block';
                } else {
                    sidebarBadge.style.display = 'none';
                }
            }
        });
}
</script>
@endsection 