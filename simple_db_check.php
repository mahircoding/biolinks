<?php
// Simple database check without using the application's init file
define('DATABASE_SERVER', 'localhost');
define('DATABASE_USERNAME', 'root');
define('DATABASE_PASSWORD', '');
define('DATABASE_NAME', 'kiblatbio');

try {
    // Check if database exists
    $conn = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected to MySQL server successfully.\n";
    
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE '" . DATABASE_NAME . "'");
    if ($result->num_rows > 0) {
        echo "Database '" . DATABASE_NAME . "' exists.\n";
        
        // Select the database
        $conn->select_db(DATABASE_NAME);
        
        // Check if tables exist
        $result = $conn->query("SHOW TABLES LIKE 'digital_products'");
        if ($result->num_rows > 0) {
            echo "Table 'digital_products' exists.\n";
        } else {
            echo "Table 'digital_products' does not exist.\n";
        }
        
        $result = $conn->query("SHOW TABLES LIKE 'digital_orders'");
        if ($result->num_rows > 0) {
            echo "Table 'digital_orders' exists.\n";
        } else {
            echo "Table 'digital_orders' does not exist.\n";
        }
    } else {
        echo "Database '" . DATABASE_NAME . "' does not exist.\n";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}