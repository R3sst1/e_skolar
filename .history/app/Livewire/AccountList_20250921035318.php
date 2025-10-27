<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AccountList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteUser($userId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'You do not have permission to delete users.');
            return;
        }

        $user = User::findOrFail($userId);
        
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        // Prevent deleting the last super admin
        if ($user->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1) {
            session()->flash('error', 'Cannot delete the last Super Admin account.');
            return;
        }

        try {
            $user->delete();
            session()->flash('success', 'User account deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user account: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('dashboardcontent.accounts.index', [
            'users' => $users,
        ])->layout('layouts.app', [
            'title' => 'Account Management'
        ]);
    }
}
