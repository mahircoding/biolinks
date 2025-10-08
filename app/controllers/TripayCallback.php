<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\DigitalOrder;
use Altum\Models\DigitalProduct;
use Altum\Helpers\FacebookPixel;

class TripayCallback extends Controller {

    public function index() {
        
        /* Only allow for whitelabel custom domains */
        $current_domain = trim($_SERVER['SERVER_NAME'], '/');
        $is_whitelabel_domain = $this->is_whitelabel_domain($current_domain);
        
        // if (!$is_whitelabel_domain) {
        //     http_response_code(403);
        //     die('Access denied - Only whitelabel custom domains allowed');
        // }

        /* Get the raw POST data */
        $json = file_get_contents('php://input');
        $payload = json_decode($json, true);
        
        if (!$payload) {
            http_response_code(400);
            die('INVALID_PAYLOAD');
        }

        /* Log the callback for debugging */
        error_log('Tripay Callback received: ' . $json);

        /* Validate required fields */
        if (empty($payload['reference']) || empty($payload['status'])) {
            http_response_code(400);
            die('MISSING_REQUIRED_FIELDS');
        }

        /* Get order by reference */
        $order = Database::get('*', 'digital_orders', ['order_id' => $payload['merchant_ref']]);
        
        if (!$order) {
            http_response_code(404);
            die('ORDER_NOT_FOUND');
        }

        /* Get product to find the owner's Tripay settings */
        $product = Database::get('*', 'digital_products', ['product_id' => $order->product_id]);
        
        if (!$product) {
            http_response_code(404);
            die('PRODUCT_NOT_FOUND');
        }

        /* Get product owner's Tripay settings for signature verification */
        $user = Database::get(['tripay_api_key_secret', 'user_id'], 'users', ['user_id' => $product->user_id]);
        
        if (!$user) {
            http_response_code(404);
            die('USER_NOT_FOUND');
        }

        /* Verify signature */
        $expected_signature = hash_hmac('sha256', $json, $user->tripay_api_key_secret
        );
        
        $received_signature = $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '';
        
        if ($received_signature !== $expected_signature) {
            http_response_code(401);
            die('INVALID_SIGNATURE');
        }

        /* Process payment status */
        if ($payload['status'] === 'PAID') {
            /* Update order status to paid */
            Database::update('digital_orders', [
                'status' => 'paid',
                'payment_method' => $payload['payment_method'] ?? 'Tripay',
                'paid_at' => date('Y-m-d H:i:s')
            ], ['order_id' => $order->order_id]);

            /* Track Facebook Pixel Purchase event */
            $pixel_id = $user->facebook_pixel_id ?? null;
            if ($pixel_id) {
                $purchase_tracking = FacebookPixel::track_purchase($order, $product);
                error_log('Facebook Pixel Purchase Event: ' . $purchase_tracking);
            }

            /* Send confirmation email to buyer */
            $download_url = !empty($product->access_url) 
                ? $product->access_url 
                : url('digital-order/download/' . $order->download_token);
                
            $content = '<p>Terima kasih, pembayaran Anda sudah diterima.</p>' .
                       '<p>Produk: <strong>' . $product->name . '</strong></p>' .
                       '<p>Harga: <strong>Rp ' . number_format($order->amount_cents / 100, 0, ',', '.') . '</strong></p>' .
                       '<p>Akses produk Anda:<br />' .
                       '<a href="' . $download_url . '">' . $download_url . '</a></p>';
                       
            send_mail($this->settings, $order->buyer_email, 'Pembayaran Sukses - Akses Produk', $content, false);
            
            /* Log successful payment */
            error_log('Payment successful for order: ' . $order->order_id . ' - Reference: ' . $payload['reference']);
            
        } else if ($payload['status'] === 'EXPIRED') {
            /* Update order status to expired */
            Database::update('digital_orders', [
                'status' => 'expired'
            ], ['order_id' => $order->order_id]);
            
            error_log('Payment expired for order: ' . $order->order_id . ' - Reference: ' . $payload['reference']);
        }

        /* Return success response */
        http_response_code(200);
        echo 'OK';
        exit;
    }

    /**
     * Check if current domain is a whitelabel custom domain
     */
    private function is_whitelabel_domain($domain) {
        /* Check if domain exists in whitelabel table */
        $whitelabel = Database::get('*', 'whitelabel', ['url' => $domain]);
        
        if ($whitelabel) {
            return true;
        }
        
        /* Check if domain exists in custom domains table */
        $custom_domain = Database::get('*', 'domains', [
            'host' => $domain,
            'is_active' => 1
        ]);
        
        return (bool) $custom_domain;
    }
}
