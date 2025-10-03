<?php

namespace Altum\Helpers;

class Midtrans {

    private $server_key;
    private $client_key;
    private $production_mode;
    private $api_url;

    public function __construct() {
        $this->server_key = getenv('MIDTRANS_SERVER_KEY') ?? 'YOUR_SERVER_KEY';
        $this->client_key = getenv('MIDTRANS_CLIENT_KEY') ?? 'YOUR_CLIENT_KEY';
        $this->production_mode = getenv('MIDTRANS_PRODUCTION_MODE') ?? false;
        $this->api_url = $this->production_mode ? 'https://api.midtrans.com/v2' : 'https://api.sandbox.midtrans.com/v2';
    }

    public function create_transaction($data) {
        try {
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            ];

            $payload = [
                'payment_type' => 'gopay',
                'transaction_details' => $data['transaction_details'],
                'customer_details' => $data['customer_details'],
                'item_details' => $data['item_details'],
                'callbacks' => [
                    'finish' => SITE_URL . '/orders/success/{order_id}'
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/charge');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            $result = json_decode($response, true);

            if ($result['status_code'] == 201) {
                return [
                    'status' => 'success',
                    'redirect_url' => $result['redirect_url'],
                    'transaction_id' => $result['transaction_id'],
                    'token' => $result['token']
                ];
            } else {
                throw new \Exception($result['status_message'] ?? 'Failed to create transaction');
            }

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_transaction_status($order_id) {
        try {
            $headers = [
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/' . $order_id . '/status');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
            $signature_key = hash('sha512', $input['order_id'] . $input['status_code'] . $input['gross_amount'] . $this->server_key);
            
            if ($signature_key != $input['signature_key']) {
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

    public function generate_snap_token($data) {
        try {
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            ];

            $payload = [
                'transaction_details' => $data['transaction_details'],
                'customer_details' => $data['customer_details'],
                'item_details' => $data['item_details'],
                'callbacks' => [
                    'finish' => SITE_URL . '/orders/success/{order_id}'
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/snap/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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
                'token' => $result['token']
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function create_snap_redirect_url($data) {
        try {
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            ];

            $payload = [
                'transaction_details' => $data['transaction_details'],
                'customer_details' => $data['customer_details'],
                'item_details' => $data['item_details'],
                'callbacks' => [
                    'finish' => SITE_URL . '/orders/success/{order_id}'
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/snap');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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
                'redirect_url' => $result['redirect_url'],
                'token' => $result['token']
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function refund_transaction($order_id, $amount = null, $reason = null) {
        try {
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            ];

            $payload = [
                'transaction_id' => $order_id,
                'amount' => $amount,
                'reason' => $reason
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/' . $order_id . '/refund');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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

}