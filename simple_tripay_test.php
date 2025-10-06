<?php
// Simple test for TripaySettings
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Simple TripaySettings test...\n";

// Check if we can access the route through web server simulation
$_SERVER['REQUEST_URI'] = '/tripay-settings';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';

echo "✓ Environment variables set\n";

// Check if files exist
$files_to_check = [
    'app/controllers/TripaySettings.php',
    'themes/altum/views/tripay-settings/index.php',
    'app/core/Router.php',
    'app/init.php'
];

foreach($files_to_check as $file) {
    if(file_exists($file)) {
        echo "✓ $file exists\n";
    } else {
        echo "✗ $file missing\n";
    }
}

echo "Test completed.\n";
?>
