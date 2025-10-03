<?php

namespace Altum\Controllers;

use Altum\Controllers\Controller;
use Altum\Models\Product;
use Altum\Models\Order;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Title;

class Orders extends Controller {

    public function index() {
        
        Authentication::guard();

        /* Get the user's orders */
        $order = new Order();
        $orders = $order->get_orders_by_user_id(Authentication::$user_id, 1, 10);
        
        /* Get user statistics */
        $order_stats = $order->get_order_statistics(Authentication::$user_id);

        /* Get pending orders count */
        $pending_orders_count = $order->get_pending_orders_count(Authentication::$user_id);

        /* Delete any old orders */
        if(isset($_POST['delete']) && !empty($_POST['selected_orders'])) {
            
            foreach($_POST['selected_orders'] as $order_id) {
                $order->delete($order_id, Authentication::$user_id);
            }

            $_SESSION['success'][] = \Altum\Language::get('global', 'success_message.delete');
            redirect('orders');
        }

        /* Prepare the pagination */
        $total_pages = ceil($order_stats['total_orders'] / 10);
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
            'orders' => $orders,
            'order_stats' => $order_stats,
            'pending_orders_count' => $pending_orders_count,
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'start_range' => $start_range,
            'end_range' => $end_range,
        ];

        $view = new \Altum\Views\View('orders/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function create() {
        
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

        /* Check if user already purchased this product */
        $order = new Order();
        $already_purchased = false;
        
        if(Authentication::$user_id) {
            $already_purchased = $order->check_existing_order($product_id, Authentication::$user_id);
        } else {
            /* For guest orders, check if email exists in session */
            if(isset($_SESSION['guest_order_email'])) {
                $already_purchased = $order->check_existing_order($product_id, null, $_SESSION['guest_order_email']);
            }
        }

        if($already_purchased) {
            $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.already_purchased');
            redirect('products/view/' . $product_id);
        }

        /* Process POST request */
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            /* Filter the data */
            $_POST['customer_name'] = filter_var($_POST['customer_name'], FILTER_SANITIZE_STRING);
            $_POST['customer_email'] = filter_var($_POST['customer_email'], FILTER_SANITIZE_EMAIL);
            $_POST['customer_phone'] = filter_var($_POST['customer_phone'], FILTER_SANITIZE_STRING);
            $_POST['payment_method'] = filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING);

            /* Check for errors */
            if(empty($_POST['customer_name'])) {
                $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.customer_name_required');
            }

            if(empty($_POST['customer_email']) || !filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.customer_email_required');
            }

            if(empty($_POST['customer_phone'])) {
                $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.customer_phone_required');
            }

            if(empty($_POST['payment_method'])) {
                $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.payment_method_required');
            }

