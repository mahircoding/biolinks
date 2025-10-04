<?php

namespace Altum\Models;

use Altum\Database\Database;
use Altum\Models\Model;

class Product extends Model {
    public function get_products_by_user_id($user_id, $page = 1, $max_items = 10) {
        $offset = ($page - 1) * $max_items;
        
        $query = "
            SELECT * FROM `products` 
            WHERE `user_id` = ? 
            ORDER BY `datetime` DESC 
            LIMIT ? OFFSET ?
        ";
        
        $result = Database::$database->query($query, [$user_id, $max_items, $offset]);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_product_by_id($product_id) {
        $query = "SELECT * FROM `products` WHERE `product_id` = ?";
        $result = Database::$database->query($query, [$product_id]);
        return $result ? $result->fetch_assoc() : null;
    }

    public function get_product_by_id_and_user_id($product_id, $user_id) {
        $query = "SELECT * FROM `products` WHERE `product_id` = ? AND `user_id` = ?";
        $result = Database::$database->query($query, [$product_id, $user_id]);
        return $result ? $result->fetch_assoc() : null;
    }

    public function get_active_products($page = 1, $max_items = 12, $search = null, $category = null) {
        $offset = ($page - 1) * $max_items;
        
        $where_conditions = ["`products`.`status` = 1"];
        $params = [];
        
        if ($search) {
            $where_conditions[] = "(`products`.`name` LIKE ? OR `products`.`description` LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if ($category) {
            $where_conditions[] = "`products`.`category_id` = ?";
            $params[] = $category;
        }
        
        $where_clause = "WHERE " . implode(" AND ", $where_conditions);
        
        $query = "
            SELECT 
                `products`.*,
                `users`.`name` as `user_name`,
                `users`.`email` as `user_email`,
                `users`.`image` as `user_image`
            FROM `products`
            LEFT JOIN `users` ON `products`.`user_id` = `users`.`id`
            {$where_clause}
            ORDER BY `products`.`datetime` DESC 
            LIMIT ? OFFSET ?
        ";
        
        $params[] = $max_items;
        $params[] = $offset;
        
        $result = Database::$database->query($query, $params);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_total_active_products($search = null, $category = null) {
        $where_conditions = ["`products`.`status` = 1"];
        $params = [];
        
        if ($search) {
            $where_conditions[] = "(`products`.`name` LIKE ? OR `products`.`description` LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if ($category) {
            $where_conditions[] = "`products`.`category_id` = ?";
            $params[] = $category;
        }
        
        $where_clause = "WHERE " . implode(" AND ", $where_conditions);
        
        $query = "SELECT COUNT(*) as `total` FROM `products` {$where_clause}";
        
        $result = Database::$database->query($query, $params);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function create($data) {
        $product_id = string_generate(10);
        
        $query = "
            INSERT INTO `products`
            (`product_id`, `user_id`, `name`, `description`, `price`, `image`, `digital_link`, `status`, `views`, `sales`, `settings`, `datetime`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $datetime = \Altum\Date::$date;
        
        Database::$database->query($query, [
            $product_id,
            $data['user_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image'],
            $data['digital_link'],
            $data['status'],
            0,
            0,
            $data['settings'] ?? null,
            $datetime
        ]);
        
        return $product_id;
    }

    public function update($product_id, $data) {
        $query = "
            UPDATE `products` 
            SET 
                `name` = ?, 
                `description` = ?, 
                `price` = ?, 
                `image` = ?, 
                `digital_link` = ?, 
                `status` = ?, 
                `settings` = ?
            WHERE `product_id` = ? AND `user_id` = ?
        ";
        
        Database::$database->query($query, [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image'],
            $data['digital_link'],
            $data['status'],
            $data['settings'] ?? null,
            $product_id,
            $data['user_id']
        ]);
        
        return true;
    }

    public function delete($product_id, $user_id) {
        $query = "DELETE FROM `products` WHERE `product_id` = ? AND `user_id` = ?";
        Database::$database->query($query, [$product_id, $user_id]);
        
        return true;
    }

    public function increment_views($product_id) {
        $query = "UPDATE `products` SET `views` = `views` + 1 WHERE `product_id` = ?";
        Database::$database->query($query, [$product_id]);
        
        return true;
    }

    public function increment_sales($product_id) {
        $query = "UPDATE `products` SET `sales` = `sales` + 1 WHERE `product_id` = ?";
        Database::$database->query($query, [$product_id]);
        
        return true;
    }

    public function get_user_products_count($user_id) {
        $query = "SELECT COUNT(*) as `total` FROM `products` WHERE `user_id` = ?";
        $result = Database::$database->query($query, [$user_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_user_sales_count($user_id) {
        $query = "
            SELECT COUNT(*) as `total` 
            FROM `products` 
            WHERE `user_id` = ? AND `sales` > 0
        ";
        $result = Database::$database->query($query, [$user_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_user_total_revenue($user_id) {
        $query = "
            SELECT COALESCE(SUM(`products`.`price` * `orders`.`quantity`), 0) as `total` 
            FROM `products` 
            LEFT JOIN `orders` ON `products`.`product_id` = `orders`.`product_id` 
            WHERE `products`.`user_id` = ? AND `orders`.`status` = 'completed'
        ";
        $result = Database::$database->query($query, [$user_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function log_activity($product_id, $user_id, $action, $additional_data = null) {
        $query = "
            INSERT INTO `product_logs` 
            (`product_id`, `user_id`, `action`, `ip_address`, `user_agent`, `additional_data`, `datetime`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        
        Database::$database->query($query, [
            $product_id,
            $user_id,
            $action,
            get_ip(),
            $_SERVER['HTTP_USER_AGENT'] ?? null,
            $additional_data,
            \Altum\Date::$date
        ]);
        
        return true;
    }

    public function get_product_logs($product_id, $limit = 50) {
        $query = "
            SELECT * FROM `product_logs` 
            WHERE `product_id` = ? 
            ORDER BY `datetime` DESC 
            LIMIT ?
        ";
        
        $result = Database::$database->query($query, [$product_id, $limit]);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_active_products_by_user($user_id, $page = 1, $max_items = 12) {
        $offset = ($page - 1) * $max_items;
        
        $query = "
            SELECT * FROM `products`
            WHERE `user_id` = ? AND `status` = 1
            ORDER BY `datetime` DESC
            LIMIT ? OFFSET ?
        ";
        
        $result = Database::$database->query($query, [$user_id, $max_items, $offset]);
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_user_products($user_id, $page = 1, $max_items = 10) {
        $offset = ($page - 1) * $max_items;
        
        $query = "
            SELECT * FROM `products`
            WHERE `user_id` = ?
            ORDER BY `datetime` DESC
            LIMIT ? OFFSET ?
        ";
        
        $result = Database::$database->query($query, [$user_id, $max_items, $offset]);
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}