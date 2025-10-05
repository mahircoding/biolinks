<?php
// Simple test to check if routing is working
require_once 'app/init.php';

echo "Testing route parsing...\n";

// Simulate a request to digital-products
$_GET['altum'] = 'digital-products';

// Parse the URL
\Altum\Routing\Router::parse_url();
$params = \Altum\Routing\Router::get_params();

echo "Parsed params: ";
print_r($params);

// Parse the controller
\Altum\Routing\Router::parse_controller();
echo "Controller key: " . \Altum\Routing\Router::$controller_key . "\n";
echo "Controller: " . \Altum\Routing\Router::$controller . "\n";
echo "Path: " . \Altum\Routing\Router::$path . "\n";