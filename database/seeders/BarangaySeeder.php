<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        $barangays = [
            'Balasinon',
            'Buguis',
            'Carre',
            'Clib',
            'Harada Butai',
            'Katipunan',
            'Kiblagon',
            'Labon',
            'Laperas',
            'Lapla',
            'Litos',
            'Luparan',
            'Mckinley',
            'New Cebu',
            'Osmeña',
            'Palili',
            'Parame',
            'Poblacion',
            'Roxas',
            'Solongvale',
            'Tagolilong',
            'Tala-o',
            'Talas',
            'Tanwalang',
            'Waterfall'
        ];

        foreach ($barangays as $barangay) {
            Barangay::firstOrCreate([
                'name' => $barangay,
            ], [
                'funded' => false, // All barangays are not funded by default
            ]);
        }
    }
} 