<?php
// Test route access
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing route access...\n";

// Simulate web request
$_SERVER['REQUEST_URI'] = '/tripay-settings';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';

// Set up basic constants
define('ALTUMCODE', true);
define('APP_PATH', __DIR__ . '/app/');
define('ROOT_PATH', __DIR__ . '/');
define('THEME_PATH', __DIR__ . '/themes/altum/views/');
define('SITE_URL', 'http://localhost/');

echo "✓ Environment set up\n";

// Check if we can include the main index.php
try {
    ob_start();
    include 'index.php';
    $output = ob_get_clean();
    
    if(empty($output)) {
        echo "✗ Blank output - possible error\n";
    } else {
        echo "✓ Got output: " . substr($output, 0, 100) . "...\n";
    }
    
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
?>
