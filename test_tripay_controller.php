<?php
// Test TripaySettings controller without database
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing TripaySettings controller...\n";

try {
    // Include necessary files
    require_once 'app/core/Controller.php';
    require_once 'app/controllers/TripaySettings.php';
    
    echo "✓ Controller files included successfully\n";
    
    // Test if class exists
    if(class_exists('Altum\Controllers\TripaySettings')) {
        echo "✓ TripaySettings class exists\n";
    } else {
        echo "✗ TripaySettings class not found\n";
    }
    
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "Test completed.\n";
?>
