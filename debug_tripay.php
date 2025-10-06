<?php
// Debug Tripay Configuration
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
require_once 'app/init.php';

echo "<h2>Debug Tripay Configuration</h2>";

// Test database connection
try {
    $db = Database::$database;
    echo "<p>✅ Database connected</p>";
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
    exit;
}

// Check if users table has Tripay columns
$result = $db->query("SHOW COLUMNS FROM users LIKE 'tripay_%'");
$columns = [];
while($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

echo "<h3>Tripay Columns in users table:</h3>";
if(empty($columns)) {
    echo "<p>❌ No Tripay columns found</p>";
} else {
    echo "<ul>";
    foreach($columns as $column) {
        echo "<li>✅ $column</li>";
    }
    echo "</ul>";
}

// Check sample user data
echo "<h3>Sample User Data:</h3>";
$result = $db->query("SELECT user_id, name, tripay_merchant_code, tripay_api_key_public, tripay_api_key_secret FROM users LIMIT 3");
if($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>User ID</th><th>Name</th><th>Merchant Code</th><th>API Public</th><th>API Secret</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tripay_merchant_code'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['tripay_api_key_public'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['tripay_api_key_secret'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>❌ Error querying users: " . $db->error . "</p>";
}

// Check digital_products table
echo "<h3>Digital Products:</h3>";
$result = $db->query("SELECT product_id, name, user_id, price_cents FROM digital_products LIMIT 3");
if($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>User ID</th><th>Price</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['price_cents']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>❌ Error querying digital_products: " . $db->error . "</p>";
}

echo "<h3>Test Tripay API Call:</h3>";
echo "<p>Testing with sample data...</p>";

// Test with sample data
$test_user = [
    'tripay_merchant_code' => 'T123456',
    'tripay_api_key_public' => 'test_public_key',
    'tripay_api_key_secret' => 'test_secret_key'
];

$test_payload = [
    'method' => 'QRIS',
    'merchant_ref' => 'TEST-' . time(),
    'amount' => 50000,
    'customer_name' => 'Test Customer',
    'customer_email' => 'test@example.com',
    'customer_phone' => '08123456789',
    'order_items' => [
        [
            'sku' => '1',
            'name' => 'Test Product',
            'price' => 50000,
            'quantity' => 1,
            'product_url' => 'https://example.com'
        ]
    ],
    'expired_time' => time() + (24 * 60 * 60),
    'signature' => hash_hmac('sha256', $test_user['tripay_merchant_code'] . 'TEST-' . time() . '50000', $test_user['tripay_api_key_secret']),
    'return_url' => 'https://example.com/return',
    'callback_url' => 'https://example.com/callback'
];

echo "<pre>";
echo "Test Payload:\n";
print_r($test_payload);
echo "</pre>";

echo "<p>✅ Debug completed. Check the output above for any issues.</p>";
?>