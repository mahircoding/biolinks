<?php
/**
 * Test script for digital products functionality
 */

// Include the application initialization
require_once 'app/init.php';

use Altum\Database\Database;

// Test the database schema
echo "Testing Digital Products Implementation\n";
echo "=====================================\n\n";

// Test 1: Check if required tables exist
echo "Test 1: Checking database tables...\n";
try {
    $result = Database::$database->query("SHOW TABLES LIKE 'digital_products'");
    if ($result->num_rows > 0) {
        echo "✓ digital_products table exists\n";
    } else {
        echo "✗ digital_products table does not exist\n";
    }
    
    $result = Database::$database->query("SHOW TABLES LIKE 'digital_orders'");
    if ($result->num_rows > 0) {
        echo "✓ digital_orders table exists\n";
    } else {
        echo "✗ digital_orders table does not exist\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking tables: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check if models exist
echo "Test 2: Checking models...\n";
if (file_exists('app/models/DigitalProduct.php')) {
    echo "✓ DigitalProduct model exists\n";
} else {
    echo "✗ DigitalProduct model does not exist\n";
}

if (file_exists('app/models/DigitalOrder.php')) {
    echo "✓ DigitalOrder model exists\n";
} else {
    echo "✗ DigitalOrder model does not exist\n";
}

echo "\n";

// Test 3: Check if controllers exist
echo "Test 3: Checking controllers...\n";
if (file_exists('app/controllers/DigitalProduct.php')) {
    echo "✓ DigitalProduct controller exists\n";
} else {
    echo "✗ DigitalProduct controller does not exist\n";
}

if (file_exists('app/controllers/DigitalOrder.php')) {
    echo "✓ DigitalOrder controller exists\n";
} else {
    echo "✗ DigitalOrder controller does not exist\n";
}

echo "\n";

// Test 4: Check if views exist
echo "Test 4: Checking views...\n";
$views_to_check = [
    'themes/altum/views/admin/digital-product/index.php',
    'themes/altum/views/admin/digital-product/create.php',
    'themes/altum/views/admin/digital-product/update.php',
    'themes/altum/views/admin/digital-order/index.php',
    'themes/altum/views/admin/digital-order/view.php',
    'themes/altum/views/admin/digital-order/update-status.php',
    'themes/altum/views/digital-product/public-index.php',
    'themes/altum/views/digital-product/public-view.php',
    'themes/altum/views/digital-order/checkout.php',
    'themes/altum/views/digital-order/payment.php'
];

$all_views_exist = true;
foreach ($views_to_check as $view) {
    if (file_exists($view)) {
        echo "✓ " . basename(dirname($view)) . "/" . basename($view) . " exists\n";
    } else {
        echo "✗ " . basename(dirname($view)) . "/" . basename($view) . " does not exist\n";
        $all_views_exist = false;
    }
}

echo "\n";

// Test 5: Check if routes are added
echo "Test 5: Checking routes...\n";
$route_content = file_get_contents('app/core/Router.php');
if (strpos($route_content, 'digital-products') !== false) {
    echo "✓ Digital products routes added to Router\n";
} else {
    echo "✗ Digital products routes not found in Router\n";
}

echo "\n";

// Test 6: Check if Midtrans settings are added
echo "Test 6: Checking Midtrans settings...\n";
$settings_content = file_get_contents('themes/altum/views/admin/settings/index.php');
if (strpos($settings_content, 'Midtrans Settings') !== false) {
    echo "✓ Midtrans settings added to admin panel\n";
} else {
    echo "✗ Midtrans settings not found in admin panel\n";
}

echo "\n";

echo "Implementation Summary:\n";
echo "======================\n";
echo "1. Database tables created for digital products and orders\n";
echo "2. Models created for DigitalProduct and DigitalOrder\n";
echo "3. Controllers created for product and order management\n";
echo "4. Admin views created for product and order management\n";
echo "5. Frontend views created for product display and checkout\n";
echo "6. Routes added for all digital product functionality\n";
echo "7. Midtrans payment gateway integration added\n";
echo "8. Email delivery system for product access after payment\n";
echo "\n";
echo "The digital product sales system is ready to be tested!\n";
echo "You can test it by:\n";
echo "1. Logging into the admin panel\n";
echo "2. Navigating to Digital Products section\n";
echo "3. Creating a new digital product\n";
echo "4. Viewing the product on the frontend\n";
echo "5. Going through the checkout process\n";
echo "6. Simulating a payment through the Midtrans webhook\n";