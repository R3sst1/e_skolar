<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display the user's notifications.
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notifications as read.
     */
    public function markAsRead(Request $request)
    {
        if ($request->has('id')) {
            Auth::user()
                ->notifications()
                ->where('id', $request->id)
                ->update(['read_at' => now()]);
        } else {
            Auth::user()
                ->unreadNotifications()
                ->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }
} 