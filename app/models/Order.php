<?php

namespace Altum\Models;

use Altum\Database\Database;

class Order extends Model {

    public function get($order_id) {
        $data = Database::get('*', 'orders', ['order_id' => $order_id]);
        
        if($data) {
            /* Parse the order settings and payment details */
            $data->settings = json_decode($data->settings);
            $data->payment_details = json_decode($data->payment_details);
        }

        return $data;
    }

    public function get_by_transaction_id($transaction_id) {
        $data = Database::get('*', 'orders', ['transaction_id' => $transaction_id]);
        
        if($data) {
            $data->settings = json_decode($data->settings);
            $data->payment_details = json_decode($data->payment_details);
        }

        return $data;
    }

    public function get_by_user($user_id, $limit = null, $offset = null) {
        $where_clause = "WHERE `user_id` = {$user_id}";
        $order_by = "ORDER BY `datetime` DESC";
        
        if($limit) {
            $query = "SELECT * FROM `orders` {$where_clause} {$order_by} LIMIT {$offset}, {$limit}";
        } else {
            $query = "SELECT * FROM `orders` {$where_clause} {$order_by}";
        }
        
        $result = Database::$database->query($query);
        $data = [];
        
        while($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $row->payment_details = json_decode($row->payment_details);
            $data[] = $row;
        }
        
        return $data;
    }

    public function get_by_product_owner($user_id, $limit = null, $offset = null) {
        $where_clause = "
            WHERE o.product_id IN (
                SELECT product_id FROM products WHERE user_id = {$user_id}
            )
        ";
        $order_by = "ORDER BY o.datetime DESC";
        
        if($limit) {
            $query = "
                SELECT o.*, p.name as product_name 
                FROM `orders` o 
                LEFT JOIN `products` p ON o.product_id = p.product_id 
                {$where_clause} {$order_by} 
                LIMIT {$offset}, {$limit}
            ";
        } else {
            $query = "
                SELECT o.*, p.name as product_name 
                FROM `orders` o 
                LEFT JOIN `products` p ON o.product_id = p.product_id 
                {$where_clause} {$order_by}
            ";
        }
        
        $result = Database::$database->query($query);
        $data = [];
        
        while($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $row->payment_details = json_decode($row->payment_details);
            $data[] = $row;
        }
        
        return $data;
    }

    public function create($data) {
        $order_id = 'ORD-' . strtoupper(uniqid());
        $transaction_id = 'TXN-' . strtoupper(uniqid());
        
        $insert_data = [
            'order_id' => $order_id,
            'transaction_id' => $transaction_id,
            'user_id' => $data['user_id'] ?? null,
            'product_id' => Database::clean_string($data['product_id']),
            'amount' => (int) $data['amount'],
            'customer_name' => Database::clean_string($data['customer_name']),
            'customer_email' => Database::clean_string($data['customer_email']),
            'customer_phone' => Database::clean_string($data['customer_phone']),
            'payment_method' => Database::clean_string($data['payment_method'] ?? 'duitku'),
            'status' => 'pending',
            'payment_details' => json_encode($data['payment_details'] ?? []),
            'settings' => json_encode($data['settings'] ?? []),
            'datetime' => \Altum\Date::$date
        ];
        
        Database::insert('orders', $insert_data);
        
        return [
            'order_id' => $order_id,
            'transaction_id' => $transaction_id
        ];
    }

    public function update_status($order_id, $status, $payment_details = null) {
        $update_data = [
            'status' => $status
        ];
        
        if($status === 'completed') {
            $update_data['completed_datetime'] = \Altum\Date::$date;
        }
        
        if($payment_details) {
            $update_data['payment_details'] = json_encode($payment_details);
        }
        
        Database::update('orders', $update_data, ['order_id' => $order_id]);
    }

    public function update_payment_details($order_id, $payment_details) {
        Database::update('orders', [
            'payment_details' => json_encode($payment_details)
        ], ['order_id' => $order_id]);
    }

    public function get_sales_stats($user_id, $period = 'all') {
        $date_filter = '';
        
        switch($period) {
            case 'today':
                $date_filter = "AND DATE(o.datetime) = CURDATE()";
                break;
            case 'week':
                $date_filter = "AND o.datetime >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $date_filter = "AND o.datetime >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                break;
            case 'year':
                $date_filter = "AND o.datetime >= DATE_SUB(NOW(), INTERVAL 365 DAY)";
                break;
        }
        
        $query = "
            SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN o.status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN o.status = 'completed' THEN o.amount ELSE 0 END) as total_revenue,
                AVG(CASE WHEN o.status = 'completed' THEN o.amount ELSE NULL END) as avg_order_value
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE p.user_id = {$user_id} {$date_filter}
        ";
        
        $result = Database::$database->query($query);
        return $result->fetch_object();
    }

    public function get_top_products($user_id, $limit = 5) {
        $query = "
            SELECT 
                p.product_id,
                p.name,
                p.price,
                COUNT(o.id) as order_count,
                SUM(CASE WHEN o.status = 'completed' THEN o.amount ELSE 0 END) as revenue
            FROM `products` p
            LEFT JOIN `orders` o ON p.product_id = o.product_id AND o.status = 'completed'
            WHERE p.user_id = {$user_id}
            GROUP BY p.product_id
            ORDER BY order_count DESC, revenue DESC
            LIMIT {$limit}
        ";
        
        $result = Database::$database->query($query);
        $data = [];
        
        while($row = $result->fetch_object()) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function check_customer_purchased($customer_email, $product_id) {
        $query = "
            SELECT COUNT(*) as count 
            FROM `orders` 
            WHERE `customer_email` = '{$customer_email}' 
            AND `product_id` = '{$product_id}' 
            AND `status` = 'completed'
        ";
        
        $result = Database::$database->query($query);
        $data = $result->fetch_object();
        
        return $data->count > 0;
    }

    public function get_customer_orders($customer_email) {
        $query = "
            SELECT o.*, p.name as product_name, p.digital_link
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE o.customer_email = '{$customer_email}' 
            AND o.status = 'completed'
            ORDER BY o.datetime DESC
        ";
        
        $result = Database::$database->query($query);
        $data = [];
        
        while($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $row->payment_details = json_decode($row->payment_details);
            $data[] = $row;
        }
        
        return $data;
    }
}