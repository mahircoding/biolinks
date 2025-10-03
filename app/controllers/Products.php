<?php

namespace Altum\Controllers;

use Altum\Controllers\Controller;
use Altum\Models\Product;
use Altum\Models\Order;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Title;

class Products extends Controller {

    public function index() {
        
        Authentication::guard();

        /* Get the user's products */
        $product = new Product();
        $products = $product->get_products_by_user_id($this->user->user_id, 1, 10);
        
        /* Get user statistics */
        $user_stats = [
            'total_products' => $product->get_user_products_count($this->user->user_id),
            'total_sales' => $product->get_user_sales_count($this->user->user_id),
            'total_revenue' => $product->get_user_total_revenue($this->user->user_id)
        ];

        /* Get order statistics */
        $order = new Order();
        $order_stats = $order->get_order_statistics($this->user->user_id);

        /* Get top products */
        $top_products = $order->get_top_products($this->user->user_id, 5);

        /* Delete any old products */
        if(isset($_POST['delete']) && !empty($_POST['selected_products'])) {
            
            foreach($_POST['selected_products'] as $product_id) {
                $product->delete($product_id, $this->user->user_id);
            }

            $_SESSION['success'][] = $this->language->global->success_message->delete;
            redirect('products');
        }

        /* Prepare the pagination */
        $total_pages = ceil($user_stats['total_products'] / 10);
        $max_pages = 10;
        $current_page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $current_page = $current_page > $total_pages ? $total_pages : $current_page;
        $previous_page = $current_page - 1;
        $next_page = $current_page + 1;
        $start_range = $current_page - floor($max_pages / 2);
        $start_range = $start_range <= 0 ? 1 : $start_range;
        $end_range = $start_range + $max_pages - 1;
        $end_range = $end_range > $total_pages ? $total_pages : $end_range;
        $start_range = $end_range > $total_pages ? $end_range - $max_pages + 1 : $start_range;
        $start_range = $start_range <= 0 ? 1 : $start_range;

        /* Main View */
        $data = [
            'products' => $products,
            'user_stats' => $user_stats,
            'order_stats' => $order_stats,
            'top_products' => $top_products,
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'start_range' => $start_range,
            'end_range' => $end_range,
        ];

        $view = new \Altum\Views\View('products/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function create() {
        
        /* Check for any errors */
        if(!empty($_SESSION['error'])) {
            $errors = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        /* Default values */
        $data = [
            'name' => '',
            'description' => '',
            'price' => '',
            'digital_link' => '',
            'status' => 1
        ];

        /* Process POST request */
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            /* Filter the data */
            $_POST['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['description'] = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $_POST['price'] = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['digital_link'] = filter_var($_POST['digital_link'], FILTER_SANITIZE_URL);
            $_POST['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

            /* Check for errors */
            if(empty($_POST['name'])) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.name_required');
            }

            if(empty($_POST['description'])) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.description_required');
            }

            if(empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.price_required');
            }

            if(empty($_POST['digital_link'])) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.digital_link_required');
            }

            if(!filter_var($_POST['digital_link'], FILTER_VALIDATE_URL)) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.digital_link_invalid');
            }

            if(empty($_SESSION['error'])) {
                
                /* Create the product */
                $product = new Product();
                $product_id = $product->create([
                    'user_id' => Authentication::$user_id,
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price'],
                    'digital_link' => $_POST['digital_link'],
                    'status' => $_POST['status'],
                    'settings' => json_encode([
                        'allow_multiple_purchases' => isset($_POST['allow_multiple_purchases']) ? 1 : 0,
                        'require_login' => isset($_POST['require_login']) ? 1 : 0,
                    ])
                ]);

                /* Log the activity */
                $product->log_activity($product_id, Authentication::$user_id, 'create');

                /* Success message */
                $_SESSION['success'][] = \Altum\Language::get('products', 'success_message.create');

                /* Redirect */
                redirect('products');
            } else {
                $data = $_POST;
            }
        }

        /* Main View */
        $data = [
            'errors' => $errors ?? null,
            'data' => $data
        ];

