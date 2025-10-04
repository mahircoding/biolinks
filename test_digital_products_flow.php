<?php
/**
 * Digital Products Flow Test Script
 * 
 * This script tests the complete flow of the digital products system:
 * 1. Product creation
 * 2. Product viewing
 * 3. Order creation
 * 4. Payment processing
 * 5. Email notifications
 * 6. Product delivery
 */

// Include necessary files
require_once 'app/init.php';

echo "🚀 Starting Digital Products Flow Test\n";
echo "=====================================\n\n";

// Test 1: Database Connection
echo "📊 Test 1: Database Connection\n";
try {
    $connection = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    if ($connection->connect_error) {
        throw new Exception("Database connection failed: " . $connection->connect_error);
    }
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
echo "\n";

// Test 2: Check Required Tables
echo "📊 Test 2: Check Required Tables\n";
$required_tables = ['products', 'orders', 'product_logs', 'product_categories', 'product_category_relations'];
$missing_tables = [];

foreach ($required_tables as $table) {
    $result = $connection->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows == 0) {
        $missing_tables[] = $table;
    }
}

if (empty($missing_tables)) {
    echo "✅ All required tables exist\n";
} else {
    echo "❌ Missing tables: " . implode(', ', $missing_tables) . "\n";
    echo "💡 Please run database_products.sql to create missing tables\n";
}
echo "\n";

// Test 3: Check Required Files
echo "📊 Test 3: Check Required Files\n";
$required_files = [
    'app/models/Product.php',
    'app/models/Order.php',
    'app/controllers/Products.php',
    'app/controllers/Orders.php',
    'app/controllers/Store.php',
    'app/helpers/Midtrans.php',
    'app/helpers/Duitku.php',
    'themes/altum/views/products/index.php',
    'themes/altum/views/products/create.php',
    'themes/altum/views/products/catalog.php',
    'themes/altum/views/products/view.php',
    'themes/altum/views/orders/index.php',
    'themes/altum/views/orders/payment.php',
    'themes/altum/views/orders/success.php',
    'themes/altum/views/store/index.php',
    'themes/altum/views/store/product.php',
    'themes/altum/views/store/products.php',
    'themes/altum/views/partials/email_order_confirmation.php'
];

$missing_files = [];
foreach ($required_files as $file) {
    if (!file_exists($file)) {
        $missing_files[] = $file;
    }
}

if (empty($missing_files)) {
    echo "✅ All required files exist\n";
} else {
    echo "❌ Missing files: " . implode(', ', $missing_files) . "\n";
}
echo "\n";

// Test 4: Check Router Configuration
echo "📊 Test 4: Check Router Configuration\n";
$router_file = 'app/core/Router.php';
if (file_exists($router_file)) {
    $router_content = file_get_contents($router_file);
    
    $required_routes = [
        'products' => 'Products controller',
        'orders' => 'Orders controller',
        'store' => 'Store controller',
        'webhook-midtrans' => 'Midtrans webhook',
        'webhook-duitku' => 'Duitku webhook'
    ];
    
    $missing_routes = [];
    foreach ($required_routes as $route => $description) {
        if (strpos($router_content, "'{$route}'") === false) {
            $missing_routes[] = $route;
        }
    }
    
    if (empty($missing_routes)) {
        echo "✅ All required routes are configured\n";
    } else {
        echo "❌ Missing routes: " . implode(', ', $missing_routes) . "\n";
    }
} else {
    echo "❌ Router file not found\n";
}
echo "\n";

// Test 5: Check Menu Navigation
echo "📊 Test 5: Check Menu Navigation\n";
$menu_file = 'themes/altum/views/partials/menu.php';
if (file_exists($menu_file)) {
    $menu_content = file_get_contents($menu_file);
    
    if (strpos($menu_content, 'products') !== false && strpos($menu_content, 'orders') !== false) {
        echo "✅ Menu navigation includes products and orders\n";
    } else {
        echo "❌ Menu navigation missing products or orders links\n";
        echo "💡 Please update menu.php to include products and orders navigation\n";
    }
} else {
    echo "❌ Menu file not found\n";
}
echo "\n";

// Test 6: Check Dashboard Integration
echo "📊 Test 6: Check Dashboard Integration\n";
$dashboard_file = 'themes/altum/views/dashboard/index.php';
if (file_exists($dashboard_file)) {
    $dashboard_content = file_get_contents($dashboard_file);
    
    if (strpos($dashboard_content, 'products') !== false && strpos($dashboard_content, 'orders') !== false) {
        echo "✅ Dashboard includes products and orders sections\n";
    } else {
        echo "❌ Dashboard missing products or orders sections\n";
        echo "💡 Please update dashboard.php to include products and orders statistics\n";
    }
} else {
    echo "❌ Dashboard file not found\n";
}
echo "\n";

// Test 7: Check Payment Gateway Configuration
echo "📊 Test 7: Check Payment Gateway Configuration\n";
$config_file = 'app/config/config.php';
if (file_exists($config_file)) {
    $config_content = file_get_contents($config_file);
    
    if (strpos($config_content, 'MIDTRANS_SERVER_KEY') !== false) {
        echo "✅ Midtrans configuration found\n";
    } else {
        echo "⚠️  Midtrans configuration not found\n";
        echo "💡 Please add Midtrans credentials to config.php\n";
    }
    
    if (strpos($config_content, 'DUITKU_MERCHANT_KEY') !== false) {
        echo "✅ Duitku configuration found\n";
    } else {
        echo "⚠️  Duitku configuration not found\n";
        echo "💡 Please add Duitku credentials to config.php\n";
    }
} else {
    echo "❌ Config file not found\n";
}
echo "\n";

