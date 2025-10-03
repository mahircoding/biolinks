<?php

namespace Altum\Helpers;

class Duitku {
    
    private $merchant_code;
    private $api_key;
    private $sandbox_mode;
    private $base_url;
    
    public function __construct($merchant_code, $api_key, $sandbox_mode = true) {
        $this->merchant_code = $merchant_code;
        $this->api_key = $api_key;
        $this->sandbox_mode = $sandbox_mode;
        $this->base_url = $sandbox_mode ? 'https://sandbox.duitku.com/webapi/api' : 'https://passport.duitku.com/webapi/api';
    }
    
    public function createInvoice($data) {
        $params = [
            'merchantCode' => $this->merchant_code,
            'paymentAmount' => $data['amount'],
            'paymentMethod' => $data['payment_method'] ?? 'VC',
            'merchantOrderId' => $data['order_id'],
            'productDetails' => $data['product_details'],
            'customerVaName' => $data['customer_name'],
            'email' => $data['email'],
            'phoneNumber' => $data['phone'],
            'callbackUrl' => $data['callback_url'],
            'returnUrl' => $data['return_url'],
            'expiryPeriod' => $data['expiry_period'] ?? 60
        ];
        
        /* Generate signature */
        $signature = md5($this->merchant_code . $params['merchantOrderId'] . $params['paymentAmount'] . $this->api_key);
        $params['signature'] = $signature;
        
        /* Make API request */
        $response = $this->makeRequest('/merchant/createinvoice', $params);
        
        return $response;
    }
    
    public function checkTransaction($merchantOrderId) {
        $params = [
            'merchantCode' => $this->merchant_code,
            'merchantOrderId' => $merchantOrderId
        ];
        
        /* Generate signature */
        $signature = md5($this->merchant_code . $merchantOrderId . $this->api_key);
        $params['signature'] = $signature;
        
        /* Make API request */
        $response = $this->makeRequest('/merchant/transactionStatus', $params);
        
        return $response;
    }
    
    public function getPaymentMethods($amount) {
        $params = [
            'merchantcode' => $this->merchant_code,
            'amount' => $amount,
            'datetime' => date('Y-m-d H:i:s')
        ];
        
        /* Generate signature */
        $signature = md5($this->merchant_code . $amount . date('Y-m-d H:i:s') . $this->api_key);
        $params['signature'] = $signature;
        
        /* Make API request */
        $response = $this->makeRequest('/merchant/paymentmethod/getpaymentmethod', $params);
        
        return $response;
    }
    
    private function makeRequest($endpoint, $params) {
        $url = $this->base_url . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('Curl error: ' . $error);
        }
        
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new \Exception('HTTP error: ' . $http_code);
        }
        
        $decoded_response = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decode error: ' . json_last_error_msg());
        }
        
        return $decoded_response;
    }
    
    public function verifySignature($merchantOrderId, $amount, $signature) {
        $expected_signature = md5($this->merchant_code . $merchantOrderId . $amount . $this->api_key);
        return $signature === $expected_signature;
    }
}