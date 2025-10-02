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
    
    echo "=== ORDERS TABLE STRUCTURE ===\n";
    $result = $conn->query("DESCRIBE orders");
    while($row = $result->fetch_assoc()) {
        echo "Column: {$row['Field']}, Type: {$row['Type']}, Null: {$row['Null']}, Key: {$row['Key']}, Default: {$row['Default']}\n";
    }
    
    echo "\n=== PRODUCTS TABLE STRUCTURE ===\n";
    $result = $conn->query("DESCRIBE products");
    while($row = $result->fetch_assoc()) {
        echo "Column: {$row['Field']}, Type: {$row['Type']}, Null: {$row['Null']}, Key: {$row['Key']}, Default: {$row['Default']}\n";
    }
    
    echo "\n=== ORDERS COUNT ===\n";
    $result = $conn->query("SELECT COUNT(*) as total FROM orders");
    $row = $result->fetch_assoc();
    echo "Total orders: " . $row['total'] . "\n";
    
    echo "\n=== PRODUCTS COUNT ===\n";
    $result = $conn->query("SELECT COUNT(*) as total FROM products");
    $row = $result->fetch_assoc();
    echo "Total products: " . $row['total'] . "\n";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
