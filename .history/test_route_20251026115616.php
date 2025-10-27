<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing View Logs route...\n";

try {
    $url = route('disbursements.allocation-logs');
    echo "✓ Route URL generated: $url\n";
    
    // Test if the route exists
    $routes = app('router')->getRoutes();
    $found = false;
    foreach ($routes as $route) {
        if ($route->getName() === 'disbursements.allocation-logs') {
            $found = true;
            echo "✓ Route found in route collection\n";
            echo "✓ Route URI: " . $route->uri() . "\n";
            echo "✓ Route Methods: " . implode(', ', $route->methods()) . "\n";
            break;
        }
    }
    
    if (!$found) {
        echo "✗ Route not found in route collection\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
