<?php

namespace Altum\Models;

use Altum\Database\Database;

class Product extends Model {

    public function get($product_id) {
        $data = Database::get('*', 'products', ['product_id' => $product_id]);
        
        if($data) {
            /* Parse the product settings */
            $data->settings = json_decode($data->settings);
        }

        return $data;
    }

    public function get_by_user($user_id, $limit = null, $offset = null) {
        $where = ['user_id' => $user_id];
        $order_by = 'datetime DESC';
        
        if($limit) {
            $query = "SELECT * FROM `products` WHERE `user_id` = {$user_id} ORDER BY {$order_by} LIMIT {$offset}, {$limit}";
        } else {
            $query = "SELECT * FROM `products` WHERE `user_id` = {$user_id} ORDER BY {$order_by}";
        }
        
        $result = Database::$database->query($query);
        $data = [];
        
        while($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $data[] = $row;
        }
        
        return $data;
    }

    public function get_active_products($limit = null, $offset = null) {
        $where_clause = "WHERE `status` = 1";
        $order_by = "ORDER BY `datetime` DESC";
        
        if($limit) {
            $query = "SELECT * FROM `products` {$where_clause} {$order_by} LIMIT {$offset}, {$limit}";
        } else {
            $query = "SELECT * FROM `products` {$where_clause} {$order_by}";
        }
        
        $result = Database::$database->query($query);
        $data = [];
        
        while($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $data[] = $row;
        }
        
        return $data;
    }

    public function create($user_id, $data) {
        $product_id = md5($user_id . microtime() . rand());
        
        $insert_data = [
            'product_id' => $product_id,
            'user_id' => $user_id,
            'name' => Database::clean_string($data['name']),
            'description' => Database::clean_string($data['description']),
            'price' => (int) $data['price'],
            'image' => $data['image'] ?? null,
            'digital_link' => Database::clean_string($data['digital_link']),
            'status' => (int) ($data['status'] ?? 1),
            'settings' => json_encode($data['settings'] ?? []),
            'datetime' => \Altum\Date::$date
        ];
        
        Database::insert('products', $insert_data);
        
        return $product_id;
    }

    public function update($product_id, $data) {
        $update_data = [];
        
        if(isset($data['name'])) {
            $update_data['name'] = Database::clean_string($data['name']);
        }
        
        if(isset($data['description'])) {
            $update_data['description'] = Database::clean_string($data['description']);
        }
        
        if(isset($data['price'])) {
            $update_data['price'] = (int) $data['price'];
        }
        
        if(isset($data['image'])) {
            $update_data['image'] = $data['image'];
        }
        
        if(isset($data['digital_link'])) {
            $update_data['digital_link'] = Database::clean_string($data['digital_link']);
        }
        
        if(isset($data['status'])) {
            $update_data['status'] = (int) $data['status'];
        }
        
        if(isset($data['settings'])) {
            $update_data['settings'] = json_encode($data['settings']);
        }
        
        Database::update('products', $update_data, ['product_id' => $product_id]);
    }

    public function delete($product_id) {
        Database::$database->query("DELETE FROM `products` WHERE `product_id` = '{$product_id}'");
        Database::$database->query("DELETE FROM `product_logs` WHERE `product_id` = '{$product_id}'");
    }

    public function increment_views($product_id) {
        Database::$database->query("UPDATE `products` SET `views` = `views` + 1 WHERE `product_id` = '{$product_id}'");
    }

    public function increment_sales($product_id) {
        Database::$database->query("UPDATE `products` SET `sales` = `sales` + 1 WHERE `product_id` = '{$product_id}'");
    }

    public function get_user_stats($user_id) {
        $query = "
            SELECT 
                COUNT(*) as total_products,
                SUM(sales) as total_sales,
                SUM(views) as total_views,
                SUM(sales * price) as total_revenue
            FROM `products` 
            WHERE `user_id` = {$user_id}
        ";
        
        $result = Database::$database->query($query);
        return $result->fetch_object();
    }

    public function search($keyword, $limit = 20, $offset = 0) {
        $keyword = Database::clean_string($keyword);
        
        $query = "
            SELECT * FROM `products` 
            WHERE `status` = 1 
            AND (`name` LIKE '%{$keyword}%' OR `description` LIKE '%{$keyword}%')
            ORDER BY `datetime` DESC 
            LIMIT {$offset}, {$limit}
        ";
        
        $result = Database::$database->query($query);
        $data = [];
        
        while($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $data[] = $row;
        }
        
        return $data;
    }
}