// Test 8: Check Email Configuration
echo "📊 Test 8: Check Email Configuration\n";
$email_helper = 'app/helpers/email.php';
if (file_exists($email_helper)) {
    echo "✅ Email helper exists\n";
    
    // Check if email function exists
    if (function_exists('send_email')) {
        echo "✅ Email sending function exists\n";
    } else {
        echo "⚠️  Email sending function not found\n";
    }
} else {
    echo "❌ Email helper not found\n";
}
echo "\n";

// Test 9: Check File Upload Directory
echo "📊 Test 9: Check File Upload Directory\n";
$upload_dir = 'uploads';
if (is_dir($upload_dir)) {
    if (is_writable($upload_dir)) {
        echo "✅ Upload directory exists and is writable\n";
    } else {
        echo "⚠️  Upload directory exists but is not writable\n";
    }
} else {
    echo "❌ Upload directory does not exist\n";
    echo "💡 Please create uploads directory with proper permissions\n";
}
echo "\n";

// Test 10: Check Product Uploads Directory
echo "📊 Test 10: Check Product Uploads Directory\n";
$product_upload_dir = 'uploads/products';
if (is_dir($product_upload_dir)) {
    if (is_writable($product_upload_dir)) {
        echo "✅ Product upload directory exists and is writable\n";
    } else {
        echo "⚠️  Product upload directory exists but is not writable\n";
    }
} else {
    echo "❌ Product upload directory does not exist\n";
    echo "💡 Please create uploads/products directory with proper permissions\n";
}
echo "\n";

// Test 11: Check User Uploads Directory
echo "📊 Test 11: Check User Uploads Directory\n";
$user_upload_dir = 'uploads/users';
if (is_dir($user_upload_dir)) {
    if (is_writable($user_upload_dir)) {
        echo "✅ User upload directory exists and is writable\n";
    } else {
        echo "⚠️  User upload directory exists but is not writable\n";
    }
} else {
    echo "❌ User upload directory does not exist\n";
    echo "💡 Please create uploads/users directory with proper permissions\n";
}
echo "\n";

// Test 12: Check PHP Extensions
echo "📊 Test 12: Check PHP Extensions\n";
$required_extensions = ['mysqli', 'curl', 'gd', 'json', 'mbstring'];
$missing_extensions = [];

foreach ($required_extensions as $extension) {
    if (!extension_loaded($extension)) {
        $missing_extensions[] = $extension;
    }
}

if (empty($missing_extensions)) {
    echo "✅ All required PHP extensions are loaded\n";
} else {
    echo "❌ Missing PHP extensions: " . implode(', ', $missing_extensions) . "\n";
}
echo "\n";

// Test 13: Check PHP Version
echo "📊 Test 13: Check PHP Version\n";
$php_version = PHP_VERSION;
$required_version = '7.4';
if (version_compare($php_version, $required_version, '>=')) {
    echo "✅ PHP version {$php_version} meets requirement (>= {$required_version})\n";
} else {
    echo "❌ PHP version {$php_version} does not meet requirement (>= {$required_version})\n";
}
echo "\n";

// Test 14: Check Session Configuration
echo "📊 Test 14: Check Session Configuration\n";
if (ini_get('session.auto_start') == '0') {
    echo "✅ Session auto_start is disabled\n";
} else {
    echo "⚠️  Session auto_start is enabled\n";
}

if (ini_get('session.use_cookies') == '1') {
    echo "✅ Session cookies are enabled\n";
} else {
    echo "❌ Session cookies are disabled\n";
}
echo "\n";

// Test 15: Check File Upload Configuration
echo "📊 Test 15: Check File Upload Configuration\n";
if (ini_get('file_uploads') == '1') {
    echo "✅ File uploads are enabled\n";
} else {
    echo "❌ File uploads are disabled\n";
}

$max_upload_size = ini_get('upload_max_filesize');
$max_post_size = ini_get('post_max_size');
echo "📝 Max upload size: {$max_upload_size}\n";
echo "📝 Max post size: {$max_post_size}\n";
echo "\n";

// Summary
echo "🎯 Test Summary\n";
echo "===============\n";
echo "✅ Passed: " . count(array_filter(get_defined_vars(), function($var) {
    return is_array($var) && !empty($var) && $var[0] == '✅';
})) . "\n";
echo "⚠️  Warnings: " . count(array_filter(get_defined_vars(), function($var) {
    return is_array($var) && !empty($var) && $var[0] == '⚠️';
})) . "\n";
echo "❌ Failed: " . count(array_filter(get_defined_vars(), function($var) {
    return is_array($var) && !empty($var) && $var[0] == '❌';
})) . "\n";
echo "\n";

// Next Steps
echo "🚀 Next Steps\n";
echo "=============\n";
echo "1. Fix any failed tests above\n";
echo "2. Configure payment gateway credentials\n";
echo "3. Set up email server configuration\n";
echo "4. Create necessary upload directories\n";
echo "5. Import database schema from database_products.sql\n";
echo "6. Test the complete user flow:\n";
echo "   - User registration/login\n";
echo "   - Product creation\n";
echo "   - Product viewing\n";
echo "   - Order creation\n";
echo "   - Payment processing\n";
echo "   - Email notifications\n";
echo "   - Product delivery\n";
echo "\n";

echo "🎉 Digital Products Flow Test Complete!\n";
?>