        $view = new \Altum\Views\View('products/create', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function view() {
        
        /* Get the product ID */
        $product_id = $this->params[0] ?? null;

        if(!$product_id) {
            redirect('products/catalog');
        }

        /* Get the product */
        $product = new Product();
        $product_data = $product->get_product_by_id($product_id);

        if(!$product_data) {
            redirect('products/catalog');
        }

        /* Check if product is active */
        if($product_data['status'] != 1) {
            redirect('products/catalog');
        }

        /* Increment views */
        $product->increment_views($product_id);

        /* Log the activity */
        $product->log_activity($product_id, $this->user->user_id ?? null, 'view');

        /* Main View */
        $data = [
            'product' => $product_data
        ];

        $view = new \Altum\Views\View('products/view', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function catalog() {
        
        /* Get search parameters */
        $search = isset($_GET['search']) ? filter_var($_GET['search'], FILTER_SANITIZE_STRING) : null;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        /* Get the products */
        $product = new Product();
        $products = $product->get_active_products($page, 12, $search);
        $total_products = $product->get_total_active_products($search);

        /* Prepare the pagination */
        $max_items = 12;
        $total_pages = ceil($total_products / $max_items);
        $max_pages = 10;
        $current_page = $page;
        $previous_page = $current_page - 1;
        $next_page = $current_page + 1;
        $start_range = $current_page - floor($max_pages / 2);
        $start_range = $start_range <= 0 ? 1 : $start_range;
        $end_range = $start_range + $max_pages - 1;
        $end_range = $end_range > $total_pages ? $total_pages : $end_range;
        $start_range = $end_range > $total_pages ? $end_range - $max_pages + 1 : $start_range;
        $start_range = $start_range <= 0 ? 1 : $start_range;

        /* Main View */
        $data = [
            'products' => $products,
            'search' => $search,
            'total_products' => $total_products,
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'start_range' => $start_range,
            'end_range' => $end_range,
        ];

        $view = new \Altum\Views\View('products/catalog', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function edit() {
        
        /* Get the product ID */
        $product_id = $this->params[0] ?? null;

        if(!$product_id) {
            redirect('products');
        }

        /* Get the product */
        $product = new Product();
        $product_data = $product->get_product_by_id_and_user_id($product_id, Authentication::$user_id);

        if(!$product_data) {
            redirect('products');
        }

        /* Check for any errors */
        if(!empty($_SESSION['error'])) {
            $errors = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        /* Default values */
        $data = $product_data;

        /* Process POST request */
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            /* Filter the data */
            $_POST['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['description'] = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $_POST['price'] = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $_POST['digital_link'] = filter_var($_POST['digital_link'], FILTER_SANITIZE_URL);
            $_POST['status'] = isset($_POST['status']) ? (int)$_POST['status'] : 0;

            /* Check for errors */
            if(empty($_POST['name'])) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.name_required');
            }

            if(empty($_POST['description'])) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.description_required');
            }

            if(empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.price_required');
            }

            if(empty($_POST['digital_link'])) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.digital_link_required');
            }

            if(!filter_var($_POST['digital_link'], FILTER_VALIDATE_URL)) {
                $_SESSION['error'][] = \Altum\Language::get('products', 'error_message.digital_link_invalid');
            }

            if(empty($_SESSION['error'])) {
                
                /* Update the product */
                $product->update($product_id, [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price'],
                    'digital_link' => $_POST['digital_link'],
                    'status' => $_POST['status'],
                    'settings' => json_encode([
                        'allow_multiple_purchases' => isset($_POST['allow_multiple_purchases']) ? 1 : 0,
                        'require_login' => isset($_POST['require_login']) ? 1 : 0,
                    ])
                ]);

                /* Log the activity */
                $product->log_activity($product_id, Authentication::$user_id, 'update');

                /* Success message */
                $_SESSION['success'][] = \Altum\Language::get('products', 'success_message.update');

                /* Redirect */
                redirect('products');
            } else {
                $data = $_POST;
            }
        }

        /* Main View */
        $data = [
            'errors' => $errors ?? null,
            'data' => $data
        ];

        $view = new \Altum\Views\View('products/edit', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {
        
        /* Get the product ID */
        $product_id = $this->params[0] ?? null;

        if(!$product_id) {
            redirect('products');
        }

        /* Get the product */
        $product = new Product();
        $product_data = $product->get_product_by_id_and_user_id($product_id, Authentication::$user_id);

        if(!$product_data) {
            redirect('products');
        }

        /* Delete the product */
        $product->delete($product_id, Authentication::$user_id);

        /* Log the activity */
        $product->log_activity($product_id, Authentication::$user_id, 'delete');

        /* Success message */
        $_SESSION['success'][] = \Altum\Language::get('products', 'success_message.delete');

        /* Redirect */
        redirect('products');

    }

}