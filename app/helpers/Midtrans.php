<?php

namespace Altum\Helpers;

class Midtrans {
    
    private static $server_key;
    private static $client_key;
    private static $is_production = false;
    private static $base_url;

    public static function init($server_key, $client_key, $is_production = false) {
        self::$server_key = $server_key;
        self::$client_key = $client_key;
        self::$is_production = $is_production;
        self::$base_url = $is_production ? 'https://api.midtrans.com/v2' : 'https://api.sandbox.midtrans.com/v2';
    }

    public static function create_transaction($order_data) {
        $url = self::$base_url . '/charge';
        
        $data = [
            'payment_type' => 'gopay', // atau payment method lain
            'transaction_details' => [
                'order_id' => $order_data['order_id'],
                'gross_amount' => $order_data['amount']
            ],
            'customer_details' => [
                'first_name' => $order_data['customer_name'],
                'email' => $order_data['customer_email']
            ],
            'item_details' => [
                [
                    'id' => $order_data['product_id'],
                    'price' => $order_data['amount'],
                    'quantity' => 1,
                    'name' => $order_data['product_name']
                ]
            ]
        ];

        return self::make_request($url, $data);
    }

    public static function create_snap_token($order_data) {
        $url = (self::$is_production ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions');
        
        $data = [
            'transaction_details' => [
                'order_id' => $order_data['order_id'],
                'gross_amount' => (int) $order_data['amount']
            ],
            'customer_details' => [
                'first_name' => $order_data['customer_name'],
                'email' => $order_data['customer_email']
            ],
            'item_details' => [
                [
                    'id' => $order_data['product_id'],
                    'price' => (int) $order_data['amount'],
                    'quantity' => 1,
                    'name' => $order_data['product_name']
                ]
            ],
            'callbacks' => [
                'finish' => SITE_URL . 'orders/success/' . $order_data['local_order_id']
            ]
        ];

        return self::make_request($url, $data);
    }

    public static function get_transaction_status($order_id) {
        $url = self::$base_url . '/' . $order_id . '/status';
        
        return self::make_request($url, null, 'GET');
    }

    private static function make_request($url, $data = null, $method = 'POST') {
        $ch = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode(self::$server_key . ':')
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if($method == 'POST' && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        return [
            'status_code' => $http_code,
            'data' => json_decode($response, true)
        ];
    }

    public static function verify_signature($payload, $signature) {
        $calculated_signature = hash('sha512', $payload . self::$server_key);
        return hash_equals($calculated_signature, $signature);
    }
}