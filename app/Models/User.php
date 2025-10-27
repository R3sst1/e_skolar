<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'email',
        'password',
        'role',
        'school',
        'phone_number',
        'barangay',
        'age',
        'siblings_boy',
        'siblings_girl',
        'mother_maiden_name',
        'father_name',
        'brother_names',
        'sister_names',
    ];

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
        'brother_names' => 'array',
        'sister_names' => 'array',
    ];

    /**
     * Get the login identifier based on input type.
     */
    public function findForLogin($login)
    {
        return static::where('email', $login)
            ->orWhere('username', $login)
            ->first();
    }

    // Role helper methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isApplicant()
    {
        return $this->role === 'applicant';
    }
    public function isScholar()
    {
        return $this->scholar()->exists();
    }

    // Relationships
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function scholar()
    {
        return $this->hasOne(Scholar::class);
    }
}
