<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\Order;
use Altum\Models\Product;

class Orders extends Controller {

    public function index() {
        /* Check if user is logged in */
        if(!$this->user) {
            redirect('login');
        }

        /* Get orders for this user */
        $orders_result = Database::$database->query("
            SELECT o.*, p.title as product_title, p.price as product_price
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE p.user_id = {$this->user->user_id}
            ORDER BY o.datetime DESC
        ");

        $orders = [];
        if($orders_result && $orders_result !== false) {
            while($row = $orders_result->fetch_object()) {
                $orders[] = $row;
            }
        }

        /* Get statistics */
        $stats = [
            'total_orders' => Database::simple_get('COUNT(*)', 'orders o LEFT JOIN products p ON o.product_id = p.product_id', ['p.user_id' => $this->user->user_id]),
            'total_revenue' => Database::simple_get('SUM(o.total_amount)', 'orders o LEFT JOIN products p ON o.product_id = p.product_id', ['p.user_id' => $this->user->user_id, 'o.status' => 'completed']) ?? 0,
            'pending_orders' => Database::simple_get('COUNT(*)', 'orders o LEFT JOIN products p ON o.product_id = p.product_id', ['p.user_id' => $this->user->user_id, 'o.status' => 'pending']),
            'completed_orders' => Database::simple_get('COUNT(*)', 'orders o LEFT JOIN products p ON o.product_id = p.product_id', ['p.user_id' => $this->user->user_id, 'o.status' => 'completed'])
        ];

        /* Prepare the View */
        $data = [
            'orders' => $orders,
            'stats' => $stats
        ];

        $view = new \Altum\Views\View('orders/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function checkout() {
        /* Get product */
        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        if(!$product_id) {
            redirect('');
        }

        $product = Database::simple_get('*', 'products', ['product_id' => $product_id, 'is_enabled' => 1]);
        
        if(!$product) {
            $_SESSION['error'][] = 'Produk tidak ditemukan atau tidak aktif.';
            redirect('');
        }

        /* Process checkout form */
        if(!empty($_POST)) {
            /* Clean variables */
            $_POST['name'] = Database::clean_string($_POST['name']);
            $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $_POST['whatsapp'] = Database::clean_string($_POST['whatsapp']);

            /* Check for any errors */
            if(empty($_POST['name'])) {
                $_SESSION['error'][] = 'Nama harus diisi.';
            }

            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = 'Email tidak valid.';
            }

            if(empty($_POST['whatsapp'])) {
                $_SESSION['error'][] = 'Nomor WhatsApp harus diisi.';
            }

            /* If there are no errors, continue */
            if(empty($_SESSION['error'])) {
                /* Generate transaction ID */
                $transaction_id = 'TXN' . time() . rand(1000, 9999);

                /* Create order */
                $order_id = Database::insert('orders', [
                    'transaction_id' => $transaction_id,
                    'product_id' => $product->product_id,
                    'customer_name' => $_POST['name'],
                    'customer_email' => $_POST['email'],
                    'customer_whatsapp' => $_POST['whatsapp'],
                    'total_amount' => $product->price,
                    'status' => 'pending',
                    'payment_method' => 'duitku',
                    'datetime' => \Altum\Date::$date
                ]);

                /* Redirect to payment */
                redirect('orders/payment/' . $transaction_id);
            }
        }

        /* Prepare the View */
        $data = [
            'product' => $product
        ];

        $view = new \Altum\Views\View('orders/checkout', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function payment() {
        /* Get transaction ID */
        $transaction_id = isset($this->params[0]) ? $this->params[0] : null;
        
        if(!$transaction_id) {
            redirect('');
        }

        /* Get order */
        $order = Database::simple_get('*', 'orders', ['transaction_id' => $transaction_id]);
        
        if(!$order) {
            $_SESSION['error'][] = 'Transaksi tidak ditemukan.';
            redirect('');
        }

        /* Get product */
        $product = Database::simple_get('*', 'products', ['product_id' => $order->product_id]);

        /* If order is already completed, redirect to success */
        if($order->status == 'completed') {
            redirect('orders/success/' . $transaction_id);
        }

        /* Prepare payment data for Duitku */
        $payment_data = [
            'merchantCode' => settings()->duitku->merchant_code ?? '',
            'paymentAmount' => $order->total_amount,
            'paymentMethod' => 'VC', // Virtual Account
            'merchantOrderId' => $transaction_id,
            'productDetails' => $product->title,
            'customerVaName' => $order->customer_name,
            'email' => $order->customer_email,
            'phoneNumber' => $order->customer_whatsapp,
            'callbackUrl' => url('webhook/duitku'),
            'returnUrl' => url('orders/success/' . $transaction_id),
            'expiryPeriod' => 60 // 60 minutes
        ];

        /* Prepare the View */
        $data = [
            'order' => $order,
            'product' => $product,
            'payment_data' => $payment_data
        ];

        $view = new \Altum\Views\View('orders/payment', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function success() {
        /* Get transaction ID */
        $transaction_id = isset($this->params[0]) ? $this->params[0] : null;
        
        if(!$transaction_id) {
            redirect('');
        }

        /* Get order */
        $order = Database::simple_get('*', 'orders', ['transaction_id' => $transaction_id]);
        
        if(!$order) {
            $_SESSION['error'][] = 'Transaksi tidak ditemukan.';
            redirect('');
        }

        /* Get product */
        $product = Database::simple_get('*', 'products', ['product_id' => $order->product_id]);

        /* Prepare the View */
        $data = [
            'order' => $order,
            'product' => $product
        ];

        $view = new \Altum\Views\View('orders/success', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function view() {
        /* Check if user is logged in */
        if(!$this->user) {
            redirect('login');
        }

        /* Get order ID */
        $order_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        if(!$order_id) {
            redirect('orders');
        }

        /* Get order with product details */
        $order_result = Database::$database->query("
            SELECT o.*, p.title as product_title, p.price as product_price, p.user_id as seller_id
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE o.order_id = {$order_id} AND p.user_id = {$this->user->user_id}
        ");

        if($order_result->num_rows == 0) {
            $_SESSION['error'][] = 'Pesanan tidak ditemukan.';
            redirect('orders');
        }

        $order = $order_result->fetch_object();

        /* Prepare the View */
        $data = [
            'order' => $order
        ];

        $view = new \Altum\Views\View('orders/view', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function statistics() {
        /* Check if user is logged in */
        if(!$this->user) {
            redirect('login');
        }

        /* Get date range */
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');

        /* Get sales statistics */
        $stats_result = Database::$database->query("
            SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN o.status = 'completed' THEN o.total_amount ELSE 0 END) as total_revenue,
                SUM(CASE WHEN o.status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN o.status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN o.status = 'failed' THEN 1 ELSE 0 END) as failed_orders
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE p.user_id = {$this->user->user_id}
            AND DATE(o.datetime) BETWEEN '{$start_date}' AND '{$end_date}'
        ");

        $stats = $stats_result->fetch_object();

        /* Get top products */
        $top_products_result = Database::$database->query("
            SELECT 
                p.title,
                p.price,
                COUNT(o.order_id) as total_sales,
                SUM(CASE WHEN o.status = 'completed' THEN o.total_amount ELSE 0 END) as revenue
            FROM `products` p
            LEFT JOIN `orders` o ON p.product_id = o.product_id AND o.status = 'completed'
            WHERE p.user_id = {$this->user->user_id}
            AND DATE(o.datetime) BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY p.product_id
            ORDER BY total_sales DESC
            LIMIT 10
        ");

        $top_products = [];
        while($row = $top_products_result->fetch_object()) {
            $top_products[] = $row;
        }

        /* Get daily sales chart data */
        $daily_sales_result = Database::$database->query("
            SELECT 
                DATE(o.datetime) as date,
                COUNT(*) as orders,
                SUM(CASE WHEN o.status = 'completed' THEN o.total_amount ELSE 0 END) as revenue
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE p.user_id = {$this->user->user_id}
            AND DATE(o.datetime) BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY DATE(o.datetime)
            ORDER BY DATE(o.datetime)
        ");

        $daily_sales = [];
        while($row = $daily_sales_result->fetch_object()) {
            $daily_sales[] = $row;
        }

        /* Prepare the View */
        $data = [
            'stats' => $stats,
            'top_products' => $top_products,
            'daily_sales' => $daily_sales,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $view = new \Altum\Views\View('orders/statistics', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}