<?php
// Test without database
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing without database...\n";

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

// Mock database functions
class MockDatabase {
    public static $database;
    public static function get($columns, $table, $where) {
        return (object)[
            'tripay_merchant_code' => '',
            'tripay_api_key_public' => '',
            'tripay_api_key_secret' => ''
        ];
    }
    public static function clean_string($str) {
        return $str;
    }
    public static function update($table, $data, $where) {
        return true;
    }
}

// Mock other classes
class MockAuthentication {
    public static function guard() {
        return true;
    }
}

class MockCsrf {
    public static function check() {
        return true;
    }
    public static function get() {
        return 'test_token';
    }
}

// Include controller
require_once 'app/core/Controller.php';
require_once 'app/traits/Paramsable.php';
require_once 'app/controllers/TripaySettings.php';

echo "✓ Controller files included\n";

// Mock the dependencies
$controller = new \Altum\Controllers\TripaySettings();
echo "✓ Controller instantiated\n";

// Mock user and settings
$controller->user = (object)['user_id' => 1];
$controller->settings = (object)['title' => 'Test Site'];

echo "✓ Mock data set\n";

// Try to call index method
try {
    ob_start();
    $controller->index();
    $output = ob_get_clean();
    
    if(empty($output)) {
        echo "✗ Blank output from controller\n";
    } else {
        echo "✓ Controller output: " . substr($output, 0, 200) . "...\n";
    }
    
} catch(Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "Test completed.\n";
?>
