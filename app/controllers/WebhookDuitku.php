<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\Order;
use Altum\Models\Product;

class WebhookDuitku extends Controller {

    public function index() {
        /* Get the payload */
        $payload = @file_get_contents('php://input');
        $data = json_decode($payload, true);

        /* Log the webhook for debugging */
        if(DEBUG) {
            error_log('Duitku Webhook: ' . $payload);
        }

        /* Verify the webhook signature */
        $merchant_code = $this->settings->duitku->merchant_code ?? '';
        $api_key = $this->settings->duitku->api_key ?? '';
        
        if(!$merchant_code || !$api_key) {
            http_response_code(400);
            die('Duitku configuration not found');
        }

        /* Check required fields */
        if(!isset($data['merchantOrderId']) || !isset($data['resultCode'])) {
            http_response_code(400);
            die('Invalid webhook data');
        }

        $transaction_id = $data['merchantOrderId'];
        $result_code = $data['resultCode'];
        $amount = $data['amount'] ?? 0;
        $signature = $data['signature'] ?? '';

        /* Verify signature */
        $expected_signature = md5($merchant_code . $transaction_id . $amount . $api_key);
        
        if($signature !== $expected_signature) {
            http_response_code(400);
            die('Invalid signature');
        }

        /* Get the order */
        $order = Database::simple_get('*', 'orders', ['transaction_id' => $transaction_id]);
        
        if(!$order) {
            http_response_code(404);
            die('Order not found');
        }

        /* Get the product */
        $product = Database::simple_get('*', 'products', ['product_id' => $order->product_id]);
        
        if(!$product) {
            http_response_code(404);
            die('Product not found');
        }

        /* Update order status based on result code */
        $new_status = 'pending';
        
        switch($result_code) {
            case '00': // Success
                $new_status = 'completed';
                break;
            case '01': // Pending
                $new_status = 'pending';
                break;
            default: // Failed
                $new_status = 'failed';
                break;
        }

        /* Only process if status is different */
        if($order->status !== $new_status) {
            /* Update order status */
            Database::update('orders', [
                'status' => $new_status,
                'payment_id' => $data['reference'] ?? '',
                'payment_details' => json_encode($data),
                'updated_at' => \Altum\Date::$date
            ], [
                'order_id' => $order->order_id
            ]);

            /* If payment is completed, send email and update product stats */
            if($new_status === 'completed') {
                /* Update product sales count */
                Database::update('products', [
                    'sales' => Database::simple_get('sales', 'products', ['product_id' => $product->product_id]) + 1
                ], [
                    'product_id' => $product->product_id
                ]);

                /* Get store user for seller notification */
                 $store_user = Database::simple_get('*', 'users', ['user_id' => $product->user_id]);
                 
                 if ($store_user) {
                     /* Include email templates */
                     require_once APP_PATH . 'helpers/EmailTemplates.php';
                     
                     /* Send email to customer */
                     $customer_email_template = \Altum\Helpers\EmailTemplates::getPurchaseSuccessTemplate($order, $product, $store_user);
                     send_mail($this->settings, $order->customer_email, $customer_email_template->subject, $customer_email_template->body);
                     
                     /* Send notification to seller */
                     $seller_email_template = \Altum\Helpers\EmailTemplates::getOrderNotificationTemplate($order, $product, $order->customer_email);
                     send_mail($this->settings, $store_user->email, $seller_email_template->subject, $seller_email_template->body);
                 }

                /* Log the successful payment */
                if(DEBUG) {
                    error_log("Payment completed for order: {$transaction_id}");
                }
            }
        }

        http_response_code(200);
        echo 'OK';
        die();
    }

    private function send_purchase_email($order, $product) {
        /* Prepare email content */
        $subject = 'Pembelian Berhasil - ' . $product->title;
        
        $body = "
        <h2>Terima kasih atas pembelian Anda!</h2>
        <p>Halo {$order->customer_name},</p>
        <p>Pembayaran Anda telah berhasil diproses. Berikut detail pembelian:</p>
        
        <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
            <h3>Detail Pesanan</h3>
            <p><strong>ID Transaksi:</strong> {$order->transaction_id}</p>
            <p><strong>Produk:</strong> {$product->title}</p>
            <p><strong>Harga:</strong> Rp " . number_format($order->total_amount, 0, ',', '.') . "</p>
            <p><strong>Tanggal:</strong> " . date('d/m/Y H:i', strtotime($order->datetime)) . "</p>
        </div>
        
        <div style='background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;'>
            <h3>Akses Produk Digital</h3>
            <p>Anda dapat mengakses produk digital yang telah dibeli melalui link berikut:</p>
            <p><a href='{$product->url}' style='background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;'>Akses Produk</a></p>
        </div>
        
        <p>Jika Anda memiliki pertanyaan, silakan hubungi kami.</p>
        <p>Terima kasih!</p>
        ";

        /* Send email */
        try {
            send_mail(
                $this->settings,
                $order->customer_email,
                $subject,
                $body
            );
        } catch(\Exception $e) {
            if(DEBUG) {
                error_log('Email sending failed: ' . $e->getMessage());
            }
        }
    }
}