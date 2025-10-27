<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            // BarangaySeeder::class,
            // SystemSettingsSeeder::class,
            // InstitutionSeeder::class,
            // UserSeeder::class,
            // ScholarSeeder::class,
            // DisbursementSeeder::class,
            // ScholarPerformanceSeeder::class,
        ]);
    }
}
