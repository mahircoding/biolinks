<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\Product;
use Altum\Models\User;

class Store extends Controller {

    public function index() {
        /* Get the username from URL */
        $username = isset($this->params[0]) ? $this->params[0] : null;
        
        if(!$username) {
            redirect('');
        }

        /* Get user by username */
        $store_user = Database::simple_get('*', 'users', ['name' => $username, 'status' => 1]);
        
        if(!$store_user) {
            $_SESSION['error'][] = 'Toko tidak ditemukan.';
            redirect('');
        }

        /* Get user's active products */
        $products_result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = {$store_user->user_id} AND `is_enabled` = 1 
            ORDER BY `datetime` DESC
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $products[] = $row;
        }

        /* Get store statistics */
        $stats = [
            'total_products' => count($products),
            'total_sales' => Database::simple_get('SUM(sales)', 'products', ['user_id' => $store_user->user_id]) ?? 0
        ];

        /* Prepare the View */
        $data = [
            'store_user' => $store_user,
            'products' => $products,
            'stats' => $stats
        ];

        $view = new \Altum\Views\View('store/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function product() {
        /* Get the username and product ID from URL */
        $username = isset($this->params[0]) ? $this->params[0] : null;
        $product_id = isset($this->params[1]) ? (int) $this->params[1] : null;
        
        if(!$username || !$product_id) {
            redirect('');
        }

        /* Get user by username */
        $store_user = Database::simple_get('*', 'users', ['name' => $username, 'status' => 1]);
        
        if(!$store_user) {
            $_SESSION['error'][] = 'Toko tidak ditemukan.';
            redirect('');
        }

        /* Get product */
        $product = Database::simple_get('*', 'products', [
            'product_id' => $product_id, 
            'user_id' => $store_user->user_id, 
            'is_enabled' => 1
        ]);
        
        if(!$product) {
            $_SESSION['error'][] = 'Produk tidak ditemukan atau tidak aktif.';
            redirect('store/' . $username);
        }

        /* Update product views */
        Database::update('products', [
            'views' => $product->views + 1
        ], [
            'product_id' => $product_id
        ]);

        /* Get related products from same user */
        $related_products_result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = {$store_user->user_id} 
            AND `product_id` != {$product_id} 
            AND `is_enabled` = 1 
            ORDER BY RAND() 
            LIMIT 4
        ");

        $related_products = [];
        while($row = $related_products_result->fetch_object()) {
            $related_products[] = $row;
        }

        /* Prepare the View */
        $data = [
            'store_user' => $store_user,
            'product' => $product,
            'related_products' => $related_products
        ];

        $view = new \Altum\Views\View('store/product', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function checkout() {
        /* Get the username and product ID from URL */
        $username = isset($this->params[0]) ? $this->params[0] : null;
        $product_id = isset($this->params[1]) ? (int) $this->params[1] : null;
        
        if(!$username || !$product_id) {
            redirect('');
        }

        /* Get user by username */
        $store_user = Database::simple_get('*', 'users', ['name' => $username, 'status' => 1]);
        
        if(!$store_user) {
            $_SESSION['error'][] = 'Toko tidak ditemukan.';
            redirect('');
        }

        /* Get product */
        $product = Database::simple_get('*', 'products', [
            'product_id' => $product_id, 
            'user_id' => $store_user->user_id, 
            'is_enabled' => 1
        ]);
        
        if(!$product) {
            $_SESSION['error'][] = 'Produk tidak ditemukan atau tidak aktif.';
            redirect('store/' . $username);
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
                redirect('store/' . $username . '/payment/' . $transaction_id);
            }
        }

        /* Prepare the View */
        $data = [
            'store_user' => $store_user,
            'product' => $product
        ];

        $view = new \Altum\Views\View('store/checkout', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function payment() {
        /* Get the username and transaction ID from URL */
        $username = isset($this->params[0]) ? $this->params[0] : null;
        $transaction_id = isset($this->params[1]) ? $this->params[1] : null;
        
        if(!$username || !$transaction_id) {
            redirect('');
        }

        /* Get user by username */
        $store_user = Database::simple_get('*', 'users', ['name' => $username, 'status' => 1]);
        
        if(!$store_user) {
            $_SESSION['error'][] = 'Toko tidak ditemukan.';
            redirect('');
        }

        /* Get order */
        $order_result = Database::$database->query("
            SELECT o.*, p.title as product_title, p.price as product_price
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE o.transaction_id = '{$transaction_id}' AND p.user_id = {$store_user->user_id}
        ");
        
        if($order_result->num_rows == 0) {
            $_SESSION['error'][] = 'Transaksi tidak ditemukan.';
            redirect('store/' . $username);
        }

        $order = $order_result->fetch_object();

        /* If order is already completed, redirect to success */
        if($order->status == 'completed') {
            redirect('store/' . $username . '/success/' . $transaction_id);
        }

        /* Initialize Duitku payment */
        require_once APP_PATH . 'helpers/Duitku.php';
        
        $duitku = new \Altum\Helpers\Duitku(
            $this->settings->duitku->merchant_code ?? '',
            $this->settings->duitku->api_key ?? '',
            $this->settings->duitku->sandbox_mode ?? true
        );

        /* Prepare payment data */
        $payment_data = [
            'amount' => $order->total_amount,
            'order_id' => $transaction_id,
            'product_details' => $order->product_title,
            'customer_name' => $order->customer_name,
            'email' => $order->customer_email,
            'phone' => $order->customer_whatsapp,
            'callback_url' => url('webhook/duitku'),
            'return_url' => url('store/' . $username . '/success/' . $transaction_id),
            'expiry_period' => 60
        ];

        try {
            $payment_response = $duitku->createInvoice($payment_data);
        } catch(\Exception $e) {
            $_SESSION['error'][] = 'Gagal membuat invoice pembayaran: ' . $e->getMessage();
            redirect('store/' . $username . '/checkout/' . $order->product_id);
        }

        /* Prepare the View */
        $data = [
            'store_user' => $store_user,
            'order' => $order,
            'payment_response' => $payment_response
        ];

        $view = new \Altum\Views\View('store/payment', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function success() {
        /* Get the username and transaction ID from URL */
        $username = isset($this->params[0]) ? $this->params[0] : null;
        $transaction_id = isset($this->params[1]) ? $this->params[1] : null;
        
        if(!$username || !$transaction_id) {
            redirect('');
        }

        /* Get user by username */
        $store_user = Database::simple_get('*', 'users', ['name' => $username, 'status' => 1]);
        
        if(!$store_user) {
            $_SESSION['error'][] = 'Toko tidak ditemukan.';
            redirect('');
        }

        /* Get order */
        $order_result = Database::$database->query("
            SELECT o.*, p.title as product_title, p.price as product_price, p.url as product_url
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE o.transaction_id = '{$transaction_id}' AND p.user_id = {$store_user->user_id}
        ");
        
        if($order_result->num_rows == 0) {
            $_SESSION['error'][] = 'Transaksi tidak ditemukan.';
            redirect('store/' . $username);
        }

        $order = $order_result->fetch_object();

        /* Prepare the View */
        $data = [
            'store_user' => $store_user,
            'order' => $order
        ];

        $view = new \Altum\Views\View('store/success', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}