<?php
// Test include TripaySettings
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing include TripaySettings...\n";

// Set up basic constants
define('ALTUMCODE', true);
define('APP_PATH', __DIR__ . '/app/');
define('ROOT_PATH', __DIR__ . '/');
define('THEME_PATH', __DIR__ . '/themes/altum/views/');
define('SITE_URL', 'http://localhost/');

// Include dependencies first
require_once 'app/core/Controller.php';
require_once 'app/traits/Paramsable.php';

echo "✓ Dependencies included\n";

// Try to include TripaySettings
try {
    require_once 'app/controllers/TripaySettings.php';
    echo "✓ TripaySettings.php included\n";
    
    // Check if class exists
    if(class_exists('Altum\Controllers\TripaySettings')) {
        echo "✓ TripaySettings class exists\n";
    } else {
        echo "✗ TripaySettings class not found\n";
    }
    
} catch(Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "Test completed.\n";
?>
