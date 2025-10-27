@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mobile-dashboard">
    <!-- Welcome Section -->
    <div class="intro-y mb-6">
        <div class="box p-4 bg-gradient-to-r from-primary to-primary-dark text-white">
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-lg font-medium">Welcome back, {{ Auth::user()->first_name }}!</h2>
                    <p class="text-sm opacity-90">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="text-right">
                    <i data-lucide="sun" class="w-8 h-8"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        @if(Auth::user()->isApplicant())
        <div class="box p-4 text-center">
            <div class="text-2xl font-bold text-primary">{{ $stats['applications'] ?? 0 }}</div>
            <div class="text-sm text-slate-500">Applications</div>
        </div>
        <div class="box p-4 text-center">
            <div class="text-2xl font-bold text-success">{{ $stats['disbursements'] ?? 0 }}</div>
            <div class="text-sm text-slate-500">Disbursements</div>
        </div>
        @else
        <div class="box p-4 text-center">
            <div class="text-2xl font-bold text-primary">{{ $stats['total_applicants'] ?? 0 }}</div>
            <div class="text-sm text-slate-500">Applicants</div>
        </div>
        <div class="box p-4 text-center">
            <div class="text-2xl font-bold text-success">{{ $stats['total_scholars'] ?? 0 }}</div>
            <div class="text-sm text-slate-500">Scholars</div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="mb-6">
        <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            @if(Auth::user()->isApplicant())
            <a href="{{ route('applications.create') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="file-plus" class="w-8 h-8 mx-auto mb-2 text-primary"></i>
                    <div class="text-sm font-medium">Apply for Scholarship</div>
                </div>
            </a>
            <a href="{{ route('feedback.create') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="message-square" class="w-8 h-8 mx-auto mb-2 text-success"></i>
                    <div class="text-sm font-medium">Submit Feedback</div>
                </div>
            </a>
            <a href="{{ route('applications.index') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="file-text" class="w-8 h-8 mx-auto mb-2 text-warning"></i>
                    <div class="text-sm font-medium">My Applications</div>
                </div>
            </a>
            <a href="{{ route('disbursements.scholar') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="credit-card" class="w-8 h-8 mx-auto mb-2 text-info"></i>
                    <div class="text-sm font-medium">My Disbursements</div>
                </div>
            </a>
            @else
            <a href="{{ route('applications.applicants') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="user-plus" class="w-8 h-8 mx-auto mb-2 text-primary"></i>
                    <div class="text-sm font-medium">Review Applicants</div>
                </div>
            </a>
            <a href="{{ route('disbursements.create') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="credit-card" class="w-8 h-8 mx-auto mb-2 text-success"></i>
                    <div class="text-sm font-medium">New Disbursement</div>
                </div>
            </a>
            <a href="{{ route('feedback.admin.index') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="message-square" class="w-8 h-8 mx-auto mb-2 text-warning"></i>
                    <div class="text-sm font-medium">Review Feedback</div>
                </div>
            </a>
            <a href="{{ route('scholar-performance.index') }}" class="quick-action-card">
                <div class="box p-4 text-center hover:bg-slate-50 transition-colors">
                    <i data-lucide="trending-up" class="w-8 h-8 mx-auto mb-2 text-info"></i>
                    <div class="text-sm font-medium">Performance</div>
                </div>
            </a>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mb-6">
        <h3 class="text-lg font-medium mb-4">Recent Activity</h3>
        <div class="box">
            <div class="p-4">
                @if(isset($recent_activities) && count($recent_activities) > 0)
                    @foreach($recent_activities as $activity)
                    <div class="flex items-center py-3 border-b border-slate-200 last:border-b-0">
                        <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium">{{ $activity->description }}</div>
                            <div class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-slate-500">
                        <i data-lucide="activity" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <div>No recent activity</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @if(Auth::user()->unreadNotifications->count() > 0)
    <div class="mb-6">
        <h3 class="text-lg font-medium mb-4">Notifications</h3>
        <div class="box">
            <div class="p-4">
                @foreach(Auth::user()->unreadNotifications->take(3) as $notification)
                <div class="flex items-center py-3 border-b border-slate-200 last:border-b-0">
                    <div class="w-2 h-2 bg-danger rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium">{{ $notification->data['title'] ?? 'New notification' }}</div>
                        <div class="text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
                <div class="text-center pt-3">
                    <a href="{{ route('notifications.index') }}" class="text-sm text-primary">View all notifications</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Install App Banner -->
    <div id="install-banner" class="hidden mb-6">
        <div class="box p-4 bg-primary text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium">Install SureScholarShip</h4>
                    <p class="text-sm opacity-90">Get quick access to your scholarship management</p>
                </div>
                <button onclick="installApp()" class="btn btn-sm btn-white">
                    Install
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.mobile-dashboard {
    padding: 1rem;
}

.quick-action-card {
    text-decoration: none;
    color: inherit;
}

.quick-action-card:hover {
    text-decoration: none;
    color: inherit;
}

@media (max-width: 768px) {
    .mobile-dashboard {
        padding: 0.5rem;
    }
    
    .grid {
        gap: 0.5rem;
    }
    
    .box {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
// Show install banner if app can be installed
if (typeof deferredPrompt !== 'undefined') {
    document.getElementById('install-banner').classList.remove('hidden');
}

// Add touch feedback for mobile
document.querySelectorAll('.quick-action-card').forEach(card => {
    card.addEventListener('touchstart', function() {
        this.style.transform = 'scale(0.95)';
    });
    
    card.addEventListener('touchend', function() {
        this.style.transform = 'scale(1)';
    });
});
</script>
@endsection 