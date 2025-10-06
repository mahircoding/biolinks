<?php
// Debug Tripay Settings route
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing Tripay Settings route...\n";

// Test if controller file exists
if(file_exists('app/controllers/TripaySettings.php')) {
    echo "✓ TripaySettings.php exists\n";
} else {
    echo "✗ TripaySettings.php not found\n";
}

// Test if view file exists
if(file_exists('themes/altum/views/tripay-settings/index.php')) {
    echo "✓ tripay-settings/index.php exists\n";
} else {
    echo "✗ tripay-settings/index.php not found\n";
}

// Test if route is defined
$router_content = file_get_contents('app/core/Router.php');
if(strpos($router_content, 'tripay-settings') !== false) {
    echo "✓ tripay-settings route found in Router.php\n";
} else {
    echo "✗ tripay-settings route not found in Router.php\n";
}

// Test if controller is included in init.php
$init_content = file_get_contents('app/init.php');
if(strpos($init_content, 'TripaySettings.php') !== false) {
    echo "✓ TripaySettings.php included in init.php\n";
} else {
    echo "✗ TripaySettings.php not included in init.php\n";
}

echo "Debug completed.\n";
?>
