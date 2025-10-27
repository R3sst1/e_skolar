<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Super',
            'middle_name' => 'Admin',
            'last_name' => 'Account',
            'username' => 'superadmin',
            'password' => Hash::make('admin123'), // You should change this in production
            'role' => 'super_admin',
        ]);
    }
} 