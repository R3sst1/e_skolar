<?php

namespace Database\Seeders;

use App\Models\Scholar;
use App\Models\ScholarPerformance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScholarPerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scholars = Scholar::where('status', 'active')->get();
        
        if ($scholars->isEmpty()) {
            $this->command->info('No active scholars found. Please run ScholarSeeder first.');
            return;
        }

        $semesters = ['First', 'Second', 'Summer'];
        $schoolYears = ['2023-2024', '2024-2025'];
        
        foreach ($scholars as $scholar) {
            foreach ($schoolYears as $schoolYear) {
                foreach ($semesters as $semester) {
                    // Skip Summer for some scholars to create variety
                    if ($semester === 'Summer' && rand(1, 3) === 1) {
                        continue;
                    }

                    // Generate realistic performance data
                    $gwa = $this->generateGWA();
                    $unitsEnrolled = rand(15, 24);
                    $unitsCompleted = $this->calculateUnitsCompleted($unitsEnrolled, $gwa);
                    $unitsFailed = $unitsEnrolled - $unitsCompleted;
                    
                    $subjectsEnrolled = rand(5, 8);
                    $subjectsPassed = $this->calculateSubjectsPassed($subjectsEnrolled, $gwa);
                    $subjectsFailed = $subjectsEnrolled - $subjectsPassed;
                    $subjectsDropped = rand(0, 1);

                    // Create performance record
                    ScholarPerformance::create([
                        'scholar_id' => $scholar->id,
                        'semester' => $semester,
                        'school_year' => $schoolYear,
                        'gwa' => $gwa,
                        'units_enrolled' => $unitsEnrolled,
                        'units_completed' => $unitsCompleted,
                        'units_failed' => $unitsFailed,
                        'subjects_enrolled' => $subjectsEnrolled,
                        'subjects_passed' => $subjectsPassed,
                        'subjects_failed' => $subjectsFailed,
                        'subjects_dropped' => $subjectsDropped,
                        'academic_remarks' => $this->generateRemarks($gwa, $subjectsFailed),
                        'submitted_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        // Update academic status and requirements for all records
        ScholarPerformance::all()->each(function ($performance) {
            $performance->update([
                'academic_status' => $performance->calculateAcademicStatus(),
                'meets_retention_requirements' => $performance->meetsGWARequirement() && 
                                                $performance->meetsUnitsRequirement() && 
                                                $performance->meetsNoFailedSubjectsRequirement(),
            ]);
        });

        $this->command->info('Scholar Performance data seeded successfully!');
    }

    private function generateGWA(): float
    {
        // Generate GWA with realistic distribution
        $rand = rand(1, 100);
        
        if ($rand <= 20) {
            // 20% excellent (1.0 - 1.5)
            return round(1.0 + (rand(0, 50) / 100), 2);
        } elseif ($rand <= 50) {
            // 30% good (1.5 - 2.0)
            return round(1.5 + (rand(0, 50) / 100), 2);
        } elseif ($rand <= 80) {
            // 30% average (2.0 - 2.5)
            return round(2.0 + (rand(0, 50) / 100), 2);
        } else {
            // 20% below average (2.5 - 3.5)
            return round(2.5 + (rand(0, 100) / 100), 2);
        }
    }

    private function calculateUnitsCompleted(int $enrolled, float $gwa): int
    {
        // Higher GWA = higher completion rate
        $completionRate = max(0.6, min(1.0, (3.0 - $gwa) / 2.0));
        return (int) round($enrolled * $completionRate);
    }

    private function calculateSubjectsPassed(int $enrolled, float $gwa): int
    {
        // Higher GWA = higher pass rate
        $passRate = max(0.7, min(1.0, (3.0 - $gwa) / 2.0));
        return (int) round($enrolled * $passRate);
    }

    private function generateRemarks(float $gwa, int $failedSubjects): string
    {
        if ($gwa <= 1.5) {
            return 'Excellent academic performance. Maintains high standards.';
        } elseif ($gwa <= 2.0) {
            return 'Good academic standing. Continue current study habits.';
        } elseif ($gwa <= 2.5) {
            return 'Average performance. Consider additional study time.';
        } else {
            if ($failedSubjects > 0) {
                return 'Below average performance. Academic intervention recommended.';
            } else {
                return 'Below average performance. Focus on improving study methods.';
            }
        }
    }
}
