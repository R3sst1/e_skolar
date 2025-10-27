<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\ResidenceData;
use App\Models\Etala\DemographicIdentifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateAccount extends Component
{
    public $residenceData;
    public $username = '';
    public $password = 'password123';
    public $confirmPassword = 'password123';
    public $email = '';

    protected $rules = [
        'username' => 'required|string|max:255|unique:users,username',
        'password' => 'required|string|min:6',
        'confirmPassword' => 'required|same:password',
        'email' => 'nullable|email|unique:users,email',
    ];

    protected $messages = [
        'username.required' => 'Username is required.',
        'username.unique' => 'This username is already taken.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 6 characters.',
        'confirmPassword.same' => 'Password confirmation does not match.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
    ];

    public function mount($residenceDataId)
    {
        $this->residenceData = ResidenceData::findOrFail($residenceDataId);
        
        // Check if account already exists
        if ($this->residenceData->account_created) {
            session()->flash('error', 'Account for this applicant has already been created.');
            return redirect()->route('residence-data.index');
        }

        // Pre-fill username with full name
        $this->username = $this->generateUsername();
        $this->email = $this->residenceData->email ?? '';
    }

    public function generateUsername()
    {
        $firstName = Str::slug($this->residenceData->first_name ?? '');
        $lastName = Str::slug($this->residenceData->last_name ?? '');
        
        if ($firstName && $lastName) {
            $baseUsername = $firstName . '.' . $lastName;
        } else {
            $baseUsername = Str::slug($this->residenceData->full_name ?? 'applicant');
        }

        // Check if username exists and add number if needed
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    public function createAccount()
    {
        $this->validate();

        try {
            // Create the user account
            $user = User::create([
                'first_name' => $this->residenceData->first_name ?? '',
                'middle_name' => $this->residenceData->middle_name ?? '',
                'last_name' => $this->residenceData->last_name ?? '',
                'username' => $this->username,
                'email' => $this->email ?: null,
                'password' => Hash::make($this->password),
                'role' => 'applicant',
                'phone_number' => $this->residenceData->contact_number ?? '',
                'barangay' => $this->residenceData->barangay ?? '',
                'age' => $this->residenceData->age ?? null,
            ]);

            // Update residence data to mark account as created
            $this->residenceData->update([
                'user_id' => $user->id,
                'account_created' => true,
                'account_created_at' => now(),
            ]);

            session()->flash('success', 'Account created successfully for ' . $this->residenceData->full_name . '. Username: ' . $this->username);
            
            return redirect()->route('accounts.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create account: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('dashboardcontent.accounts.create-account');
    }
}
