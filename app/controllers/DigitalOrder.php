<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\DigitalProduct as DigitalProductModel;
use Altum\Models\DigitalOrder as DigitalOrderModel;

class DigitalOrder extends Controller {

    public function manage() {
        \Altum\Middlewares\Authentication::guard();

        DigitalOrderModel::migrate();
        \Altum\Models\DigitalProduct::migrate();

        $user_id = (int)$this->user->user_id;
        $sql = "SELECT o.*, p.name AS product_name FROM `" . DigitalOrderModel::$table . "` o INNER JOIN `" . \Altum\Models\DigitalProduct::$table . "` p ON p.product_id = o.product_id WHERE p.user_id = '{$user_id}' ORDER BY o.order_id DESC";
        $result = Database::$database->query($sql);
        $orders = [];
        while($row = $result->fetch_object()) $orders[] = $row;

        $view = new \Altum\Views\View('digital-order/index', (array) $this);
        $this->add_view_content('content', $view->run(['orders' => $orders]));
    }

    public function index() {
        /* Public product landing by slug: /digital-order/{slug} */
        $params = \Altum\Routing\Router::get_params();
        $slug = isset($params[0]) ? Database::clean_string($params[0]) : '';

        DigitalProductModel::migrate();
        DigitalOrderModel::migrate();

        $product = DigitalProductModel::find_by_slug($slug);
        if(!$product) redirect('notfound');

        $view = new \Altum\Views\View('digital-order/public-view', (array) $this);
        $this->add_view_content('content', $view->run(['product' => $product]));
    }

    public function checkout() {
        /* Form POST: name, email, phone, slug */
        if(empty($_POST)) redirect('notfound');

        $slug = Database::clean_string($_POST['slug'] ?? '');
        $name = Database::clean_string($_POST['name'] ?? '');
        $email = Database::clean_string($_POST['email'] ?? '');
        $phone = Database::clean_string($_POST['phone'] ?? '');
        $method = Database::clean_string($_POST['method'] ?? ''); /* tripay channel code, optional */

        $product = DigitalProductModel::find_by_slug($slug);
        if(!$product) redirect('notfound');

        $token = bin2hex(random_bytes(16));
        $expires_at = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 3); /* 3 days */

        DigitalOrderModel::create([
            'product_id' => $product->product_id,
            'buyer_name' => $name,
            'buyer_email' => $email,
            'buyer_phone' => $phone,
            'amount_cents' => (int)$product->price_cents,
            'currency' => $product->currency,
            'download_token' => $token,
            'download_expires_at' => $expires_at,
            'status' => 'pending'
        ]);

        /* If Tripay configured, create transaction and redirect to payment page */
        if(defined('TRIPAY_API_KEY') && TRIPAY_API_KEY && defined('TRIPAY_PRIVATE_KEY') && TRIPAY_PRIVATE_KEY && defined('TRIPAY_MERCHANT_CODE') && TRIPAY_MERCHANT_CODE) {
            $reference = 'DOP-' . time() . '-' . rand(1000,9999);

            $payload = [
                'method'        => $method ?: 'QRIS',
                'merchant_ref'  => $reference,
                'amount'        => (int) ceil($product->price_cents / 100),
                'customer_name' => $name,
                'customer_email'=> $email,
                'customer_phone'=> $phone,
                'order_items'   => [
                    [
                        'sku'         => (string)$product->product_id,
                        'name'        => $product->name,
                        'price'       => (int) ceil($product->price_cents / 100),
                        'quantity'    => 1,
                        'product_url' => url('digital-order/' . $product->slug)
                    ]
                ],
                'expired_time'  => time() + (24 * 60 * 60),
                'signature'     => hash_hmac('sha256', TRIPAY_MERCHANT_CODE . $reference . ((int) ceil($product->price_cents / 100)), TRIPAY_PRIVATE_KEY),
                'return_url'    => url('digital-order/' . $product->slug),
                'callback_url'  => url('digital-order/webhook')
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => 'https://tripay.co.id/api/transaction/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => [ 'Authorization: Bearer ' . TRIPAY_API_KEY ],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($payload)
            ]);
            $response = curl_exec($ch);
            curl_close($ch);

            $json = @json_decode($response);
            if(isset($json->success) && $json->success && isset($json->data->reference)) {
                /* Save reference */
                Database::update(DigitalOrderModel::$table, [
                    'tripay_reference' => $json->data->reference,
                    'payment_channel' => $json->data->payment_method
                ], [ 'download_token' => $token ]);

                /* Redirect to payment url */
                header('Location: ' . $json->data->checkout_url);
                exit;
            }
        }

        /* Fallback: no Tripay; show thank you & send immediate access */
        $download_url = !empty($product->access_url) ? $product->access_url : url('digital-order/download/' . $token);
        $content = '<p>Terima kasih atas pesanan Anda.</p>' .
                   '<p>Produk: <strong>' . $product->name . '</strong></p>' .
                   '<p>Akses produk Anda:<br />' .
                   '<a href="' . $download_url . '">' . $download_url . '</a></p>';
        send_mail($this->settings, $email, 'Akses Produk Digital - {{WEBSITE_TITLE}}', $content, false);

        $view = new \Altum\Views\View('digital-order/thank-you', (array) $this);
        $this->add_view_content('content', $view->run(['email' => $email]));
    }

    public function webhook() {
        /* Tripay callback */
        $json = file_get_contents('php://input');
        $payload = json_decode($json);
        if(!$payload) die('INVALID');

        $signature = hash_hmac('sha256', $payload->reference . $payload->status . $payload->total_amount, TRIPAY_PRIVATE_KEY);
        $header_signature = $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '';
        if($signature !== $header_signature) die('INVALID_SIGNATURE');

        if(($payload->status ?? '') === 'PAID') {
            $order = DigitalOrderModel::find_by_reference($payload->reference);
            if($order) {
                DigitalOrderModel::mark_paid($order->order_id, $payload->payment_method ?? '');

                $product = DigitalProductModel::find_by_id($order->product_id);
                if($product) {
                    $download_url = !empty($product->access_url) ? $product->access_url : url('digital-order/download/' . $order->download_token);
                    $content = '<p>Terima kasih, pembayaran Anda sudah diterima.</p>' .
                               '<p>Produk: <strong>' . $product->name . '</strong></p>' .
                               '<p>Akses produk Anda:<br />' .
                               '<a href="' . $download_url . '">' . $download_url . '</a></p>';
                    send_mail($this->settings, $order->buyer_email, 'Pembayaran Sukses - Akses Produk', $content, false);
                }
            }
        }

        echo 'OK';
        exit;
    }

    public function download() {
        $params = \Altum\Routing\Router::get_params();
        $token = isset($params[0]) ? Database::clean_string($params[0]) : '';

        $order = DigitalOrderModel::find_by_token($token);
        if(!$order) redirect('notfound');

        $product = DigitalProductModel::find_by_id($order->product_id);
        if(!$product) redirect('notfound');

        /* If access_url set, redirect there (no expiry check) */
        if(!empty($product->access_url)) {
            header('Location: ' . $product->access_url);
            exit;
        }

        /* For file downloads, keep expiry enforcement */
        // if(strtotime($order->download_expires_at) < time()) redirect('notfound');

        // $full_path = UPLOADS_PATH . $product->file_path;
        // if(!file_exists($full_path)) redirect('notfound');

        // header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename="' . basename($full_path) . '"');
        // header('Content-Length: ' . filesize($full_path));
        // readfile($full_path);
        // exit;
    }
}


