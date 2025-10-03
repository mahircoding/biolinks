<?php

namespace Altum\Helpers;

class Duitku {

    private $merchant_code;
    private $merchant_key;
    private $production_mode;
    private $api_url;

    public function __construct() {
        $this->merchant_code = getenv('DUITKU_MERCHANT_CODE') ?? 'YOUR_MERCHANT_CODE';
        $this->merchant_key = getenv('DUITKU_MERCHANT_KEY') ?? 'YOUR_MERCHANT_KEY';
        $this->production_mode = getenv('DUITKU_PRODUCTION_MODE') ?? false;
        $this->api_url = $this->production_mode ? 
            'https://passport.duitku.com/webapi/api/merchant' : 
            'https://sandbox.duitku.com/webapi/api/merchant';
    }

    public function create_payment($data) {
        try {
            $params = [
                'merchantCode' => $this->merchant_code,
                'paymentAmount' => $data['amount'],
                'merchantOrderId' => $data['merchantOrderId'],
                'productDetails' => $data['product'],
                'additionalParam' => '',
                'merchantUserInfo' => '',
                'customerVaName' => $data['name'],
                'email' => $data['email'],
                'phoneNumber' => $data['phone'],
                'paymentCallbackUrl' => SITE_URL . '/webhook-duitku',
                'returnUrl' => SITE_URL . '/orders/success/' . $data['merchantOrderId'],
                'signature' => $this->generate_signature($data['merchantOrderId'], $data['amount'])
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/payment');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            $result = json_decode($response, true);

            if ($result['statusCode'] == '00') {
                return [
                    'status' => 'success',
                    'payment_url' => $result['paymentUrl'],
                    'merchantOrderId' => $result['merchantOrderId'],
                    'reference' => $result['reference']
                ];
            } else {
                throw new \Exception($result['statusMessage'] ?? 'Failed to create payment');
            }

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_payment_status($merchantOrderId) {
        try {
            $params = [
                'merchantCode' => $this->merchant_code,
                'merchantOrderId' => $merchantOrderId,
                'signature' => $this->generate_status_signature($merchantOrderId)
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/paymentStatus');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            $result = json_decode($response, true);

            return [
                'status' => 'success',
                'data' => $result
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function handle_webhook($input) {
        try {
            $signature = $this->generate_webhook_signature($input['merchantOrderId'], $input['amount'], $input['statusCode']);
            
            if ($signature != $input['signature']) {
                throw new \Exception('Invalid signature');
            }

            return [
                'status' => 'success',
                'data' => $input
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function generate_signature($merchantOrderId, $amount) {
        $stringToEncrypt = $this->merchant_code . $merchantOrderId . $amount . $this->merchant_key;
        return sha1($stringToEncrypt);
    }

    public function generate_status_signature($merchantOrderId) {
        $stringToEncrypt = $this->merchant_code . $merchantOrderId . $this->merchant_key;
        return sha1($stringToEncrypt);
    }

    public function generate_webhook_signature($merchantOrderId, $amount, $statusCode) {
        $stringToEncrypt = $this->merchant_code . $merchantOrderId . $amount . $statusCode . $this->merchant_key;
        return sha1($stringToEncrypt);
    }

    public function get_payment_methods() {
        try {
            $params = [
                'merchantCode' => $this->merchant_code,
                'signature' => $this->generate_payment_methods_signature()
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/paymentMethod');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            $result = json_decode($response, true);

            return [
                'status' => 'success',
                'data' => $result
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function generate_payment_methods_signature() {
        $stringToEncrypt = $this->merchant_code . $this->merchant_key;
        return sha1($stringToEncrypt);
    }

    public function inquiry_payment($merchantOrderId) {
        try {
            $params = [
                'merchantCode' => $this->merchant_code,
                'merchantOrderId' => $merchantOrderId,
                'signature' => $this->generate_inquiry_signature($merchantOrderId)
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/inquiry');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            $result = json_decode($response, true);

            return [
                'status' => 'success',
                'data' => $result
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function generate_inquiry_signature($merchantOrderId) {
        $stringToEncrypt = $this->merchant_code . $merchantOrderId . $this->merchant_key;
        return sha1($stringToEncrypt);
    }

    public function refund_payment($merchantOrderId, $refundAmount, $reason) {
        try {
            $params = [
                'merchantCode' => $this->merchant_code,
                'merchantOrderId' => $merchantOrderId,
                'refundAmount' => $refundAmount,
                'reason' => $reason,
                'signature' => $this->generate_refund_signature($merchantOrderId, $refundAmount)
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/refund');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            $result = json_decode($response, true);

            return [
                'status' => 'success',
                'data' => $result
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function generate_refund_signature($merchantOrderId, $refundAmount) {
        $stringToEncrypt = $this->merchant_code . $merchantOrderId . $refundAmount . $this->merchant_key;
        return sha1($stringToEncrypt);
    }

}