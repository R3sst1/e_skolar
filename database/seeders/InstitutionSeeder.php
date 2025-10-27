<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Scholar;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        // Get unique institutions from existing scholars
        $existingInstitutions = Scholar::select('institution')
            ->distinct()
            ->whereNotNull('institution')
            ->where('institution', '!=', '')
            ->pluck('institution');

        // Add existing institutions
        foreach ($existingInstitutions as $institutionName) {
            Institution::firstOrCreate(
                ['name' => $institutionName],
                [
                    'type' => 'university',
                    'is_active' => true,
                ]
            );
        }

        // Add some additional institutions if none exist
        if (Institution::count() === 0) {
            $institutions = [
                [
                    'name' => 'Davao Del Sur State College',
                    'type' => 'college',
                    'address' => 'Mati, Davao Oriental',
                    'contact_person' => 'Registrar',
                    'contact_email' => 'registrar@dssc.edu.ph',
                    'contact_phone' => '+63 87 123 4567',
                    'description' => 'State college in Davao Oriental',
                    'is_active' => true,
                ],
                [
                    'name' => 'University of Mindanao',
                    'type' => 'university',
                    'address' => 'Davao City',
                    'contact_person' => 'Admissions Office',
                    'contact_email' => 'admissions@umindanao.edu.ph',
                    'contact_phone' => '+63 82 234 5678',
                    'description' => 'Private university in Davao City',
                    'is_active' => true,
                ],
                [
                    'name' => 'University of San Carlos',
                    'type' => 'university',
                    'address' => 'Cebu City',
                    'contact_person' => 'Student Affairs',
                    'contact_email' => 'studentaffairs@usc.edu.ph',
                    'contact_phone' => '+63 32 345 6789',
                    'description' => 'Private university in Cebu',
                    'is_active' => true,
                ],
                [
                    'name' => 'Cebu Institute of Technology',
                    'type' => 'university',
                    'address' => 'Cebu City',
                    'contact_person' => 'Registrar',
                    'contact_email' => 'registrar@cit.edu.ph',
                    'contact_phone' => '+63 32 456 7890',
                    'description' => 'Private university in Cebu',
                    'is_active' => true,
                ],
                [
                    'name' => 'University of Cebu',
                    'type' => 'university',
                    'address' => 'Cebu City',
                    'contact_person' => 'Admissions',
                    'contact_email' => 'admissions@uc.edu.ph',
                    'contact_phone' => '+63 32 567 8901',
                    'description' => 'Private university in Cebu',
                    'is_active' => true,
                ],
                [
                    'name' => 'Cebu Normal University',
                    'type' => 'university',
                    'address' => 'Cebu City',
                    'contact_person' => 'Student Services',
                    'contact_email' => 'studentservices@cnu.edu.ph',
                    'contact_phone' => '+63 32 678 9012',
                    'description' => 'State university in Cebu',
                    'is_active' => true,
                ],
                [
                    'name' => 'University of San Jose-Recoletos',
                    'type' => 'university',
                    'address' => 'Cebu City',
                    'contact_person' => 'Registrar',
                    'contact_email' => 'registrar@usjr.edu.ph',
                    'contact_phone' => '+63 32 789 0123',
                    'description' => 'Private university in Cebu',
                    'is_active' => true,
                ],
            ];

            foreach ($institutions as $institution) {
                Institution::create($institution);
            }
        }

        $this->command->info('Institutions seeded successfully!');
    }
}
