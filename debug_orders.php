<?php
// Simple debug script to check orders
$host = '127.0.0.1:3307';
$username = 'root';
$password = '';
$database = 'kiblatbio';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "=== DATABASE CONNECTION SUCCESS ===\n\n";
    
    // Check orders table
    echo "=== ORDERS TABLE ===\n";
    $result = $conn->query("SELECT COUNT(*) as total FROM orders");
    $row = $result->fetch_assoc();
    echo "Total orders: " . $row['total'] . "\n\n";
    
    // Show all orders
    echo "=== ALL ORDERS ===\n";
    $result = $conn->query("SELECT order_id, product_id, customer_email, amount, status, datetime FROM orders ORDER BY datetime DESC LIMIT 10");
    while($row = $result->fetch_assoc()) {
        echo "Order: {$row['order_id']}, Product: {$row['product_id']}, Email: {$row['customer_email']}, Amount: {$row['amount']}, Status: {$row['status']}, Date: {$row['datetime']}\n";
    }
    
    echo "\n=== PRODUCTS TABLE ===\n";
    $result = $conn->query("SELECT COUNT(*) as total FROM products");
    $row = $result->fetch_assoc();
    echo "Total products: " . $row['total'] . "\n\n";
    
    // Show all products
    echo "=== ALL PRODUCTS ===\n";
    $result = $conn->query("SELECT product_id, user_id, name FROM products ORDER BY datetime DESC LIMIT 10");
    while($row = $result->fetch_assoc()) {
        echo "Product: {$row['product_id']}, User ID: {$row['user_id']}, Name: {$row['name']}\n";
    }
    
    echo "\n=== ORDERS WITH PRODUCTS JOIN ===\n";
    $result = $conn->query("
        SELECT 
            o.order_id, 
            o.product_id, 
            o.customer_email, 
            o.amount, 
            o.status,
            p.name as product_name,
            p.user_id as product_owner
        FROM orders o
        LEFT JOIN products p ON o.product_id = p.product_id
        ORDER BY o.datetime DESC 
        LIMIT 10
    ");
    while($row = $result->fetch_assoc()) {
        echo "Order: {$row['order_id']}, Product: {$row['product_name']}, Owner: {$row['product_owner']}, Email: {$row['customer_email']}, Amount: {$row['amount']}, Status: {$row['status']}\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
