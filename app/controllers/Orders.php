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
        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and is active */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'status' => 1]);
        if(!$product) {
            redirect('products/catalog');
        }

        /* Check if user/customer already purchased this product */
        $existing_order = null;
        
        if($this->user) {
            /* For logged in users */
            $existing_order = Database::get('*', 'orders', [
                'user_id' => $this->user->user_id, 
                'product_id' => $product_id, 
                'status' => 'completed'
            ]);
        }

        if($existing_order) {
            if($this->user) {
                redirect('orders');
            } else {
                redirect('products/product/' . $product_id);
            }
        }

        if(!empty($_POST)) {
            /* Validate CSRF token */
            if(!\Altum\Csrf::check()) {
                redirect('products/product/' . $product_id);
            }

            $customer_name = null;
            $customer_email = null;
            $customer_phone = null;
            $user_id = null;

            if($this->user) {
                /* For logged in users */
                $user_id = $this->user->user_id;
                $customer_name = $this->user->name;
                $customer_email = $this->user->email;
            } else {
                /* For guest checkout */
                $customer_name = trim($_POST['customer_name'] ?? '');
                $customer_email = trim($_POST['customer_email'] ?? '');
                $customer_phone = trim($_POST['customer_phone'] ?? '');

                /* Basic validation */
                if(empty($customer_name) || strlen($customer_name) < 2 || strlen($customer_name) > 128) {
                    redirect('products/product/' . $product_id);
                }

                if(!filter_var($customer_email, FILTER_VALIDATE_EMAIL) || strlen($customer_email) > 320) {
                    redirect('products/product/' . $product_id);
                }

                if(empty($customer_phone) || strlen($customer_phone) < 10 || strlen($customer_phone) > 20) {
                    redirect('products/product/' . $product_id);
                }

                /* Check if guest customer already purchased this product */
                $guest_order = Database::$database->query("
                    SELECT * FROM `orders` 
                    WHERE `customer_email` = '" . Database::clean_string($customer_email) . "' 
                    AND `product_id` = '" . Database::clean_string($product_id) . "' 
                    AND `status` = 'completed'
                ")->fetch_object();

                if($guest_order) {
                    /* Store email in session to show purchased status */
                    $_SESSION['guest_email'] = $customer_email;
                    redirect('products/product/' . $product_id);
                }
            }

            /* Create order */
            $order_id = 'ORD-' . time() . '-' . rand(1000, 9999);
            $transaction_id = 'TXN-' . time() . '-' . rand(10000, 99999);

            Database::insert('orders', [
                'order_id' => $order_id,
                'transaction_id' => $transaction_id,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'amount' => $product->price,
                'payment_method' => 'midtrans',
                'status' => 'pending',
                'datetime' => \Altum\Date::$date
            ]);

            /* Store guest email in session for future reference */
            if(!$this->user) {
                $_SESSION['guest_email'] = $customer_email;
                $_SESSION['guest_order_id'] = $order_id;
            }

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
        $order_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if order exists */
        $order = null;
        
        if($this->user) {
            /* For logged in users */
            $order = Database::get('*', 'orders', ['order_id' => $order_id, 'user_id' => $this->user->user_id]);
        } else {
            /* For guest users, check by session or order_id */
            if(isset($_SESSION['guest_order_id']) && $_SESSION['guest_order_id'] == $order_id) {
                $order = Database::get('*', 'orders', ['order_id' => $order_id]);
            } else {
                /* Allow guest to access if they know the order_id and it's a guest order */
                $order = Database::get('*', 'orders', ['order_id' => $order_id]);
                if($order && $order->user_id !== null) {
                    /* This is not a guest order */
                    $order = null;
                }
            }
        }
        
        if(!$order) {
            if($this->user) {
                redirect('orders');
            } else {
                redirect('products/catalog');
            }
        }

        /* Get product details */
        $product = Database::get('*', 'products', ['product_id' => $order->product_id]);

        /* If already completed, redirect to success */
        if($order->status == 'completed') {
            redirect('orders/success/' . $order_id);
        }

        /* Initialize Midtrans */
        if(!empty($_POST['pay_now'])) {
            /* Create Midtrans transaction */
            $transaction_details = [
                'order_id' => $order->transaction_id,
                'gross_amount' => (int)$order->amount // IDR amount as integer
            ];
            
            $customer_details = [
                'first_name' => $order->customer_name ?? ($this->user ? $this->user->name : 'Customer'),
                'email' => $order->customer_email ?? ($this->user ? $this->user->email : ''),
                'phone' => $order->customer_phone ?? ($this->user && isset($this->user->phone) ? $this->user->phone : '')
            ];
            
            $item_details = [
                [
                    'id' => $product->product_id,
                    'price' => (int)$order->amount,
                    'quantity' => 1,
                    'name' => $product->name,
                    'category' => 'Digital Product'
                ]
            ];
            
            try {
                $snap_token = \Altum\Helpers\Midtrans::get_snap_token($transaction_details, $customer_details, $item_details);
                
                /* Store snap token in order */
                Database::update('orders', [
                    'payment_details' => json_encode(['snap_token' => $snap_token])
                ], ['order_id' => $order_id]);
                
                /* Prepare payment data for view */
                $data['snap_token'] = $snap_token;
                $data['client_key'] = MIDTRANS_CLIENT_KEY;
                
            } catch(\Exception $e) {
                $error = 'Payment initialization failed: ' . $e->getMessage();
            }
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
        $order_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if order exists */
        $order = null;
        
        if($this->user) {
            /* For logged in users */
            $order = Database::get('*', 'orders', ['order_id' => $order_id, 'user_id' => $this->user->user_id]);
        } else {
            /* For guest users, check by session or order_id */
            if(isset($_SESSION['guest_order_id']) && $_SESSION['guest_order_id'] == $order_id) {
                $order = Database::get('*', 'orders', ['order_id' => $order_id]);
            } else {
                /* Allow guest to access if they know the order_id and it's a guest order */
                $order = Database::get('*', 'orders', ['order_id' => $order_id]);
                if($order && $order->user_id !== null) {
                    /* This is not a guest order */
                    $order = null;
                }
            }
        }
        
        if(!$order || $order->status != 'completed') {
            if($this->user) {
                redirect('orders');
            } else {
                redirect('products/catalog');
            }
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
        $customer_name = '';
        $customer_email = '';
        
        if($order->user_id) {
            /* Get user details for registered users */
            $user = Database::get(['name', 'email'], 'users', ['user_id' => $order->user_id]);
            if($user && $user->email) {
                $customer_name = $user->name;
                $customer_email = $user->email;
            }
        } else {
            /* Use guest customer details */
            $customer_name = $order->customer_name;
            $customer_email = $order->customer_email;
        }
        
        if($customer_email) {
            $subject = "Konfirmasi Pembelian - " . $product->name;
            
            $message = "
            <h2>Terima kasih atas pembelian Anda!</h2>
            <p>Halo {$customer_name},</p>
            <p>Pesanan Anda telah berhasil diselesaikan. Berikut adalah detail pembelian Anda:</p>
            
            <h3>Detail Pesanan:</h3>
            <ul>
                <li><strong>ID Pesanan:</strong> {$order->order_id}</li>
                <li><strong>Produk:</strong> {$product->name}</li>
                <li><strong>Total:</strong> " . format_idr($order->amount) . "</li>
                <li><strong>Tanggal Pembelian:</strong> {$order->completed_datetime}</li>
            </ul>
            
            <h3>Akses Produk:</h3>
            <p>{$product->description}</p>
            " . ($product->digital_link ? "<p><strong>Link Akses:</strong> <a href='{$product->digital_link}' target='_blank'>Klik disini untuk mengakses produk Anda</a></p>" : "") . "
            
            <p>Terima kasih atas pembelian Anda!</p>
            <p>Tim KiblatBio</p>
            ";

            /* Send email */
            $this->send_email($customer_email, $subject, $message);
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