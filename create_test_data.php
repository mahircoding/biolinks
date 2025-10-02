<?php
$host = '127.0.0.1:3307';
$username = 'root';
$password = '';
$database = 'kiblatbio';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "=== CREATING TEST DATA ===\n";
    
    // First, let's check if users table exists and get a user_id
    $result = $conn->query("SELECT user_id FROM users LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];
        echo "Found user_id: $user_id\n";
    } else {
        // Create a test user if none exists
        $user_id = 1;
        echo "Using default user_id: $user_id\n";
    }
    
    // Create test products
    $product_id_1 = 'prod_' . uniqid();
    $product_id_2 = 'prod_' . uniqid();
    
    $stmt = $conn->prepare("INSERT INTO products (product_id, user_id, name, description, price, status, datetime) VALUES (?, ?, ?, ?, ?, 1, NOW())");
    
    $name1 = "Test E-Book Digital Marketing";
    $desc1 = "Panduan lengkap digital marketing untuk pemula";
    $price1 = 150000;
    $stmt->bind_param("sissi", $product_id_1, $user_id, $name1, $desc1, $price1);
    $stmt->execute();
    echo "Created product: $product_id_1\n";
    
    $name2 = "Template Website Bisnis";
    $desc2 = "Template website siap pakai untuk bisnis";
    $price2 = 250000;
    $stmt->bind_param("sissi", $product_id_2, $user_id, $name2, $desc2, $price2);
    $stmt->execute();
    echo "Created product: $product_id_2\n";
    
    // Create test orders
    $order_id_1 = 'order_' . uniqid();
    $order_id_2 = 'order_' . uniqid();
    $order_id_3 = 'order_' . uniqid();
    
    $trans_id_1 = 'trans_' . uniqid();
    $trans_id_2 = 'trans_' . uniqid();
    $trans_id_3 = 'trans_' . uniqid();
    
    $stmt = $conn->prepare("INSERT INTO orders (order_id, transaction_id, user_id, product_id, amount, status, datetime, completed_datetime) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    // Order 1 - completed today
    $status1 = 'completed';
    $stmt->bind_param("ssisss", $order_id_1, $trans_id_1, $user_id, $product_id_1, $price1, $status1);
    $stmt->execute();
    echo "Created order: $order_id_1 (completed)\n";
    
    // Order 2 - pending today
    $status2 = 'pending';
    $stmt->bind_param("ssisss", $order_id_2, $trans_id_2, $user_id, $product_id_2, $price2, $status2);
    $stmt->execute();
    echo "Created order: $order_id_2 (pending)\n";
    
    // Order 3 - completed yesterday
    $stmt = $conn->prepare("INSERT INTO orders (order_id, transaction_id, user_id, product_id, amount, status, datetime, completed_datetime) VALUES (?, ?, ?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY))");
    $status3 = 'completed';
    $stmt->bind_param("ssisss", $order_id_3, $trans_id_3, $user_id, $product_id_1, $price1, $status3);
    $stmt->execute();
    echo "Created order: $order_id_3 (completed yesterday)\n";
    
    echo "\n=== VERIFICATION ===\n";
    
    // Verify products
    $result = $conn->query("SELECT COUNT(*) as total FROM products WHERE user_id = $user_id");
    $row = $result->fetch_assoc();
    echo "Products for user $user_id: " . $row['total'] . "\n";
    
    // Verify orders
    $result = $conn->query("SELECT COUNT(*) as total FROM orders");
    $row = $result->fetch_assoc();
    echo "Total orders: " . $row['total'] . "\n";
    
    // Test the sales query
    echo "\n=== TESTING SALES QUERY ===\n";
    $result = $conn->query("
        SELECT 
            COUNT(*) as total_orders,
            COALESCE(SUM(o.amount), 0) as total_revenue
        FROM orders o
        LEFT JOIN products p ON o.product_id = p.product_id
        WHERE o.status IN ('completed', 'paid', 'pending') 
            AND p.user_id = $user_id
    ");
    $row = $result->fetch_assoc();
    echo "Total sales for user $user_id: {$row['total_revenue']} IDR from {$row['total_orders']} orders\n";
    
    $conn->close();
    echo "\n=== TEST DATA CREATED SUCCESSFULLY ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
