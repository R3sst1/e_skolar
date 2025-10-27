<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing route fix...\n";

try {
    // Test the route
    $url = route('disbursements.allocation-logs');
    echo "✓ Route URL: $url\n";
    
    // Test if we can access the controller method
    $controller = new \App\Http\Controllers\DisbursementController();
    echo "✓ Controller exists\n";
    
    // Check if the method exists
    if (method_exists($controller, 'allocationLogs')) {
        echo "✓ allocationLogs method exists\n";
    } else {
        echo "✗ allocationLogs method not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
