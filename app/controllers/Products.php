<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Product;

class Products extends Controller {

    public function index() {
        /* Handle different routes */
        $route = \Altum\Routing\Router::$controller_key;
        
        if($route == 'catalog') {
            $this->catalog();
            return;
        }
        
        if($route == 'product') {
            $this->view();
            return;
        }

        if($route == 'user-catalog') {
            $this->user_catalog();
            return;
        }
        
        /* Default products management */
        Authentication::guard();

        /* Get the products */
        $products_result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = {$this->user->user_id} 
            ORDER BY `datetime` DESC
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '{}');
            $products[] = $row;
        }

        /* Prepare the view */
        $data = [
            'products' => $products
        ];

        $view = new \Altum\Views\View('products/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function create() {
        Authentication::guard();

        if(!empty($_POST)) {
            $name = Database::clean_string($_POST['name']);
            $description = Database::clean_string($_POST['description']);
            $price = clean_idr_input($_POST['price']);
            $digital_link = Database::clean_string($_POST['digital_link']);
            $status = isset($_POST['status']) ? 1 : 0;

            /* Basic validation */
            if(empty($name) || empty($description) || $price < 1000) {
                $error = 'Please fill all required fields correctly. Minimum price is Rp 1.000';
            } else {
                /* Handle image upload */
                $image = null;
                if(!empty($_FILES['image']['name'])) {
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    
                    if(in_array($file_extension, $allowed_extensions)) {
                        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/products/';
                        if(!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        
                        $image_name = md5(time() . rand()) . '.' . $file_extension;
                        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                            $image = $image_name;
                        }
                    }
                }

                /* Insert to database */
                $product_id = md5($this->user->user_id . $name . time());

                Database::insert('products', [
                    'product_id' => $product_id,
                    'user_id' => $this->user->user_id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'image' => $image,
                    'digital_link' => $digital_link,
                    'status' => $status,
                    'datetime' => \Altum\Date::$date
                ]);

                redirect('products');
            }
        }

        $view = new \Altum\Views\View('products/create', (array) $this);
        $this->add_view_content('content', $view->run([]));
    }

    public function edit() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Get the product */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'user_id' => $this->user->user_id]);
        if(!$product) {
            redirect('products');
        }

        if(!empty($_POST)) {
            $name = Database::clean_string($_POST['name']);
            $description = Database::clean_string($_POST['description']);
            $price = clean_idr_input($_POST['price']);
            $digital_link = Database::clean_string($_POST['digital_link']);
            $status = isset($_POST['status']) ? 1 : 0;

            /* Handle image upload */
            $image = $product->image;
            if(!empty($_FILES['image']['name'])) {
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                
                if(in_array($file_extension, $allowed_extensions)) {
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/products/';
                    
                    /* Delete old image */
                    if($image && file_exists($upload_dir . $image)) {
                        unlink($upload_dir . $image);
                    }
                    
                    $image_name = md5(time() . rand()) . '.' . $file_extension;
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                        $image = $image_name;
                    }
                }
            }

            /* Update database */
            Database::update('products', [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'digital_link' => $digital_link,
                'status' => $status
            ], ['product_id' => $product_id, 'user_id' => $this->user->user_id]);

            redirect('products');
        }

        $data = ['product' => $product];
        $view = new \Altum\Views\View('products/edit', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {
        Authentication::guard();

        if(empty($_POST)) {
            redirect('products');
        }

        $product_id = (string) $_POST['product_id'];

        /* Get the product to check if it belongs to the user */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'user_id' => $this->user->user_id]);
        if(!$product) {
            redirect('products');
        }

        /* Delete the image file if exists */
        if($product->image) {
            $image_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/products/' . $product->image;
            if(file_exists($image_path)) {
                unlink($image_path);
            }
        }

        /* Delete the product */
        Database::$database->query("DELETE FROM `products` WHERE `product_id` = '" . Database::clean_string($product_id) . "' AND `user_id` = {$this->user->user_id}");

        redirect('products');
    }

    public function view() {
        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and is active */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'status' => 1]);
        if(!$product) {
            redirect('');
        }

        /* Get product owner info */
        $product_owner = Database::get(['name', 'email'], 'users', ['user_id' => $product->user_id]);

        /* Increment views */
        Database::$database->query("UPDATE `products` SET `views` = `views` + 1 WHERE `product_id` = '" . Database::clean_string($product_id) . "'");

        /* Check if user has already purchased this product */
        $has_purchased = false;
        
        if($this->user) {
            /* For logged in users, check by user_id */
            $order = Database::get('*', 'orders', ['user_id' => $this->user->user_id, 'product_id' => $product_id, 'status' => 'completed']);
            $has_purchased = (bool) $order;
        } else {
            /* For guest users, check if they provided email and already purchased */
            if(isset($_SESSION['guest_email'])) {
                $order = Database::$database->query("
                    SELECT * FROM `orders` 
                    WHERE `customer_email` = '" . Database::clean_string($_SESSION['guest_email']) . "' 
                    AND `product_id` = '" . Database::clean_string($product_id) . "' 
                    AND `status` = 'completed'
                ")->fetch_object();
                $has_purchased = (bool) $order;
            }
        }

        /* Prepare the view */
        $data = [
            'product' => $product,
            'product_owner' => $product_owner,
            'has_purchased' => $has_purchased
        ];

        $view = new \Altum\Views\View('products/view', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function catalog() {
        /* Get the products */
        $products_result = Database::$database->query("
            SELECT p.*, u.name as seller_name 
            FROM `products` p
            LEFT JOIN `users` u ON p.user_id = u.user_id
            WHERE p.status = 1 
            ORDER BY p.datetime DESC
            LIMIT 20
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $products[] = $row;
        }

        /* Prepare the view */
        $data = [
            'products' => $products
        ];

        $view = new \Altum\Views\View('products/catalog', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function user_catalog() {
        $user_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$user_id) {
            redirect('');
        }

        /* Get user info */
        $user = Database::get(['user_id', 'name', 'email'], 'users', ['user_id' => $user_id]);
        if(!$user) {
            redirect('');
        }

        /* Get user's products */
        $products_result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = {$user_id} AND `status` = 1 
            ORDER BY `datetime` DESC
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $products[] = $row;
        }

        /* Prepare the view */
        $data = [
            'products' => $products,
            'user' => $user
        ];

        $view = new \Altum\Views\View('products/user_catalog', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function sales() {
        Authentication::guard();

        /* Get seller's products */
        $products_result = Database::$database->query("
            SELECT product_id, name FROM `products` 
            WHERE `user_id` = {$this->user->user_id} 
            ORDER BY `name` ASC
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $products[] = $row;
        }

        /* Get sales analytics for seller */
        $sales_stats = [];
        $periods = ['today', 'week', 'month', 'all'];
        
        foreach($periods as $period) {
            $where_conditions = ["o.status = 'completed'", "p.user_id = {$this->user->user_id}"];
            
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
            }
            
            $where_clause = implode(' AND ', $where_conditions);
            
            $result = Database::$database->query("
                SELECT 
                    COUNT(*) as total_orders,
                    COALESCE(SUM(o.amount), 0) as total_revenue,
                    COALESCE(AVG(o.amount), 0) as average_order_value
                FROM `orders` o
                LEFT JOIN `products` p ON o.product_id = p.product_id
                WHERE {$where_clause}
            ");
            
            $sales_stats[$period] = $result->fetch_object();
        }

        /* Get recent orders for seller's products */
        $orders_result = Database::$database->query("
            SELECT 
                o.*, 
                p.name as product_name,
                p.image as product_image,
                CASE 
                    WHEN o.user_id IS NOT NULL THEN u.name 
                    ELSE o.customer_name 
                END as customer_name,
                CASE 
                    WHEN o.user_id IS NOT NULL THEN u.email 
                    ELSE o.customer_email 
                END as customer_email
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            LEFT JOIN `users` u ON o.user_id = u.user_id
            WHERE p.user_id = {$this->user->user_id}
            ORDER BY o.datetime DESC
            LIMIT 20
        ");

        $orders = [];
        while($row = $orders_result->fetch_object()) {
            $orders[] = $row;
        }

        /* Prepare the view */
        $data = [
            'products' => $products,
            'sales_stats' => $sales_stats,
            'orders' => $orders
        ];

        $view = new \Altum\Views\View('products/sales', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}