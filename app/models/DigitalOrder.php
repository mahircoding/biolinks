<?php

namespace Altum\Models;

use Altum\Database\Database;

class DigitalOrder extends Model {

    public function get_order($order_id) {
        return Database::get('*', 'digital_orders', ['order_id' => $order_id]);
    }

    public function get_orders_by_user($user_id) {
        $result = Database::$database->query("SELECT do.*, dp.name as product_name FROM `digital_orders` do JOIN `digital_products` dp ON do.product_id = dp.product_id WHERE do.user_id = {$user_id} ORDER BY do.date DESC");
        $data = [];
        
        while($row = $result->fetch_object()) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function get_orders_by_product($product_id) {
        $result = Database::$database->query("SELECT * FROM `digital_orders` WHERE `product_id` = {$product_id} ORDER BY `date` DESC");
        $data = [];
        
        while($row = $result->fetch_object()) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function create_order($data) {
        return Database::insert('digital_orders', $data);
    }

    public function update_order($order_id, $data) {
        return Database::update('digital_orders', $data, ['order_id' => $order_id]);
    }

    public function delete_order($order_id) {
        return Database::$database->query("DELETE FROM `digital_orders` WHERE `order_id` = {$order_id}");
    }

    public function get_all_orders($limit = null) {
        $limit_query = $limit ? "LIMIT {$limit}" : "";
        $result = Database::$database->query("SELECT do.*, dp.name as product_name, u.name as seller_name FROM `digital_orders` do JOIN `digital_products` dp ON do.product_id = dp.product_id JOIN `users` u ON do.user_id = u.user_id ORDER BY do.date DESC {$limit_query}");
        $data = [];
        
        while($row = $result->fetch_object()) {
            $data[] = $row;
        }
        
        return $data;
    }
}