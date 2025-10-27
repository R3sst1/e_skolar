<?php

namespace Database\Seeders;

use App\Models\Scholar;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScholarSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = [
            'University of San Carlos',
            'Cebu Institute of Technology',
            'University of Cebu',
            'Cebu Normal University',
            'University of San Jose-Recoletos'
        ];

        $courses = [
            'Bachelor of Science in Information Technology',
            'Bachelor of Science in Computer Science',
            'Bachelor of Science in Civil Engineering',
            'Bachelor of Science in Nursing',
            'Bachelor of Science in Accountancy',
            'Bachelor of Arts in Communication',
            'Bachelor of Science in Architecture',
            'Bachelor of Elementary Education',
            'Bachelor of Secondary Education',
            'Bachelor of Science in Tourism Management'
        ];

        $barangays = [
            'Poblacion',
            'Paligue',
            'San Roque',
            'Santo Niño',
            'San Miguel',
            'Santa Cruz',
            'San Jose',
            'San Antonio',
            'San Pedro',
            'San Pablo'
        ];

        // Get all applicant users
        $users = User::where('role', 'applicant')->get();

        foreach ($users as $user) {
            // 80% chance of being a scholar
            if (rand(1, 100) <= 80) {
                $status = rand(1, 100);
                // 70% active, 20% graduated, 10% discontinued
                $scholarStatus = $status <= 70 ? 'active' : ($status <= 90 ? 'graduated' : 'discontinued');
                
                $startDate = now()->subMonths(rand(1, 36))->format('Y-m-d');
                $endDate = null;
                
                if ($scholarStatus === 'graduated') {
                    $endDate = now()->subMonths(rand(1, 12))->format('Y-m-d');
                } elseif ($scholarStatus === 'discontinued') {
                    $endDate = now()->subMonths(rand(1, 24))->format('Y-m-d');
                }

                Scholar::create([
                    'user_id' => $user->id,
                    'institution' => $institutions[array_rand($institutions)],
                    'barangay' => $barangays[array_rand($barangays)],
                    'course' => $courses[array_rand($courses)],
                    'year_level' => rand(1, 4) . ' Year',
                    'status' => $scholarStatus,
                    'category' => rand(1, 100) <= 90 ? 'Student' : (rand(1, 100) <= 50 ? 'Master Degree' : 'Graduate'),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);
            }
        }
    }
} 