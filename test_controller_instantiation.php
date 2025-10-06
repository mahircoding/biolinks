<?php
// Test controller instantiation
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing controller instantiation...\n";

// Set up basic environment
define('ALTUMCODE', true);
define('APP_PATH', __DIR__ . '/app/');
define('ROOT_PATH', __DIR__ . '/');
define('THEME_PATH', __DIR__ . '/themes/altum/views/');
define('SITE_URL', 'http://localhost/');

// Mock some basic functions
function url($path = '') {
    return SITE_URL . $path;
}

// Include necessary files
try {
    require_once 'app/core/Controller.php';
    require_once 'app/traits/Paramsable.php';
    require_once 'app/controllers/TripaySettings.php';
    echo "✓ All files included\n";
    
    // Try to instantiate
    $controller = new \Altum\Controllers\TripaySettings();
    echo "✓ TripaySettings controller instantiated\n";
    
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "Test completed.\n";
?>
