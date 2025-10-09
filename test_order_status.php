<?php
/**
 * Test Order Status Management
 * Test to verify order status handling
 */

require_once 'app/init.php';

use Altum\Models\DigitalOrder;
use Altum\Database\Database;

echo "=== Order Status Management Test ===\n\n";

// Test 1: Check valid statuses
echo "1. Valid Order Statuses:\n";
$valid_statuses = DigitalOrder::get_valid_statuses();
foreach($valid_statuses as $status) {
    echo "   - $status\n";
}
echo "\n";

// Test 2: Test order status checking methods
echo "2. Testing Order Status Methods:\n";

// Create a mock order object
$mock_order_pending = (object)[
    'order_id' => 1,
    'status' => 'pending'
];

$mock_order_pending_payment = (object)[
    'order_id' => 2,
    'status' => 'pending_payment'
];

$mock_order_paid = (object)[
    'order_id' => 3,
    'status' => 'paid'
];

echo "   - Order 1 (pending): is_pending = " . (DigitalOrder::is_pending($mock_order_pending) ? 'YES' : 'NO') . "\n";
echo "   - Order 1 (pending): is_paid = " . (DigitalOrder::is_paid($mock_order_pending) ? 'YES' : 'NO') . "\n";
echo "   - Order 2 (pending_payment): is_pending = " . (DigitalOrder::is_pending($mock_order_pending_payment) ? 'YES' : 'NO') . "\n";
echo "   - Order 2 (pending_payment): is_paid = " . (DigitalOrder::is_paid($mock_order_pending_payment) ? 'YES' : 'NO') . "\n";
echo "   - Order 3 (paid): is_pending = " . (DigitalOrder::is_pending($mock_order_paid) ? 'YES' : 'NO') . "\n";
echo "   - Order 3 (paid): is_paid = " . (DigitalOrder::is_paid($mock_order_paid) ? 'YES' : 'NO') . "\n";
echo "\n";

// Test 3: Check database structure
echo "3. Database Structure Check:\n";
try {
    $result = Database::$database->query("DESCRIBE digital_orders");
    echo "   - digital_orders table exists\n";
    echo "   - Columns:\n";
    while($row = $result->fetch_object()) {
        echo "     * {$row->Field} ({$row->Type})\n";
    }
} catch(Exception $e) {
    echo "   - Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 4: Check for pending orders
echo "4. Current Pending Orders:\n";
try {
    $pending_orders = Database::get_all('*', 'digital_orders', [
        'status' => ['pending', 'pending_payment']
    ]);
    
    echo "   - Found " . count($pending_orders) . " pending orders\n";
    foreach($pending_orders as $order) {
        echo "     * Order ID: {$order->order_id}, Status: {$order->status}, Created: {$order->created_at}\n";
    }
} catch(Exception $e) {
    echo "   - Error: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== Test Complete ===\n";
