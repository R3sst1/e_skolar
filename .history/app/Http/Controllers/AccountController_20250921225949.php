<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        // Temporarily bypass permission check for debugging
        // if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
        //     return redirect()->route('dashboard');
        // }

        $query = User::where('id', '!=', Auth::id());

        // Handle search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Debug: Log the user count
        \Log::info('AccountController: Found ' . $users->count() . ' users');

        if ($request->ajax()) {
            return view('dashboardcontent.accounts._users_list', compact('users'))->render();
        }

        return view('dashboardcontent.accounts.index', compact('users'));
    }

    public function promoteToAdmin($id)
    {
        try {
            if (!Auth::user()->isSuperAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $user = User::findOrFail($id);
            
            if ($user->role === 'super_admin') {
                return response()->json(['error' => 'Cannot modify Super Admin role'], 403);
            }

            $user->role = 'admin';
            $user->save();

            return response()->json(['success' => true, 'message' => 'User promoted to Admin successfully']);
        } catch (\Exception $e) {
            \Log::error('Error promoting user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update user role'], 500);
        }
    }

    public function demoteToApplicant($id)
    {
        try {
            if (!Auth::user()->isSuperAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $user = User::findOrFail($id);
            
            if ($user->role === 'super_admin') {
                return response()->json(['error' => 'Cannot modify Super Admin role'], 403);
            }

            $user->role = 'applicant';
            $user->save();

            return response()->json(['success' => true, 'message' => 'User demoted to Applicant successfully']);
        } catch (\Exception $e) {
            \Log::error('Error demoting user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update user role'], 500);
        }
    }
} 