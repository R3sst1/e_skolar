<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isApplicant()) {
            // Get applicant-specific data
            $applications = $user->applications()->latest()->take(3)->get();
            $notifications = $user->notifications()->latest()->take(3)->get();
            
            $stats = [
                'total_applications' => $user->applications()->count(),
                'approved_applications' => $user->applications()->where('status', 'approved')->count(),
                'pending_applications' => $user->applications()->whereIn('status', ['pending', 'under_review'])->count(),
                'unread_notifications' => $user->unreadNotifications()->count(),
            ];
            
            return view('dashboard.index', compact('applications', 'notifications', 'stats'));
        }
        
        // For admin/super admin, just return the basic dashboard
        return view('dashboard.index');
    }
} 