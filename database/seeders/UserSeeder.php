<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create some sample applicants
        $applicants = [
            [
                'first_name' => 'John',
                'middle_name' => 'Michael',
                'last_name' => 'Doe',
                'username' => 'johndoe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'school' => 'University of San Carlos',
                'barangay' => 'Poblacion',
                'age' => 20,
                'phone_number' => '09123456789',
            ],
            [
                'first_name' => 'Jane',
                'middle_name' => 'Elizabeth',
                'last_name' => 'Smith',
                'username' => 'janesmith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'school' => 'Cebu Institute of Technology',
                'barangay' => 'San Roque',
                'age' => 19,
                'phone_number' => '09187654321',
            ],
            [
                'first_name' => 'Mike',
                'middle_name' => 'Robert',
                'last_name' => 'Johnson',
                'username' => 'mikejohnson',
                'email' => 'mike.johnson@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'school' => 'University of Cebu',
                'barangay' => 'Santo Niño',
                'age' => 21,
                'phone_number' => '09234567890',
            ],
            [
                'first_name' => 'Sarah',
                'middle_name' => 'Anne',
                'last_name' => 'Williams',
                'username' => 'sarahwilliams',
                'email' => 'sarah.williams@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'school' => 'Cebu Normal University',
                'barangay' => 'San Miguel',
                'age' => 20,
                'phone_number' => '09345678901',
            ],
            [
                'first_name' => 'David',
                'middle_name' => 'Thomas',
                'last_name' => 'Brown',
                'username' => 'davidbrown',
                'email' => 'david.brown@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'school' => 'University of San Jose-Recoletos',
                'barangay' => 'Santa Cruz',
                'age' => 22,
                'phone_number' => '09456789012',
            ],
        ];

        foreach ($applicants as $applicant) {
            User::create($applicant);
        }

        // Create some sample admins
        $admins = [
            [
                'first_name' => 'Admin',
                'middle_name' => 'User',
                'last_name' => 'One',
                'username' => 'admin1',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'school' => 'Sulop Municipal Office',
                'barangay' => 'Poblacion',
                'age' => 35,
                'phone_number' => '09567890123',
            ],
            [
                'first_name' => 'Admin',
                'middle_name' => 'User',
                'last_name' => 'Two',
                'username' => 'admin2',
                'email' => 'admin2@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'school' => 'Sulop Municipal Office',
                'barangay' => 'San Roque',
                'age' => 40,
                'phone_number' => '09678901234',
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }
    }
} 