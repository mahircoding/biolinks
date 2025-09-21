<?php

namespace Altum\Models;

use Altum\Database\Database;

class Product extends Model {

    public function get($product_id) {
        $data = Database::get('*', 'products', ['product_id' => $product_id]);
        
        if($data) {
            // Parse JSON fields if needed
            $data->settings = $data->settings ? json_decode($data->settings) : new \stdClass();
        }
        
        return $data;
    }

    public function get_all($user_id = null, $status = null) {
        $conditions = [];
        
        if($user_id) {
            $conditions['user_id'] = $user_id;
        }
        
        if($status !== null) {
            $conditions['status'] = $status;
        }
        
        $result = Database::$database->query("
            SELECT * FROM `products` 
            " . (!empty($conditions) ? "WHERE " . implode(' AND ', array_map(function($key, $value) {
                return "`{$key}` = '" . Database::clean_string($value) . "'";
            }, array_keys($conditions), $conditions)) : "") . "
            ORDER BY `datetime` DESC
        ");
        
        $data = [];
        while($row = $result->fetch_object()) {
            $row->settings = $row->settings ? json_decode($row->settings) : new \stdClass();
            $data[] = $row;
        }
        
        return $data;
    }

    public function create($user_id, $name, $description, $price, $image = null, $digital_link = null, $status = 1, $settings = null) {
        $product_id = md5($user_id . $name . time());
        
        $settings_json = $settings ? json_encode($settings) : null;
        
        Database::insert('products', [
            'product_id' => $product_id,
            'user_id' => $user_id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $image,
            'digital_link' => $digital_link,
            'status' => $status,
            'settings' => $settings_json,
            'datetime' => \Altum\Date::$date
        ]);
        
        return $product_id;
    }

    public function update($product_id, $data) {
        if(isset($data['settings']) && is_array($data['settings'])) {
            $data['settings'] = json_encode($data['settings']);
        }
        
        Database::update('products', $data, ['product_id' => $product_id]);
    }

    public function delete($product_id) {
        // Get product details first
        $product = $this->get($product_id);
        
        if($product) {
            // Delete product image if exists
            if($product->image && file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/products/' . $product->image)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/products/' . $product->image);
            }
            
            // Delete from database
            Database::$database->query("DELETE FROM `products` WHERE `product_id` = '" . Database::clean_string($product_id) . "'");
            
            // Delete related orders if needed
            Database::$database->query("DELETE FROM `orders` WHERE `product_id` = '" . Database::clean_string($product_id) . "'");
        }
    }

    public function get_by_user($user_id) {
        $result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = '" . Database::clean_string($user_id) . "'
            ORDER BY `datetime` DESC
        ");
        
        $data = [];
        while($row = $result->fetch_object()) {
            $row->settings = $row->settings ? json_decode($row->settings) : new \stdClass();
            $data[] = $row;
        }
        
        return $data;
    }

    public function get_active_products($limit = null) {
        $limit_sql = $limit ? "LIMIT " . (int)$limit : "";
        
        $result = Database::$database->query("
            SELECT p.*, u.name as seller_name, u.email as seller_email 
            FROM `products` p
            LEFT JOIN `users` u ON p.user_id = u.user_id
            WHERE p.status = 1
            ORDER BY p.datetime DESC
            {$limit_sql}
        ");
        
        $data = [];
        while($row = $result->fetch_object()) {
            $row->settings = $row->settings ? json_decode($row->settings) : new \stdClass();
            $data[] = $row;
        }
        
        return $data;
    }

    public function search($query, $limit = 20) {
        $query = Database::clean_string($query);
        
        $result = Database::$database->query("
            SELECT p.*, u.name as seller_name 
            FROM `products` p
            LEFT JOIN `users` u ON p.user_id = u.user_id
            WHERE p.status = 1 
            AND (p.name LIKE '%{$query}%' OR p.description LIKE '%{$query}%')
            ORDER BY p.datetime DESC
            LIMIT {$limit}
        ");
        
        $data = [];
        while($row = $result->fetch_object()) {
            $row->settings = $row->settings ? json_decode($row->settings) : new \stdClass();
            $data[] = $row;
        }
        
        return $data;
    }

    public function increment_views($product_id) {
        Database::$database->query("
            UPDATE `products` 
            SET `views` = `views` + 1 
            WHERE `product_id` = '" . Database::clean_string($product_id) . "'
        ");
    }

    public function get_stats($user_id) {
        $result = Database::$database->query("
            SELECT 
                COUNT(*) as total_products,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_products,
                SUM(views) as total_views,
                SUM(sales) as total_sales
            FROM `products` 
            WHERE `user_id` = '" . Database::clean_string($user_id) . "'
        ");
        
        return $result->fetch_object();
    }
}