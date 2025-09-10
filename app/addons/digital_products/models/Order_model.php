<?php
namespace Altum\Addons\DigitalProducts\Models;

class Order_model {
    public function get_by_user($user_id) {
        $sql = "SELECT o.*, p.name, p.price FROM orders o 
                JOIN products p ON o.product_id = p.product_id 
                WHERE p.user_id = ?";
        return database()->rawQuery($sql, [$user_id]);
    }
}
