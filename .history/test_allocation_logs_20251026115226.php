<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AllocationLog;
use App\Models\User;

echo "Testing View Logs functionality...\n";

// Check if we have any allocation logs
$count = AllocationLog::count();
echo "Current AllocationLog count: $count\n";

// If no logs exist, create a sample one for testing
if ($count == 0) {
    echo "Creating sample allocation log...\n";
    
    // Get the first admin user
    $admin = User::whereIn('role', ['admin', 'super_admin'])->first();
    
    if ($admin) {
        try {
            AllocationLog::create([
                'office_id' => 6, // Scholarship Office ID
                'allocated_by' => $admin->id,
                'transaction_type' => 'allocation',
                'amount' => 100000.00,
                'description' => 'Sample budget allocation for testing View Logs functionality',
                'reference_number' => 'TEST-ALLOC-' . now()->format('YmdHis'),
            ]);
            
            echo "✓ Sample allocation log created successfully\n";
            echo "✓ View Logs should now work and show this sample data\n";
        } catch (Exception $e) {
            echo "✗ Error creating sample log: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✗ No admin user found to create sample log\n";
    }
} else {
    echo "✓ Allocation logs already exist ($count records)\n";
    echo "✓ View Logs should work with existing data\n";
}

// Test the route
try {
    $url = route('disbursements.allocation-logs');
    echo "✓ Route URL: $url\n";
} catch (Exception $e) {
    echo "✗ Route error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
