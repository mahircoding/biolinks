<?php

namespace Altum\Database;

class Database {

    public static $database;

    public static function initialize() {

        // Try SQLite first for development
        if (defined('USE_SQLITE') && USE_SQLITE) {
            try {
                $sqlite_db_path = __DIR__ . '/../database/database.db';
                
                // Create database directory if it doesn't exist
                $db_dir = dirname($sqlite_db_path);
                if (!is_dir($db_dir)) {
                    mkdir($db_dir, 0755, true);
                }
                
                // Create database file if it doesn't exist
                if (!file_exists($sqlite_db_path)) {
                    touch($sqlite_db_path);
                }
                
                // Use PDO for SQLite
                self::$database = new \PDO('sqlite:' . $sqlite_db_path);
                self::$database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                
                // Create basic tables for development
                self::createSQLiteTables();
                
                return self::$database;
            } catch (\Exception $e) {
                // Fall back to MySQL if SQLite fails
            }
        }

        self::$database = new \mysqli(
            DATABASE_SERVER,
            DATABASE_USERNAME,
            DATABASE_PASSWORD,
            DATABASE_NAME
        );

        /* Debugging */
        if(self::$database->connect_error) {
            die('The connection to the database failed ! Please edit the "app/config/config.php" file and make sure your database connection details are correct!');
        }

        /* Mysql profiling */
        if(MYSQL_DEBUG) {
            self::$database->query("set profiling_history_size=100");
            self::$database->query("set profiling=1");
        }

        self::$database->set_charset('utf8mb4');

        return self::$database;
    }

    public static function createSQLiteTables() {
        if (self::$database instanceof \PDO) {
            // Create basic tables for development
            self::$database->exec("
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
            
            self::$database->exec("
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
            
            self::$database->exec("
                CREATE TABLE IF NOT EXISTS orders (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    order_id VARCHAR(64) UNIQUE,
                    user_id INTEGER,
                    product_id INTEGER,
                    customer_name VARCHAR(255),
                    customer_email VARCHAR(255),
                    customer_phone VARCHAR(50),
                    amount DECIMAL(15,0),
                    status VARCHAR(50) DEFAULT 'pending',
                    payment_method VARCHAR(50),
                    datetime DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            // Insert default admin user if not exists
            $stmt = self::$database->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute(['admin@example.com']);
            if ($stmt->fetchColumn() == 0) {
                $stmt = self::$database->prepare("INSERT INTO users (email, password, name, type) VALUES (?, ?, ?, ?)");
                $stmt->execute(['admin@example.com', password_hash('admin123', PASSWORD_DEFAULT), 'Admin', 1]);
            }
        }
    }

    public static function get($what, $from, Array $conditions = [], $order = false, $clean = true) {

        $what = ($what == '*') ? '*' : '`' . implode('`, `', $what) . '`';
        $from = '`' . $from . '`';
        $where = [];
		$operators = ['=', '>', '>=', '<', '<='];

        foreach($conditions as $key => $value) {
			if(is_array($value)) {
				$value[0] = in_array($value[0],$operators) ? $value[0] : '=';
				$value[1] = ($clean) ? self::clean_string($value[1]) : $value;
				$where[] = '`' . $key . '` '.$value[0].' \'' . $value[1] . '\'';
			} else {
				$value = ($clean) ? self::clean_string($value) : $value;
				$where[] = '`' . $key . '` = \'' . $value . '\'';
			}
        }
        $where = implode(' AND ', $where);

        $order_by = ($order) ? 'ORDER BY ' . $order : null;

        $result = self::$database->query("SELECT {$what} FROM {$from} WHERE {$where} {$order_by}");

        return ($result->num_rows) ? $result->fetch_object() : false;

    }

    public static function simple_get($raw_what, $from, Array $conditions, $clean = true) {

        if (self::$database instanceof \PDO) {
            // SQLite/PDO implementation
            $what = '`' . $raw_what . '`';
            $from = '`' . $from . '`';
            
            $where = [];
            $params = [];
            foreach($conditions as $key => $value) {
                $value = ($clean) ? self::clean_string($value) : $value;
                $where[] = '`' . $key . '` = ?';
                $params[] = $value;
            }
            $where = implode(' AND ', $where);
            
            $stmt = self::$database->prepare("SELECT {$what} FROM {$from} WHERE {$where}");
            $stmt->execute($params);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $data ? $data[$raw_what] : false;
        } else {
            // MySQL/mysqli implementation
            $what = '`' . $raw_what . '`';
            $from = '`' . $from . '`';

            $where = [];
            foreach($conditions as $key => $value) {
                $value = ($clean) ? self::clean_string($value) : $value;
                $where[] = '`' . $key . '` = \'' . $value . '\'';
            }
            $where = implode(' AND ', $where);

            $result = self::$database->query("SELECT {$what} FROM {$from} WHERE {$where}");
            $data = $result->fetch_object();

            return ($result->num_rows) ? $data->{$raw_what} : false;
        }
    }

    public static function exists($what = [], $from, $conditions = []) {

        $what = (!is_array($what)) ? '`' . $what . '`' : '`' . implode('`, `', $what) . '`';
        $from = '`' . $from . '`';
        $where = [];

        foreach($conditions as $key => $value) $where[] = '`' . $key . '` = \'' . $value . '\'';
        $where = implode(' AND ', $where);


        $result = self::$database->query("SELECT {$what} FROM {$from} WHERE {$where}");

        return ($result->num_rows) ? $result->num_rows : false;

    }

    public static function update($what, $fields = [], $conditions = []) {

        $what = '`' . $what . '`';
        $parameters = [];
        $where = [];

        foreach($fields as $key => $value) $parameters[] = '`' . $key . '` = \'' . $value . '\'';
        $parameters = implode(', ', $parameters);

        foreach($conditions as $key => $value) $where[] = '`' . $key . '` = \'' . $value . '\'';
        $where = implode(' AND ', $where);


        return self::$database->query("UPDATE {$what} SET {$parameters} WHERE {$where}");

    }

    public static function insert($table, $data = [], $clean = true) {

        $parameters = [];
        $values = [];

        foreach($data as $key => $value) {
            $parameters[] = $key;
            $values[] = ($clean) ? self::clean_string($value) : $value;
        }

        $parameters_string = '`' . implode('`, `', $parameters) . '`';
        $values_string = '\'' . implode('\', \'', $values) . '\'';

        return self::$database->query("INSERT INTO `{$table}` ({$parameters_string}) VALUES ({$values_string})");
    }

    public static function clean_string($data) {
        return self::$database->escape_string(filter_var($data, FILTER_SANITIZE_STRING));
    }

    public static function clean_array(Array $data) {
        foreach($data as $key => $value) {
            $data[$key] = self::clean_string($value);
        }

        return $data;
    }

    public static function close() {

        if(MYSQL_DEBUG) {
            $result = self::$database->query("show profiles");

            while($profile = $result->fetch_object()) {
                echo $profile->Query_ID . ' - ' . round($profile->Duration, 4) * 1000 . ' ms - ' . $profile->Query . '<br />';
            }
        }

        self::$database->close();
    }
}
