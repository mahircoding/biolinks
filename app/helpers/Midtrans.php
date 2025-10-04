<?php

class Midtrans {
    private $server_key;
    private $client_key;
    private $production = false;
    private $api_url = 'https://api.sandbox.midtrans.com/v2';
    
    public function __construct() {
        $this->server_key = getenv('MIDTRANS_SERVER_KEY') ?? '';
        $this->client_key = getenv('MIDTRANS_CLIENT_KEY') ?? '';
        
        // Check if production mode
        if(getenv('MIDTRANS_PRODUCTION') === 'true') {
            $this->production = true;
            $this->api_url = 'https://api.midtrans.com/v2';
        }
    }
    
    public function generate_snap_token($order_data) {
        $curl = curl_init();
        
        $params = [
            'transaction_details' => [
                'order_id' => $order_data['order_id'],
                'gross_amount' => $order_data['amount']
            ],
            'customer_details' => [
                'first_name' => $order_data['customer_name'],
                'email' => $order_data['customer_email'],
                'phone' => $order_data['customer_phone']
            ],
            'item_details' => [
                [
                    'id' => $order_data['product_id'],
                    'price' => $order_data['amount'],
                    'quantity' => 1,
                    'name' => $order_data['product_name']
                ]
            ],
            'callbacks' => [
                'finish' => url('orders/success/' . $order_data['order_id'])
            ]
        ];
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->api_url . '/charge',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if($err) {
            throw new Exception('cURL Error: ' . $err);
        }
        
        $result = json_decode($response);
        
        if($result->status_code != 201) {
            throw new Exception('Midtrans Error: ' . $result->status_message);
        }
        
        return $result->token;
    }
    
    public function get_transaction_status($order_id) {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->api_url . '/' . $order_id . '/status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if($err) {
            throw new Exception('cURL Error: ' . $err);
        }
        
        return json_decode($response);
    }
    
    public function handle_webhook($signature_key, $body) {
        $hashed = hash('sha512', $body . $this->server_key);
        
        if($hashed != $signature_key) {
            throw new Exception('Invalid signature key');
        }
        
        $notification = json_decode($body);
        
        switch($notification->transaction_status) {
            case 'capture':
            case 'settlement':
                return 'completed';
                break;
            case 'pending':
            case 'expire':
                return 'failed';
                break;
            case 'cancel':
                return 'cancelled';
                break;
            default:
                return 'unknown';
        }
    }
    
    public function is_production() {
        return $this->production;
    }
    
    public function get_client_key() {
        return $this->client_key;
    }
    
    public function get_api_url() {
        return $this->api_url;
    }
}

// Helper functions
function generate_midtrans_snap_token($order_data) {
    $midtrans = new Midtrans();
    return $midtrans->generate_snap_token($order_data);
}

function get_midtrans_transaction_status($order_id) {
    $midtrans = new Midtrans();
    return $midtrans->get_transaction_status($order_id);
}

function handle_midtrans_webhook($signature_key, $body) {
    $midtrans = new Midtrans();
    return $midtrans->handle_webhook($signature_key, $body);
}

function is_midtrans_production() {
    $midtrans = new Midtrans();
    return $midtrans->is_production();
}

function get_midtrans_client_key() {
    $midtrans = new Midtrans();
    return $midtrans->get_client_key();
}