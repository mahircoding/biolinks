<?php

namespace Altum\Models;

use Altum\Database\Database;

class Order extends Model {

    public function get($order_id) {
        $data = Database::get('*', 'orders', ['order_id' => $order_id]);
        
        if($data) {
            $data->settings = $data->settings ? json_decode($data->settings) : new \stdClass();
        }
        
        return $data;
    }

    public function get_by_transaction_id($transaction_id) {
        $data = Database::get('*', 'orders', ['transaction_id' => $transaction_id]);
        
        if($data) {
            $data->settings = $data->settings ? json_decode($data->settings) : new \stdClass();
        }
        
        return $data;
    }

    public function create($user_id, $product_id, $amount, $payment_method = 'midtrans', $settings = null) {
        $order_id = 'ORD-' . time() . '-' . rand(1000, 9999);
        $transaction_id = 'TXN-' . time() . '-' . rand(10000, 99999);
        
        $settings_json = $settings ? json_encode($settings) : null;
        
        Database::insert('orders', [
            'order_id' => $order_id,
            'transaction_id' => $transaction_id,
            'user_id' => $user_id,
            'product_id' => $product_id,
            'amount' => $amount,
            'payment_method' => $payment_method,
            'status' => 'pending',
            'settings' => $settings_json,
            'datetime' => \Altum\Date::$date
        ]);
        
        return $order_id;
    }

    public function update_status($order_id, $status, $payment_details = null) {
        $data = ['status' => $status];
        
        if($payment_details) {
            $data['payment_details'] = json_encode($payment_details);
        }
        
        if($status == 'completed') {
            $data['completed_datetime'] = \Altum\Date::$date;
            
            // Update product sales count
            $order = $this->get($order_id);
            if($order) {
                Database::$database->query("
                    UPDATE `products` 
                    SET `sales` = `sales` + 1 
                    WHERE `product_id` = '" . Database::clean_string($order->product_id) . "'
                ");
            }
        }
        
        Database::update('orders', $data, ['order_id' => $order_id]);
    }

    public function get_user_orders($user_id, $status = null) {
        $conditions = ['user_id' => $user_id];
        
        if($status) {
            $conditions['status'] = $status;
        }
        
        $where_clause = implode(' AND ', array_map(function($key, $value) {
            return "`{$key}` = '" . Database::clean_string($value) . "'";
        }, array_keys($conditions), $conditions));
        
        $result = Database::$database->query("
            SELECT o.*, p.name as product_name, p.description as product_description, p.image as product_image, p.digital_link
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE {$where_clause}
            ORDER BY o.datetime DESC
        ");
        
        $data = [];
        while($row = $result->fetch_object()) {
            $row->settings = $row->settings ? json_decode($row->settings) : new \stdClass();
            $data[] = $row;
        }
        
        return $data;
    }

    public function get_product_orders($product_id, $status = null) {
        $conditions = ['product_id' => $product_id];
        
        if($status) {
            $conditions['status'] = $status;
        }
        
        $where_clause = implode(' AND ', array_map(function($key, $value) {
            return "`{$key}` = '" . Database::clean_string($value) . "'";
        }, array_keys($conditions), $conditions));
        
        $result = Database::$database->query("
            SELECT o.*, u.name as user_name, u.email as user_email
            FROM `orders` o
            LEFT JOIN `users` u ON o.user_id = u.user_id
            WHERE {$where_clause}
            ORDER BY o.datetime DESC
        ");
        
        $data = [];
        while($row = $result->fetch_object()) {
            $row->settings = $row->settings ? json_decode($row->settings) : new \stdClass();
            $data[] = $row;
        }
        
        return $data;
    }

    public function has_user_purchased($user_id, $product_id) {
        $result = Database::$database->query("
            SELECT COUNT(*) as count 
            FROM `orders` 
            WHERE `user_id` = '" . Database::clean_string($user_id) . "' 
            AND `product_id` = '" . Database::clean_string($product_id) . "' 
            AND `status` = 'completed'
        ");
        
        $data = $result->fetch_object();
        return $data->count > 0;
    }

    public function get_sales_stats($user_id = null, $period = 'all') {
        $where_conditions = ["status = 'completed'"];
        
        if($user_id) {
            $where_conditions[] = "p.user_id = '" . Database::clean_string($user_id) . "'";
        }
        
        // Add date filter based on period
        switch($period) {
            case 'today':
                $where_conditions[] = "DATE(o.completed_datetime) = CURDATE()";
                break;
            case 'week':
                $where_conditions[] = "o.completed_datetime >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $where_conditions[] = "o.completed_datetime >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                break;
            case 'year':
                $where_conditions[] = "o.completed_datetime >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                break;
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        $result = Database::$database->query("
            SELECT 
                COUNT(*) as total_orders,
                SUM(o.amount) as total_revenue,
                AVG(o.amount) as average_order_value
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE {$where_clause}
        ");
        
        return $result->fetch_object();
    }

    public function get_recent_orders($limit = 10, $user_id = null) {
        $where_clause = "1=1";
        
        if($user_id) {
            $where_clause = "p.user_id = '" . Database::clean_string($user_id) . "'";
        }
        
        $result = Database::$database->query("
            SELECT o.*, p.name as product_name, u.name as buyer_name, u.email as buyer_email
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            LEFT JOIN `users` u ON o.user_id = u.user_id
            WHERE {$where_clause}
            ORDER BY o.datetime DESC
            LIMIT " . (int)$limit
        );
        
        $data = [];
        while($row = $result->fetch_object()) {
            $row->settings = $row->settings ? json_decode($row->settings) : new \stdClass();
            $data[] = $row;
        }
        
        return $data;
    }
}