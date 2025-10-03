<?php

namespace Altum\Models;

class Order extends Model {
    public function get_orders_by_user_id($user_id, $page = 1, $max_items = 10) {
        $offset = ($page - 1) * $max_items;
        
        $query = "
            SELECT * FROM `orders` 
            WHERE `user_id` = ? 
            ORDER BY `datetime` DESC 
            LIMIT ? OFFSET ?
        ";
        
        $orders = Database::query($query, [$user_id, $max_items, $offset]);
        
        return $orders ? $orders->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_order_by_id($order_id) {
        $query = "SELECT * FROM `orders` WHERE `order_id` = ?";
        $result = Database::query($query, [$order_id]);
        
        return $result ? $result->fetch_assoc() : null;
    }

    public function get_order_by_transaction_id($transaction_id) {
        $query = "SELECT * FROM `orders` WHERE `transaction_id` = ?";
        $result = Database::query($query, [$transaction_id]);
        
        return $result ? $result->fetch_assoc() : null;
    }

    public function get_orders_by_product_id($product_id, $page = 1, $max_items = 10) {
        $offset = ($page - 1) * $max_items;
        
        $query = "
            SELECT * FROM `orders` 
            WHERE `product_id` = ? 
            ORDER BY `datetime` DESC 
            LIMIT ? OFFSET ?
        ";
        
        $orders = Database::query($query, [$product_id, $max_items, $offset]);
        
        return $orders ? $orders->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_guest_orders($email, $page = 1, $max_items = 10) {
        $offset = ($page - 1) * $max_items;
        
        $query = "
            SELECT * FROM `orders` 
            WHERE `customer_email` = ? AND `user_id` IS NULL
            ORDER BY `datetime` DESC 
            LIMIT ? OFFSET ?
        ";
        
        $orders = Database::query($query, [$email, $max_items, $offset]);
        
        return $orders ? $orders->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create($data) {
        $order_id = string_generate(10);
        $transaction_id = string_generate(10);
        
        $query = "
            INSERT INTO `orders` 
            (`order_id`, `transaction_id`, `user_id`, `product_id`, `amount`, `customer_name`, `customer_email`, `customer_phone`, `payment_method`, `status`, `payment_details`, `settings`, `datetime`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $datetime = \Altum\Date::$date;
        
        Database::query($query, [
            $order_id,
            $transaction_id,
            $data['user_id'] ?? null,
            $data['product_id'],
            $data['amount'],
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['payment_method'],
            $data['status'],
            $data['payment_details'] ?? null,
            $data['settings'] ?? null,
            $datetime
        ]);
        
        return [
            'order_id' => $order_id,
            'transaction_id' => $transaction_id
        ];
    }

    public function update($order_id, $data) {
        $query = "
            UPDATE `orders` 
            SET 
                `status` = ?, 
                `payment_details` = ?, 
                `settings` = ?,
                `completed_datetime` = ?
            WHERE `order_id` = ?
        ";
        
        Database::query($query, [
            $data['status'],
            $data['payment_details'] ?? null,
            $data['settings'] ?? null,
            $data['completed_datetime'] ?? null,
            $order_id
        ]);
        
        return true;
    }

    public function update_status($order_id, $status, $payment_details = null) {
        $query = "
            UPDATE `orders` 
            SET `status` = ?, `payment_details` = ?, `completed_datetime` = ?
            WHERE `order_id` = ?
        ";
        
        $completed_datetime = ($status == 'completed') ? \Altum\Date::$date : null;
        
        Database::query($query, [
            $status,
            $payment_details,
            $completed_datetime,
            $order_id
        ]);
        
        return true;
    }

    public function get_user_total_orders($user_id) {
        $query = "SELECT COUNT(*) as `total` FROM `orders` WHERE `user_id` = ?";
        $result = Database::query($query, [$user_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_user_completed_orders($user_id) {
        $query = "SELECT COUNT(*) as `total` FROM `orders` WHERE `user_id` = ? AND `status` = 'completed'";
        $result = Database::query($query, [$user_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_user_total_revenue($user_id) {
        $query = "
            SELECT COALESCE(SUM(`amount`), 0) as `total` 
            FROM `orders` 
            WHERE `user_id` = ? AND `status` = 'completed'
        ";
        $result = Database::query($query, [$user_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_product_total_sales($product_id) {
        $query = "
            SELECT COUNT(*) as `total` 
            FROM `orders` 
            WHERE `product_id` = ? AND `status` = 'completed'
        ";
        $result = Database::query($query, [$product_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_product_total_revenue($product_id) {
        $query = "
            SELECT COALESCE(SUM(`amount`), 0) as `total` 
            FROM `orders` 
            WHERE `product_id` = ? AND `status` = 'completed'
        ";
        $result = Database::query($query, [$product_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function check_existing_order($product_id, $user_id = null, $customer_email = null) {
        $query = "
            SELECT COUNT(*) as `total` 
            FROM `orders` 
            WHERE `product_id` = ? 
            AND (`status` = 'completed' OR `status` = 'processing')
        ";
        
        $params = [$product_id];
        
        if ($user_id) {
            $query .= " AND `user_id` = ?";
            $params[] = $user_id;
        } elseif ($customer_email) {
            $query .= " AND `customer_email` = ? AND `user_id` IS NULL";
            $params[] = $customer_email;
        }
        
        $result = Database::query($query, $params);
        return $result ? $result->fetch_assoc()['total'] > 0 : false;
    }

    public function get_order_statistics($user_id) {
        $query = "
            SELECT 
                COUNT(*) as `total_orders`,
                COUNT(CASE WHEN `status` = 'completed' THEN 1 END) as `completed_orders`,
                COUNT(CASE WHEN `status` = 'pending' THEN 1 END) as `pending_orders`,
                COUNT(CASE WHEN `status` = 'failed' THEN 1 END) as `failed_orders`,
                COALESCE(SUM(CASE WHEN `status` = 'completed' THEN `amount` END), 0) as `total_revenue`
            FROM `orders` 
            WHERE `user_id` = ?
        ";
        
        $result = Database::query($query, [$user_id]);
        return $result ? $result->fetch_assoc() : [
            'total_orders' => 0,
            'completed_orders' => 0,
            'pending_orders' => 0,
            'failed_orders' => 0,
            'total_revenue' => 0
        ];
    }

    public function get_top_products($user_id, $limit = 5) {
        $query = "
            SELECT 
                `product_id`,
                COUNT(*) as `sales_count`,
                COALESCE(SUM(`amount`), 0) as `total_revenue`
            FROM `orders` 
            WHERE `user_id` = ? AND `status` = 'completed'
            GROUP BY `product_id`
            ORDER BY `total_revenue` DESC
            LIMIT ?
        ";
        
        $result = Database::query($query, [$user_id, $limit]);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_monthly_sales($user_id, $year, $month) {
        $query = "
            SELECT 
                COUNT(*) as `sales_count`,
                COALESCE(SUM(`amount`), 0) as `total_revenue`
            FROM `orders` 
            WHERE `user_id` = ? 
            AND `status` = 'completed'
            AND YEAR(`datetime`) = ? 
            AND MONTH(`datetime`) = ?
        ";
        
        $result = Database::query($query, [$user_id, $year, $month]);
        return $result ? $result->fetch_assoc() : [
            'sales_count' => 0,
            'total_revenue' => 0
        ];
    }

    public function get_daily_sales($user_id, $days = 30) {
        $query = "
            SELECT 
                DATE(`datetime`) as `date`,
                COUNT(*) as `sales_count`,
                COALESCE(SUM(`amount`), 0) as `total_revenue`
            FROM `orders` 
            WHERE `user_id` = ? 
            AND `status` = 'completed'
            AND `datetime` >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(`datetime`)
            ORDER BY `date` DESC
        ";
        
        $result = Database::query($query, [$user_id, $days]);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_pending_orders_count($user_id) {
        $query = "SELECT COUNT(*) as `total` FROM `orders` WHERE `user_id` = ? AND `status` = 'pending'";
        $result = Database::query($query, [$user_id]);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_guest_order_by_email_and_product($email, $product_id) {
        $query = "
            SELECT * FROM `orders` 
            WHERE `customer_email` = ? AND `product_id` = ? AND `user_id` IS NULL
            ORDER BY `datetime` DESC 
            LIMIT 1
        ";
        
        $result = Database::query($query, [$email, $product_id]);
        return $result ? $result->fetch_assoc() : null;
    }

    public function delete_old_orders($days = 30) {
        $query = "
            DELETE FROM `orders` 
            WHERE `status` = 'failed' 
            AND `datetime` < DATE_SUB(NOW(), INTERVAL ? DAY)
        ";
        
        Database::query($query, [$days]);
        return true;
    }

    public function delete($order_id, $user_id) {
        $query = "DELETE FROM `orders` WHERE `order_id` = ? AND `user_id` = ?";
        Database::query($query, [$order_id, $user_id]);
        return true;
    }
}