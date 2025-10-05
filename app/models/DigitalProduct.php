<?php

namespace Altum\Models;

use Altum\Database\Database;

class DigitalProduct extends Model {

    public function get_product($product_id) {
        return Database::get('*', 'digital_products', ['product_id' => $product_id]);
    }

    public function get_products_by_user($user_id) {
        $result = Database::$database->query("SELECT * FROM `digital_products` WHERE `user_id` = {$user_id} ORDER BY `date` DESC");
        $data = [];
        
        while($row = $result->fetch_object()) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function get_active_products_by_user($user_id) {
        $result = Database::$database->query("SELECT * FROM `digital_products` WHERE `user_id` = {$user_id} AND `status` = 'active' ORDER BY `date` DESC");
        $data = [];
        
        while($row = $result->fetch_object()) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function create_product($data) {
        return Database::insert('digital_products', $data);
    }

    public function update_product($product_id, $data) {
        return Database::update('digital_products', $data, ['product_id' => $product_id]);
    }

    public function delete_product($product_id) {
        return Database::$database->query("DELETE FROM `digital_products` WHERE `product_id` = {$product_id}");
    }

    public function get_all_products($limit = null) {
        $limit_query = $limit ? "LIMIT {$limit}" : "";
        $result = Database::$database->query("SELECT dp.*, u.name as seller_name FROM `digital_products` dp JOIN `users` u ON dp.user_id = u.user_id WHERE dp.status = 'active' ORDER BY dp.date DESC {$limit_query}");
        $data = [];
        
        while($row = $result->fetch_object()) {
            $data[] = $row;
        }
        
        return $data;
    }
}