<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\DigitalOrder as DigitalOrderModel;
use Altum\Models\DigitalProduct as DigitalProductModel;

class DigitalOrderController extends Controller {

    public function index() {
        Authentication::guard();

        // Get all orders for the current user (seller)
        $orders = (new DigitalOrderModel())->get_orders_by_user($this->user->user_id);

        /* Prepare the View */
        $data = [
            'orders' => $orders
        ];

        $view = new \Altum\Views\View('digital-order/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function view() {
        Authentication::guard();

        $order_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        // Check if order exists and belongs to current user
        $order = (new DigitalOrderModel())->get_order($order_id);
        if(!$order || $order->user_id != $this->user->user_id) {
            redirect('digital-order');
        }

        // Get product details
        $product = (new DigitalProductModel())->get_product($order->product_id);

        /* Prepare the View */
        $data = [
            'order' => $order,
            'product' => $product
        ];

        $view = new \Altum\Views\View('digital-order/view', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function update_status() {
        Authentication::guard();

        $order_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        // Check if order exists and belongs to current user
        $order = (new DigitalOrderModel())->get_order($order_id);
        if(!$order || $order->user_id != $this->user->user_id) {
            redirect('digital-order');
        }

        if(!empty($_POST)) {
            // Validate CSRF token
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {
                // Update order status
                $data = [
                    'payment_status' => Database::clean_string($_POST['payment_status'])
                ];

                (new DigitalOrderModel())->update_order($order_id, $data);

                $_SESSION['success'][] = 'Order status updated successfully';
                redirect('digital-order');
            }
        }

        /* Prepare the View */
        $data = [
            'order' => $order
        ];

        $view = new \Altum\Views\View('digital-order/update-status', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    // Public method for checkout without authentication
    public function checkout() {
        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        // Get product details
        $product = (new DigitalProductModel())->get_product($product_id);
        if(!$product || $product->status != 'active') {
            redirect('notfound');
        }

        // Get seller details
        $seller = Database::get(['name', 'email'], 'users', ['user_id' => $product->user_id]);

        if(!empty($_POST)) {
            // Validate required fields
            if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['whatsapp'])) {
                $_SESSION['error'][] = $this->language->global->error_message->empty_fields;
            }

            // Validate email format
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = 'Please enter a valid email address';
            }

            if(empty($_SESSION['error'])) {
                // Generate access token
                $access_token = md5(uniqid(rand(), true));
                
                // Prepare data for insertion
                $data = [
                    'product_id' => $product->product_id,
                    'user_id' => $product->user_id, // Seller ID
                    'customer_name' => Database::clean_string($_POST['name']),
                    'customer_email' => Database::clean_string($_POST['email']),
                    'customer_whatsapp' => Database::clean_string($_POST['whatsapp']),
                    'price' => $product->price,
                    'payment_status' => 'pending',
                    'access_token' => $access_token,
                    'date' => \Altum\Date::$date
                ];

                // Insert order
                $order_id = Database::$database->insert_id;
                (new DigitalOrderModel())->create_order($data);
                $order_id = Database::$database->insert_id;

                // Redirect to payment page
                redirect('digital-order/payment/' . $order_id);
            }
        }

        /* Prepare the View */
        $data = [
            'product' => $product,
            'seller' => $seller
        ];

        $view = new \Altum\Views\View('digital-order/checkout', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    // Public method for payment processing
    public function payment() {
        $order_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        // Get order details
        $order = (new DigitalOrderModel())->get_order($order_id);
        if(!$order || $order->payment_status != 'pending') {
            redirect('notfound');
        }

        // Get product details
        $product = (new DigitalProductModel())->get_product($order->product_id);

        /* Prepare the View */
        $data = [
            'order' => $order,
            'product' => $product
        ];

        $view = new \Altum\Views\View('digital-order/payment', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    // Webhook for Midtrans payment notification
    public function midtrans_webhook() {
        // Get the raw POST data
        $json = file_get_contents('php://input');
        $notification = json_decode($json);

        // For testing purposes, we'll also support GET parameters
        if (isset($_GET['order_id']) && isset($_GET['status'])) {
            $order_id = (int)$_GET['order_id'];
            $transaction_status = $_GET['status'];
        } else {
            // In a real implementation, you would verify the notification signature
            // and extract the order ID and status from the Midtrans notification
            // For now, we'll just return a simple response
            http_response_code(200);
            echo 'OK';
            return;
        }

        // Get order details
        $order = (new DigitalOrderModel())->get_order($order_id);
        if (!$order) {
            http_response_code(404);
            echo 'Order not found';
            return;
        }

        // Update order status based on transaction status
        $status_map = [
            'paid' => 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'failed',
            'cancel' => 'failed',
            'refund' => 'refunded',
            'chargeback' => 'refunded',
            'partial_refund' => 'refunded',
            'partial_chargeback' => 'refunded'
        ];

        $new_status = isset($status_map[$transaction_status]) ? $status_map[$transaction_status] : 'pending';

        // Update order status
        (new DigitalOrderModel())->update_order($order_id, [
            'payment_status' => $new_status,
            'payment_method' => 'midtrans',
            'payment_reference' => 'TEST_' . time() // In real implementation, use actual transaction ID
        ]);

        // If payment is successful, send access information to customer
        if ($new_status == 'paid') {
            // Get product details
            $product = (new DigitalProductModel())->get_product($order->product_id);
            
            // Send email with access information
            $this->send_access_email($order, $product);
        }

        // Return success response
        http_response_code(200);
        echo 'OK';
    }

    // Send email with product access information
    private function send_access_email($order, $product) {
        // Email subject
        $subject = 'Your Product Access - ' . $product->name;

        // Email body
        $body = '<h2>Thank you for your purchase!</h2>';
        $body .= '<p>Dear ' . htmlspecialchars($order->customer_name) . ',</p>';
        $body .= '<p>Thank you for purchasing <strong>' . htmlspecialchars($product->name) . '</strong>.</p>';
        $body .= '<p>You can access your product using the link below:</p>';
        $body .= '<p><a href="' . htmlspecialchars($product->access_url) . '" target="_blank">Access Product</a></p>';
        $body .= '<p>If you have any questions, please don\'t hesitate to contact us.</p>';
        $body .= '<p>Best regards,<br>The Team</p>';

        // Send email (in a real implementation, you would use the application\'s email sending function)
        // For now, we\'ll just simulate the email sending
        error_log('Sending email to: ' . $order->customer_email);
        error_log('Subject: ' . $subject);
        error_log('Body: ' . $body);
        
        // In a real implementation, you would use something like:
        // send_mail($this->settings, $order->customer_email, $subject, $body);
    }
}