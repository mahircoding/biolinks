<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Order;
use Altum\Models\Product;

class Orders extends Controller {

    public function index() {
        Authentication::guard();

        /* Get user orders */
        $orders_result = Database::$database->query("
            SELECT o.*, p.name as product_name, p.description as product_description, p.image as product_image 
            FROM `orders` o
            LEFT JOIN `products` p ON o.product_id = p.product_id
            WHERE o.user_id = {$this->user->user_id} 
            ORDER BY o.datetime DESC
        ");

        $orders = [];
        while($row = $orders_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '{}');
            $orders[] = $row;
        }

        /* Prepare the view */
        $data = [
            'orders' => $orders
        ];

        $view = new \Altum\Views\View('orders/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function create() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and is active */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'status' => 1]);
        if(!$product) {
            redirect('products/catalog');
        }

        /* Check if user already purchased this product */
        $existing_order = Database::get('*', 'orders', [
            'user_id' => $this->user->user_id, 
            'product_id' => $product_id, 
            'status' => 'completed'
        ]);
        
        if($existing_order) {
            redirect('orders');
        }

        if(!empty($_POST)) {
            /* Create order */
            $order_id = 'ORD-' . time() . '-' . rand(1000, 9999);
            $transaction_id = 'TXN-' . time() . '-' . rand(10000, 99999);

            Database::insert('orders', [
                'order_id' => $order_id,
                'transaction_id' => $transaction_id,
                'user_id' => $this->user->user_id,
                'product_id' => $product_id,
                'amount' => $product->price,
                'payment_method' => 'midtrans',
                'status' => 'pending',
                'datetime' => \Altum\Date::$date
            ]);

            /* Redirect to payment */
            redirect('orders/payment/' . $order_id);
        }

        /* Prepare the view */
        $data = [
            'product' => $product
        ];

        $view = new \Altum\Views\View('orders/create', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function payment() {
        Authentication::guard();

        $order_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if order exists and belongs to user */
        $order = Database::get('*', 'orders', ['order_id' => $order_id, 'user_id' => $this->user->user_id]);
        if(!$order) {
            redirect('orders');
        }

        /* Get product details */
        $product = Database::get('*', 'products', ['product_id' => $order->product_id]);

        /* If already completed, redirect to success */
        if($order->status == 'completed') {
            redirect('orders/success/' . $order_id);
        }

        /* Initialize Midtrans */
        if(!empty($_POST['pay_now'])) {
            // TODO: Implement Midtrans payment flow
            // For now, we'll simulate successful payment
            Database::update('orders', [
                'status' => 'completed',
                'completed_datetime' => \Altum\Date::$date
            ], ['order_id' => $order_id]);

            /* Update product sales count */
            Database::$database->query("
                UPDATE `products` 
                SET `sales` = `sales` + 1 
                WHERE `product_id` = '" . Database::clean_string($order->product_id) . "'
            ");

            /* Send email notification */
            $this->send_purchase_email($order, $product);

            redirect('orders/success/' . $order_id);
        }

        /* Prepare the view */
        $data = [
            'order' => $order,
            'product' => $product
        ];

        $view = new \Altum\Views\View('orders/payment', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function success() {
        Authentication::guard();

        $order_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if order exists and belongs to user */
        $order = Database::get('*', 'orders', ['order_id' => $order_id, 'user_id' => $this->user->user_id]);
        if(!$order || $order->status != 'completed') {
            redirect('orders');
        }

        /* Get product details */
        $product = Database::get('*', 'products', ['product_id' => $order->product_id]);

        /* Prepare the view */
        $data = [
            'order' => $order,
            'product' => $product
        ];

        $view = new \Altum\Views\View('orders/success', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function webhook() {
        /* Handle Midtrans webhook */
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if(!empty($data['order_id'])) {
            $order = Database::get('*', 'orders', ['transaction_id' => $data['order_id']]);
            
            if($order) {
                $status = 'pending';
                
                if($data['transaction_status'] == 'capture' || $data['transaction_status'] == 'settlement') {
                    $status = 'completed';
                } elseif($data['transaction_status'] == 'cancel' || $data['transaction_status'] == 'deny' || $data['transaction_status'] == 'expire') {
                    $status = 'failed';
                }

                /* Update order status */
                Database::update('orders', [
                    'status' => $status,
                    'payment_details' => json_encode($data),
                    'completed_datetime' => ($status == 'completed') ? \Altum\Date::$date : null
                ], ['order_id' => $order->order_id]);

                if($status == 'completed') {
                    /* Update product sales count */
                    Database::$database->query("
                        UPDATE `products` 
                        SET `sales` = `sales` + 1 
                        WHERE `product_id` = '" . Database::clean_string($order->product_id) . "'
                    ");

                    /* Get product details */
                    $product = Database::get('*', 'products', ['product_id' => $order->product_id]);
                    
                    /* Send email notification */
                    $this->send_purchase_email($order, $product);
                }
            }
        }

        http_response_code(200);
        echo "OK";
    }

    private function send_purchase_email($order, $product) {
        /* Get user details */
        $user = Database::get(['name', 'email'], 'users', ['user_id' => $order->user_id]);
        
        if($user && $user->email) {
            $subject = "Purchase Confirmation - " . $product->name;
            
            $message = "
            <h2>Thank you for your purchase!</h2>
            <p>Dear {$user->name},</p>
            <p>Your order has been successfully completed. Here are your purchase details:</p>
            
            <h3>Order Details:</h3>
            <ul>
                <li><strong>Order ID:</strong> {$order->order_id}</li>
                <li><strong>Product:</strong> {$product->name}</li>
                <li><strong>Amount:</strong> $" . number_format($order->amount, 2) . "</li>
                <li><strong>Purchase Date:</strong> {$order->completed_datetime}</li>
            </ul>
            
            <h3>Product Access:</h3>
            <p>{$product->description}</p>
            " . ($product->digital_link ? "<p><strong>Access Link:</strong> <a href='{$product->digital_link}'>Click here to access your product</a></p>" : "") . "
            
            <p>Thank you for your purchase!</p>
            ";

            /* Send email */
            $this->send_email($user->email, $subject, $message);
        }
    }

    private function send_email($to, $subject, $message) {
        /* Basic email sending - you can enhance this with proper email service */
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: noreply@' . parse_url(SITE_URL, PHP_URL_HOST) . "\r\n";

        return mail($to, $subject, $message, $headers);
    }
}