            if(empty($_SESSION['error'])) {
                
                /* Create the order */
                $order_data = $order->create([
                    'user_id' => Authentication::$user_id,
                    'product_id' => $product_id,
                    'amount' => $product_data['price'],
                    'customer_name' => $_POST['customer_name'],
                    'customer_email' => $_POST['customer_email'],
                    'customer_phone' => $_POST['customer_phone'],
                    'payment_method' => $_POST['payment_method'],
                    'status' => 'pending',
                    'settings' => json_encode([
                        'product_name' => $product_data['name'],
                        'product_description' => $product_data['description'],
                        'digital_link' => $product_data['digital_link'],
                    ])
                ]);

                /* Store guest email in session */
                if(!Authentication::$user_id) {
                    $_SESSION['guest_order_email'] = $_POST['customer_email'];
                }

                /* Log the activity */
                $product->log_activity($product_id, Authentication::$user_id, 'purchase', json_encode(['order_id' => $order_data['order_id']]));

                /* Redirect to payment page */
                redirect('orders/payment/' . $order_data['order_id']);
            }
        }

        /* Main View */
        $data = [
            'product' => $product_data,
            'already_purchased' => $already_purchased,
            'errors' => $_SESSION['error'] ?? null
        ];

        if(isset($_SESSION['error'])) {
            unset($_SESSION['error']);
        }

        $view = new \Altum\Views\View('orders/create', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function payment() {
        
        /* Get the order ID */
        $order_id = $this->params[0] ?? null;

        if(!$order_id) {
            redirect('products/catalog');
        }

        /* Get the order */
        $order = new Order();
        $order_data = $order->get_order_by_id($order_id);

        if(!$order_data) {
            redirect('products/catalog');
        }

        /* Check if order belongs to user or is guest order with matching email */
        if(Authentication::$user_id) {
            if($order_data['user_id'] != Authentication::$user_id) {
                redirect('products/catalog');
            }
        } else {
            if($order_data['user_id'] !== null) {
                redirect('products/catalog');
            }
            if(!isset($_SESSION['guest_order_email']) || $_SESSION['guest_order_email'] != $order_data['customer_email']) {
                redirect('products/catalog');
            }
        }

        /* Check if order is already completed */
        if($order_data['status'] == 'completed') {
            redirect('orders/success/' . $order_id);
        }

        /* Get product details */
        $product = new Product();
        $product_data = $product->get_product_by_id($order_data['product_id']);

        if(!$product_data) {
            redirect('products/catalog');
        }

        /* Process payment based on payment method */
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $payment_method = filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING);
            
            switch($payment_method) {
                case 'duitku':
                    /* Process Duitku payment */
                    $this->process_duitku_payment($order_data, $product_data);
                    break;
                    
                case 'midtrans':
                    /* Process Midtrans payment */
                    $this->process_midtrans_payment($order_data, $product_data);
                    break;
                    
                default:
                    $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.invalid_payment_method');
                    break;
            }
        }

        /* Main View */
        $data = [
            'order' => $order_data,
            'product' => $product_data,
            'available_payment_methods' => ['duitku', 'midtrans']
        ];

        $view = new \Altum\Views\View('orders/payment', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function success() {
        
        /* Get the order ID */
        $order_id = $this->params[0] ?? null;

        if(!$order_id) {
            redirect('products/catalog');
        }

        /* Get the order */
        $order = new Order();
        $order_data = $order->get_order_by_id($order_id);

        if(!$order_data) {
            redirect('products/catalog');
        }

        /* Check if order belongs to user or is guest order with matching email */
        if(Authentication::$user_id) {
            if($order_data['user_id'] != Authentication::$user_id) {
                redirect('products/catalog');
            }
        } else {
            if($order_data['user_id'] !== null) {
                redirect('products/catalog');
            }
            if(!isset($_SESSION['guest_order_email']) || $_SESSION['guest_order_email'] != $order_data['customer_email']) {
                redirect('products/catalog');
            }
        }

        /* Check if order is completed */
        if($order_data['status'] != 'completed') {
            redirect('orders/payment/' . $order_id);
        }

        /* Get product details */
        $product = new Product();
        $product_data = $product->get_product_by_id($order_data['product_id']);

        if(!$product_data) {
            redirect('products/catalog');
        }

        /* Clear guest order email from session */
        if(!Authentication::$user_id) {
            unset($_SESSION['guest_order_email']);
        }

        /* Main View */
        $data = [
            'order' => $order_data,
            'product' => $product_data
        ];

        $view = new \Altum\Views\View('orders/success', (array) $this);
        $this->add_view_content('content', $view->run($data));

    }

    public function webhook() {
        
        /* Handle payment webhooks */
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if(!$data) {
            http_response_code(400);
            exit;
        }

        /* Get the order */
        $order = new Order();
        $order_data = $order->get_order_by_transaction_id($data['transaction_id'] ?? '');

        if(!$order_data) {
            http_response_code(404);
            exit;
        }

        /* Update order status based on payment status */
        $status = $data['status'] ?? 'failed';
        
        if($status == 'success' || $status == 'completed') {
            $order->update_status($order_data['order_id'], 'completed', json_encode($data));
            
            /* Send email notification */
            $this->send_order_confirmation_email($order_data);
            
            /* Update product sales count */
            $product = new Product();
            $product->increment_sales($order_data['product_id']);
            
        } else {
            $order->update_status($order_data['order_id'], 'failed', json_encode($data));
        }

        http_response_code(200);
        exit;

    }

    private function process_duitku_payment($order_data, $product_data) {
        
        /* Include Duitku helper */
        require_once APP_PATH . 'helpers/Duitku.php';
        
        /* Create Duitku payment */
        $duitku = new \Altum\Helpers\Duitku();
        
        $payment_data = [
            'merchantOrderId' => $order_data['order_id'],
            'amount' => $order_data['amount'],
            'name' => $order_data['customer_name'],
            'email' => $order_data['customer_email'],
            'phone' => $order_data['customer_phone'],
            'product' => $product_data['name'],
            'callbackUrl' => SITE_URL . '/webhook-duitku'
        ];
        
        $result = $duitku->create_payment($payment_data);
        
        if($result['status'] == 'success') {
            redirect($result['payment_url']);
        } else {
            $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.payment_failed');
            redirect('orders/payment/' . $order_data['order_id']);
        }
        
    }

    private function process_midtrans_payment($order_data, $product_data) {
        
        /* Include Midtrans helper */
        require_once APP_PATH . 'helpers/Midtrans.php';
        
        /* Create Midtrans payment */
        $midtrans = new \Altum\Helpers\Midtrans();
        
        $payment_data = [
            'transaction_details' => [
                'order_id' => $order_data['order_id'],
                'gross_amount' => $order_data['amount'],
            ],
            'customer_details' => [
                'first_name' => $order_data['customer_name'],
                'email' => $order_data['customer_email'],
                'phone' => $order_data['customer_phone'],
            ],
            'item_details' => [
                [
                    'id' => $product_data['product_id'],
                    'price' => $order_data['amount'],
                    'quantity' => 1,
                    'name' => $product_data['name'],
                ]
            ]
        ];
        
        $result = $midtrans->create_transaction($payment_data);
        
        if($result['status'] == 'success') {
            redirect($result['redirect_url']);
        } else {
            $_SESSION['error'][] = \Altum\Language::get('orders', 'error_message.payment_failed');
            redirect('orders/payment/' . $order_data['order_id']);
        }
        
    }

    private function send_order_confirmation_email($order_data) {
        
        /* Get product details */
        $product = new Product();
        $product_data = $product->get_product_by_id($order_data['product_id']);
        
        if(!$product_data) {
            return;
        }
        
        /* Prepare email content */
        $email_data = [
            'customer_name' => $order_data['customer_name'],
            'product_name' => $product_data['name'],
            'product_description' => $product_data['description'],
            'digital_link' => $product_data['digital_link'],
            'order_id' => $order_data['order_id'],
            'amount' => $order_data['amount'],
            'datetime' => $order_data['datetime']
        ];
        
        /* Send email */
        send_mail(
            $order_data['customer_email'],
            \Altum\Language::get('emails', 'order_confirmation.subject'),
            'order_confirmation',
            $email_data
        );
        
    }

}