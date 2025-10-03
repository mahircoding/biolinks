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

        /* Get statistics for the user's products and orders */
        $date = \Altum\Date::get_start_end_dates_new();

        /* Get total products */
        $total_products = Database::simple_get('COUNT(*)', 'products', ['user_id' => $this->user->user_id]);

        /* Get total orders */
        $total_orders = Database::simple_get('COUNT(*)', 'orders', ['user_id' => $this->user->user_id]);

        /* Get completed orders */
        $completed_orders = Database::simple_get('COUNT(*)', 'orders', ['user_id' => $this->user->user_id, 'status' => 'completed']);

        /* Get total revenue */
        $total_revenue_result = database()->query("SELECT SUM(total_amount) as total FROM `orders` WHERE `user_id` = {$this->user->user_id} AND `status` = 'completed'");
        $total_revenue = $total_revenue_result->fetch_object()->total ?? 0;

        /* Get monthly revenue for chart */
        $monthly_revenue = [];
        for($i = 11; $i >= 0; $i--) {
            $month_start = (new \DateTime())->modify("-{$i} months")->format('Y-m-01');
            $month_end = (new \DateTime())->modify("-{$i} months")->format('Y-m-t');
            
            $revenue_result = database()->query("
                SELECT COALESCE(SUM(total_amount), 0) as revenue 
                FROM `orders` 
                WHERE `user_id` = {$this->user->user_id} 
                AND `status` = 'completed' 
                AND DATE(datetime) BETWEEN '{$month_start}' AND '{$month_end}'
            ");
            
            $monthly_revenue[] = [
                'month' => (new \DateTime())->modify("-{$i} months")->format('M Y'),
                'revenue' => $revenue_result->fetch_object()->revenue ?? 0
            ];
        }

        /* Get recent orders */
        $recent_orders_result = database()->query("
            SELECT o.*, p.title as product_title 
            FROM `orders` o 
            LEFT JOIN `products` p ON o.product_id = p.product_id 
            WHERE o.user_id = {$this->user->user_id} 
            ORDER BY o.datetime DESC 
            LIMIT 10
        ");
        $recent_orders = [];
        while($row = $recent_orders_result->fetch_object()) {
            $recent_orders[] = $row;
        }

        /* Get top selling products */
        $top_products_result = database()->query("
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
            
            $sales_result = database()->query("
                SELECT COUNT(*) as orders, COALESCE(SUM(total_amount), 0) as revenue 
                FROM `orders` 
                WHERE `user_id` = {$this->user->user_id} 
                AND `status` = 'completed' 
                AND DATE(datetime) = '{$date_check}'
            ");
            
            $sales_data = $sales_result->fetch_object();
            $daily_sales[] = [
                'date' => $date_check,
                'orders' => $sales_data->orders ?? 0,
                'revenue' => $sales_data->revenue ?? 0
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
            'daily_sales' => $daily_sales,
            'date' => $date
        ];

        $view = new \Altum\Views\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function orders() {
        Authentication::guard();

        /* Pagination */
        $total_rows = Database::simple_get('COUNT(*)', 'orders', ['user_id' => $this->user->user_id]);
        $paginator = new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('dashboard/orders?page=%d'));

        /* Get orders */
        $orders_result = database()->query("
            SELECT o.*, p.title as product_title, p.price as product_price
            FROM `orders` o 
            LEFT JOIN `products` p ON o.product_id = p.product_id 
            WHERE o.user_id = {$this->user->user_id} 
            ORDER BY o.datetime DESC 
            {$paginator->get_sql_limit()}
        ");

        $orders = [];
        while($row = $orders_result->fetch_object()) {
            $orders[] = $row;
        }

        /* Prepare the View */
        $data = [
            'orders' => $orders,
            'paginator' => $paginator
        ];

        $view = new \Altum\Views\View('dashboard/orders', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function analytics() {
        Authentication::guard();

        /* Check if the user has access to analytics */
        if(!$this->user->package_settings->analytics_is_enabled) {
            redirect('dashboard');
        }

        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : (new \DateTime())->modify('-30 days')->format('Y-m-d');
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : (new \DateTime())->format('Y-m-d');

        /* Get analytics data */
        $analytics_result = database()->query("
            SELECT 
                DATE(datetime) as date,
                COUNT(*) as orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as revenue
            FROM `orders` 
            WHERE `user_id` = {$this->user->user_id} 
            AND DATE(datetime) BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY DATE(datetime)
            ORDER BY date ASC
        ");

        $analytics_data = [];
        while($row = $analytics_result->fetch_object()) {
            $analytics_data[] = $row;
        }

        /* Get product performance */
        $product_performance_result = database()->query("
            SELECT 
                p.title,
                p.price,
                COUNT(o.order_id) as total_orders,
                SUM(CASE WHEN o.status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN o.status = 'completed' THEN o.total_amount ELSE 0 END) as revenue
            FROM `products` p 
            LEFT JOIN `orders` o ON p.product_id = o.product_id 
                AND DATE(o.datetime) BETWEEN '{$start_date}' AND '{$end_date}'
            WHERE p.user_id = {$this->user->user_id}
            GROUP BY p.product_id
            ORDER BY revenue DESC
        ");

        $product_performance = [];
        while($row = $product_performance_result->fetch_object()) {
            $product_performance[] = $row;
        }

        /* Prepare the View */
        $data = [
            'analytics_data' => $analytics_data,
            'product_performance' => $product_performance,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $view = new \Altum\Views\View('dashboard/analytics', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}
