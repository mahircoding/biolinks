<?php

class Duitku {
    private $merchant_code;
    private $merchant_key;
    private $production = false;
    private $api_url = 'https://sandbox.duitku.com/webapi/api';
    
    public function __construct() {
        $this->merchant_code = getenv('DUITKU_MERCHANT_CODE') ?? '';
        $this->merchant_key = getenv('DUITKU_MERCHANT_KEY') ?? '';
        
        // Check if production mode
        if(getenv('DUITKU_PRODUCTION') === 'true') {
            $this->production = true;
            $this->api_url = 'https://api.duitku.com/webapi/api';
        }
    }
    
    public function generate_payment_url($order_data) {
        $params = [
            'merchantCode' => $this->merchant_code,
            'paymentAmount' => $order_data['amount'],
            'merchantOrderId' => $order_data['order_id'],
            'productDetails' => $order_data['product_name'],
            'additionalParam' => '',
            'merchantUserInfo' => '',
            'customerVaName' => $order_data['customer_name'],
            'email' => $order_data['customer_email'],
            'phoneNumber' => $order_data['customer_phone'],
            'paymentCallbackUrl' => url('orders/callback/duitku'),
            'paymentMethod' => 'VA',
            'merchantRedirectUrl' => url('orders/success/' . $order_data['order_id']),
            'signature' => $this->generate_signature($order_data),
            'currency' => 'IDR',
            'timeStamp' => time()
        ];
        
        $response = $this->send_request('payment', $params);
        
        if($response->statusCode != '00') {
            throw new Exception('Duitku Error: ' . $response->statusMessage);
        }
        
        return $response->paymentUrl;
    }
    
    public function get_payment_status($order_id) {
        $params = [
            'merchantCode' => $this->merchant_code,
            'merchantOrderId' => $order_id,
            'signature' => $this->generate_status_signature($order_id)
        ];
        
        $response = $this->send_request('paymentStatus', $params);
        
        if($response->statusCode != '00') {
            throw new Exception('Duitku Error: ' . $response->statusMessage);
        }
        
        return $response;
    }
    
    public function handle_callback($data) {
        // Verify signature
        $received_signature = $data['signature'];
        $generated_signature = $this->generate_callback_signature($data);
        
        if($received_signature != $generated_signature) {
            throw new Exception('Invalid signature');
        }
        
        // Map Duitku status to our status
        switch($data['resultCode']) {
            case '00':
                return 'completed';
                break;
            case '01':
                return 'pending';
                break;
            case '02':
                return 'failed';
                break;
            case '03':
                return 'cancelled';
                break;
            default:
                return 'unknown';
        }
    }
    
    private function generate_signature($order_data) {
        $string_to_hash = $this->merchant_code . $order_data['amount'] . $order_data['order_id'] . $this->merchant_key;
        return md5($string_to_hash);
    }
    
    private function generate_status_signature($order_id) {
        $string_to_hash = $this->merchant_code . $order_id . $this->merchant_key;
        return md5($string_to_hash);
    }
    
    private function generate_callback_signature($data) {
        $string_to_hash = $this->merchant_code . $data['merchantOrderId'] . $data['reference'] . $data['resultCode'] . $this->merchant_key;
        return md5($string_to_hash);
    }
    
    private function send_request($method, $params) {
        $curl = curl_init();
        
        $params_string = json_encode($params);
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->api_url . '/' . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params_string,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
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
    
    public function is_production() {
        return $this->production;
    }
    
    public function get_merchant_code() {
        return $this->merchant_code;
    }
    
    public function get_api_url() {
        return $this->api_url;
    }
}

// Helper functions
function generate_duitku_payment_url($order_data) {
    $duitku = new Duitku();
    return $duitku->generate_payment_url($order_data);
}

function get_duitku_payment_status($order_id) {
    $duitku = new Duitku();
    return $duitku->get_payment_status($order_id);
}

function handle_duitku_callback($data) {
    $duitku = new Duitku();
    return $duitku->handle_callback($data);
}

function is_duitku_production() {
    $duitku = new Duitku();
    return $duitku->is_production();
}

function get_duitku_merchant_code() {
    $duitku = new Duitku();
    return $duitku->get_merchant_code();
}