<?php
// Simple routing debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Debug routing...\n";

// Set up environment
$_SERVER['REQUEST_URI'] = '/tripay-settings';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';

// Include router
require_once 'app/core/Router.php';

echo "✓ Router included\n";

// Test route parsing
$path = \Altum\Routing\Router::parse_path();
echo "✓ Path parsed: " . $path . "\n";

$controller = \Altum\Routing\Router::parse_controller();
echo "✓ Controller parsed: " . $controller . "\n";

$method = \Altum\Routing\Router::parse_method(null);
echo "✓ Method parsed: " . $method . "\n";

echo "Debug completed.\n";
?>
