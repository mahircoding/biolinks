<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Dashboard extends Controller {

    public function index() {

        Authentication::guard();

        /* Check if the user has access to this page */
        if(!$this->user->package_settings->analytics_is_enabled) {
            redirect('links');
        }

        /* Get total products */
        $total_products = Database::simple_get('COUNT(*)', 'products', ['user_id' => $this->user->user_id]);

        /* Get total orders */
        $total_orders_result = Database::$database->query("SELECT COUNT(*) as total FROM `orders` o LEFT JOIN `products` p ON o.product_id = p.product_id WHERE p.user_id = {$this->user->user_id}");
        $total_orders = $total_orders_result->fetch_object()->total ?? 0;

        /* Get completed orders */
        $completed_orders_result = Database::$database->query("SELECT COUNT(*) as total FROM `orders` o LEFT JOIN `products` p ON o.product_id = p.product_id WHERE p.user_id = {$this->user->user_id} AND o.status = 'completed'");
        $completed_orders = $completed_orders_result->fetch_object()->total ?? 0;

        /* Get total revenue */
        $total_revenue_result = Database::$database->query("SELECT SUM(total_amount) as total FROM `orders` o LEFT JOIN `products` p ON o.product_id = p.product_id WHERE p.user_id = {$this->user->user_id} AND o.status = 'completed'");
        $total_revenue = $total_revenue_result->fetch_object()->total ?? 0;

        /* Get monthly revenue for chart */
        $monthly_revenue = [];
        for($i = 11; $i >= 0; $i--) {
            $month_start = (new \DateTime())->modify("-{$i} months")->format('Y-m-01');
            $month_end = (new \DateTime())->modify("-{$i} months")->format('Y-m-t');
            
            $revenue_result = Database::$database->query("
                SELECT COALESCE(SUM(o.total_amount), 0) as revenue 
                FROM `orders` o
                LEFT JOIN `products` p ON o.product_id = p.product_id
                WHERE p.user_id = {$this->user->user_id} 
                AND o.status = 'completed' 
                AND DATE(o.datetime) BETWEEN '{$month_start}' AND '{$month_end}'
            ");
            $monthly_revenue[] = [
                'month' => (new \DateTime())->modify("-{$i} months")->format('M Y'),
                'revenue' => $revenue_result->fetch_object()->revenue
            ];
        }

        /* Get recent orders */
        $recent_orders_result = Database::$database->query("
            SELECT o.*, p.title as product_title, p.price as product_price
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE p.user_id = {$this->user->user_id}
            ORDER BY o.datetime DESC
            LIMIT 10
        ");
        
        $recent_orders = [];
        while($row = $recent_orders_result->fetch_object()) {
            $recent_orders[] = $row;
        }

        /* Get top selling products */
        $top_products_result = Database::$database->query("
            SELECT p.*, COUNT(o.order_id) as order_count, SUM(o.total_amount) as total_revenue
            FROM `products` p
            LEFT JOIN `orders` o ON p.product_id = o.product_id AND o.status = 'completed'
            WHERE p.user_id = {$this->user->user_id}
            GROUP BY p.product_id
            ORDER BY order_count DESC, total_revenue DESC
            LIMIT 5
        ");
        
        $top_products = [];
        while($row = $top_products_result->fetch_object()) {
            $top_products[] = $row;
        }

        /* Get daily sales for the last 30 days */
        $daily_sales = [];
        for($i = 29; $i >= 0; $i--) {
            $date_check = (new \DateTime())->modify("-{$i} days")->format('Y-m-d');
            
            $sales_result = Database::$database->query("
                SELECT COUNT(*) as orders, COALESCE(SUM(o.total_amount), 0) as revenue
                FROM `orders` o
                LEFT JOIN `products` p ON o.product_id = p.product_id
                WHERE p.user_id = {$this->user->user_id} 
                AND o.status = 'completed'
                AND DATE(o.datetime) = '{$date_check}'
            ");
            
            $sales_data = $sales_result->fetch_object();
            $daily_sales[] = [
                'date' => $date_check,
                'orders' => $sales_data->orders,
                'revenue' => $sales_data->revenue
            ];
        }

        /* Prepare the View */
        $data = [
            'total_products' => $total_products,
            'total_orders' => $total_orders,
            'completed_orders' => $completed_orders,
            'total_revenue' => $total_revenue,
            'monthly_revenue' => $monthly_revenue,
            'recent_orders' => $recent_orders,
            'top_products' => $top_products,
            'daily_sales' => $daily_sales
        ];

        $view = new \Altum\Views\View('dashboard/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function orders() {
        Authentication::guard();

        /* Get total count for pagination */
        $total_rows_result = Database::$database->query("SELECT COUNT(*) as total FROM `orders` o LEFT JOIN `products` p ON o.product_id = p.product_id WHERE p.user_id = {$this->user->user_id}");
        $total_rows = $total_rows_result->fetch_object()->total ?? 0;
        
        /* Simple pagination */
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 25;
        $offset = ($page - 1) * $per_page;

        /* Get orders */
        $orders_result = Database::$database->query("
            SELECT o.*, p.title as product_title, p.price as product_price
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE p.user_id = {$this->user->user_id}
            ORDER BY o.datetime DESC
            LIMIT {$offset}, {$per_page}
        ");

        $orders = [];
        while($row = $orders_result->fetch_object()) {
            $orders[] = $row;
        }

        /* Prepare the View */
        $data = [
            'orders' => $orders,
            'total_rows' => $total_rows,
            'current_page' => $page,
            'per_page' => $per_page
        ];

        $view = new \Altum\Views\View('dashboard/orders', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function analytics() {
        Authentication::guard();

        /* Get date range from parameters */
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : (new \DateTime())->modify('-30 days')->format('Y-m-d');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : (new \DateTime())->format('Y-m-d');

        /* Get analytics data */
        $analytics_result = Database::$database->query("
            SELECT 
                DATE(o.datetime) as date,
                COUNT(o.order_id) as orders,
                SUM(o.total_amount) as revenue,
                COUNT(DISTINCT o.customer_email) as unique_customers
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE p.user_id = {$this->user->user_id}
            AND o.status = 'completed'
            AND DATE(o.datetime) BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY DATE(o.datetime)
            ORDER BY date ASC
        ");

        $analytics_data = [];
        while($row = $analytics_result->fetch_object()) {
            $analytics_data[] = $row;
        }

        /* Get product performance */
        $product_performance_result = Database::$database->query("
            SELECT 
                p.title,
                p.price,
                COUNT(o.order_id) as total_orders,
                SUM(o.total_amount) as total_revenue
            FROM `products` p
            LEFT JOIN `orders` o ON p.product_id = o.product_id AND o.status = 'completed'
            WHERE p.user_id = {$this->user->user_id}
            AND DATE(o.datetime) BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY p.product_id
            ORDER BY total_revenue DESC
        ");

        $product_performance = [];
        while($row = $product_performance_result->fetch_object()) {
            $product_performance[] = $row;
        }

        /* Prepare the View */
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'analytics_data' => $analytics_data,
            'product_performance' => $product_performance
        ];

        $view = new \Altum\Views\View('dashboard/analytics', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}
