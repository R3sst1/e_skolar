<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AllocationLog;
use Illuminate\Support\Facades\Schema;

echo "Testing allocation_logs table...\n";

// Check if table exists
if (Schema::hasTable('allocation_logs')) {
    echo "✓ allocation_logs table exists\n";
    
    // Check if office_id column exists
    if (Schema::hasColumn('allocation_logs', 'office_id')) {
        echo "✓ office_id column exists\n";
        
        // Test the query that was failing
        try {
            $result = AllocationLog::forOffice(6)->allocations()->sum('amount');
            echo "✓ Query successful! Result: " . $result . "\n";
        } catch (Exception $e) {
            echo "✗ Query failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✗ office_id column does not exist\n";
    }
} else {
    echo "✗ allocation_logs table does not exist\n";
}

echo "Test completed.\n";
