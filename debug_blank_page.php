<?php
// Debug blank page issue
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

echo "Debugging blank page...\n";

// Set up environment
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

// Try to include the main application
try {
    ob_start();
    
    // Include init.php first
    require_once 'app/init.php';
    echo "✓ init.php included\n";
    
    // Include App.php
    require_once 'app/core/App.php';
    echo "✓ App.php included\n";
    
    // Create App instance
    $app = new \Altum\App();
    echo "✓ App instance created\n";
    
    $output = ob_get_clean();
    
    if(empty($output)) {
        echo "✗ Blank output - checking error log\n";
        if(file_exists('/tmp/php_errors.log')) {
            echo "Error log content:\n";
            echo file_get_contents('/tmp/php_errors.log');
        }
    } else {
        echo "✓ Got output: " . substr($output, 0, 200) . "...\n";
    }
    
} catch(Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch(Error $e) {
    echo "✗ Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "Debug completed.\n";
?>
