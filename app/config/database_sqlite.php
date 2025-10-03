<?php
/* SQLite Database Configuration for Development */

// Create SQLite database file if it doesn't exist
$sqlite_db_path = __DIR__ . '/../../database.sqlite';

// Create database file if it doesn't exist
if (!file_exists($sqlite_db_path)) {
    touch($sqlite_db_path);
}

// SQLite Database connection
try {
    $pdo = new PDO('sqlite:' . $sqlite_db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create basic tables for development
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            user_id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) UNIQUE,
            password VARCHAR(255),
            name VARCHAR(255),
            type INTEGER DEFAULT 0,
            package_id VARCHAR(50) DEFAULT 'free',
            package_expiration_date DATETIME,
            datetime DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id VARCHAR(64) UNIQUE,
            user_id INTEGER,
            name VARCHAR(255),
            description TEXT,
            price DECIMAL(15,0) DEFAULT 0,
            image VARCHAR(255),
            digital_link TEXT,
            status INTEGER DEFAULT 1,
            views INTEGER DEFAULT 0,
            sales INTEGER DEFAULT 0,
            settings TEXT,
            datetime DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id VARCHAR(64) UNIQUE,
            transaction_id VARCHAR(64) UNIQUE,
            user_id INTEGER,
            product_id VARCHAR(64),
            amount DECIMAL(15,0),
            customer_name VARCHAR(255),
            customer_email VARCHAR(255),
            customer_phone VARCHAR(20),
            payment_method VARCHAR(50) DEFAULT 'duitku',
            status VARCHAR(20) DEFAULT 'pending',
            payment_details TEXT,
            settings TEXT,
            datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
            completed_datetime DATETIME
        )
    ");
    
    // Insert a test user if none exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO users (email, password, name, type, package_id) 
            VALUES ('admin@test.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'Admin User', 1, 'premium')
        ");
    }
    
} catch (PDOException $e) {
    error_log("SQLite Database Error: " . $e->getMessage());
}

// Define SQLite connection function
function get_sqlite_connection() {
    global $sqlite_db_path;
    try {
        $pdo = new PDO('sqlite:' . $sqlite_db_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log("SQLite Connection Error: " . $e->getMessage());
        return null;
    }
}