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

        $perPage = $request->get('per_page', 10);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

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

    public function create()
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return view('dashboardcontent.accounts.create-account');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,admin,applicant',
            'phone_number' => 'nullable|string|max:20',
            'barangay' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1|max:120',
        ]);

        try {
            // Generate unique username if needed
            $username = $this->generateUniqueUsername($request->username);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'barangay' => $request->barangay,
                'age' => $request->age,
            ]);

            return redirect()->route('accounts.index')
                ->with('success', 'Account created successfully for ' . $user->first_name . ' ' . $user->last_name);

        } catch (\Exception $e) {
            \Log::error('Error creating account: ' . $e->getMessage());
            return back()->with('error', 'Failed to create account. Please try again.')
                ->withInput();
        }
    }

    private function generateUniqueUsername($baseUsername)
    {
        $username = Str::slug($baseUsername);
        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }

    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Access denied. Super Admin privileges required.');
        }

        try {
            $user = User::findOrFail($id);
            
            // Prevent deletion of the last Super Admin
            if ($user->role === 'super_admin' && User::where('role', 'super_admin')->count() <= 1) {
                return back()->with('error', 'Cannot delete the last Super Admin account.');
            }

            // Prevent self-deletion
            if ($user->id === Auth::id()) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            $user->delete();

            return redirect()->route('accounts.index')
                ->with('success', 'Account deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Error deleting account: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete account. Please try again.');
        }
    }
} 