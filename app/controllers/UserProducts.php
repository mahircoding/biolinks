<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\DigitalProduct as DigitalProductModel;
use Altum\Models\DigitalOrder as DigitalOrderModel;

class UserProducts extends Controller {

    public function index() {
        /* Public product listing by user_id: /{user_id} */
        $params = \Altum\Routing\Router::get_params();
        $user_id = isset($params[0]) ? (int)$params[0] : 0;
        
        if(!$user_id) redirect('notfound');

        DigitalProductModel::migrate();

        /* Get user's products */
        $products = \Altum\Models\DigitalProduct::list_by_user($user_id);
        
        /* Get user info including Tripay settings and bank account */
        $user = Database::get(['user_id', 'name', 'email', 'phone', 'tripay_merchant_code', 'tripay_api_key_public', 'tripay_api_key_secret', 'bank_account'], 'users', ['user_id' => $user_id]);
        if(!$user) redirect('notfound');

        $view = new \Altum\Views\View('user-products/index', (array) $this);
        $this->add_view_content('content', $view->run(['products' => $products, 'user' => $user]));
    }

    public function view() {
        /* Product detail: /{user_id}/{slug} */
        $params = \Altum\Routing\Router::get_params();
        $user_id = isset($params[0]) ? (int)$params[0] : 0;
        $slug = isset($params[1]) ? Database::clean_string($params[1]) : '';
        
        if(!$user_id || !$slug) redirect('notfound');

        DigitalProductModel::migrate();

        $product = DigitalProductModel::find_by_slug($slug);
        if(!$product || (int)$product->user_id !== $user_id) redirect('notfound');

        /* Get user info including Tripay settings and bank account */
        $user = Database::get(['user_id', 'name', 'email', 'phone', 'tripay_merchant_code', 'tripay_api_key_public', 'tripay_api_key_secret', 'bank_account'], 'users', ['user_id' => $user_id]);
        if(!$user) redirect('notfound');

        $view = new \Altum\Views\View('user-products/view', (array) $this);
        $this->add_view_content('content', $view->run(['product' => $product, 'user' => $user]));
    }

    public function checkout() {
        /* Checkout: /{user_id}/{slug}/checkout */
        $params = \Altum\Routing\Router::get_params();
        $user_id = isset($params[0]) ? (int)$params[0] : 0;
        $slug = isset($params[1]) ? Database::clean_string($params[1]) : '';
        
        if(!$user_id || !$slug) redirect('notfound');

        DigitalProductModel::migrate();
        DigitalOrderModel::migrate();

        $product = DigitalProductModel::find_by_slug($slug);
        if(!$product || (int)$product->user_id !== $user_id) redirect('notfound');

        /* Get user info including Tripay settings and bank account */
        $user = Database::get(['user_id', 'name', 'email', 'phone', 'tripay_merchant_code', 'tripay_api_key_public', 'tripay_api_key_secret', 'bank_account'], 'users', ['user_id' => $user_id]);
        if(!$user) redirect('notfound');

        if(!empty($_POST)) {
            $name = Database::clean_string($_POST['name'] ?? '');
            $email = Database::clean_string($_POST['email'] ?? '');
            $phone = Database::clean_string($_POST['phone'] ?? '');
            $payment_method = Database::clean_string($_POST['payment_method'] ?? '');

            $token = bin2hex(random_bytes(16));
            $expires_at = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 3);

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

            /* Check payment method and process accordingly */
            if(strpos($payment_method, 'bank_transfer_') === 0) {
                /* Bank Transfer Payment */
                $bank_name = str_replace('bank_transfer_', '', $payment_method);
                $bank_accounts = json_decode($user->bank_account);
                $selected_bank = null;
                
                if($bank_accounts) {
                    foreach($bank_accounts as $bank) {
                        if($bank->bank_name === $bank_name) {
                            $selected_bank = $bank;
                            break;
                        }
                    }
                }
                
                if($selected_bank) {
                    /* Send bank transfer instructions via email */
                    $content = '<p>Terima kasih atas pesanan Anda.</p>' .
                               '<p>Produk: <strong>' . $product->name . '</strong></p>' .
                               '<p>Harga: <strong>Rp ' . number_format($product->price_cents / 100, 0, ',', '.') . '</strong></p>' .
                               '<p>Silakan transfer ke rekening berikut:</p>' .
                               '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">' .
                               '<p><strong>Bank:</strong> ' . htmlspecialchars($selected_bank->bank_name) . '</p>' .
                               '<p><strong>Nama Rekening:</strong> ' . htmlspecialchars($selected_bank->account_name) . '</p>' .
                               '<p><strong>Nomor Rekening:</strong> ' . htmlspecialchars($selected_bank->account_number) . '</p>' .
                               '</div>' .
                               '<p>Setelah transfer, produk akan dikirim ke email Anda.</p>';
                    
                    send_mail($this->settings, $email, 'Instruksi Pembayaran - {{WEBSITE_TITLE}}', $content, false);
                    
                    /* Update order status */
                    Database::update(DigitalOrderModel::$table, ['status' => 'pending_payment'], ['download_token' => $token]);
                    
                    $view = new \Altum\Views\View('user-products/bank-transfer-instructions', (array) $this);
                    $this->add_view_content('content', $view->run(['bank' => $selected_bank, 'product' => $product, 'email' => $email]));
                    return;
                }
            }
            /* If Tripay configured for this user, create transaction and redirect to payment page */
            elseif(!empty($user->tripay_merchant_code) && !empty($user->tripay_api_key_public) && !empty($user->tripay_api_key_secret)) {
                $reference = 'DOP-' . time() . '-' . rand(1000,9999);

                $payload = [
                    'method'        => $payment_method ?: 'QRIS',
                    'merchant_ref'  => $reference,
                    'amount'        => (int)$product->price_cents,
                    'customer_name' => $name,
                    'customer_email'=> $email,
                    'customer_phone'=> $phone,
                    'order_items'   => [
                        [
                            'sku'         => (string)$product->product_id,
                            'name'        => $product->name,
                            'price'       => (int)$product->price_cents,
                            'quantity'    => 1,
                            'product_url' => url($user_id . '/' . $product->slug)
                        ]
                    ],
                    'expired_time'  => time() + (24 * 60 * 60),
                    'signature'     => hash_hmac('sha256', $user->tripay_merchant_code . $reference . ((int) ceil($product->price_cents / 100)), $user->tripay_api_key_secret),
                    'return_url'    => url($user_id . '/' . $product->slug),
                    'callback_url'  => url('digital-order/webhook')
                ];

                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_FRESH_CONNECT  => true,
                    CURLOPT_URL            => 'https://tripay.co.id/api/transaction/create',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER         => false,
                    CURLOPT_HTTPHEADER     => [ 'Authorization: Bearer ' . $user->tripay_api_key_public ],
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

            /* Fallback: no Tripay configured for this user; show thank you & send immediate access */
            $download_url = !empty($product->access_url) ? $product->access_url : url('digital-order/download/' . $token);
            $content = '<p>Terima kasih atas pesanan Anda.</p>' .
                       '<p>Produk: <strong>' . $product->name . '</strong></p>' .
                       '<p>Akses produk Anda:<br />' .
                       '<a href="' . $download_url . '">' . $download_url . '</a></p>';
            send_mail($this->settings, $email, 'Akses Produk Digital - {{WEBSITE_TITLE}}', $content, false);

            $view = new \Altum\Views\View('user-products/thank-you', (array) $this);
            $this->add_view_content('content', $view->run(['email' => $email, 'product' => $product]));
        } else {
            $view = new \Altum\Views\View('user-products/checkout', (array) $this);
            $this->add_view_content('content', $view->run(['product' => $product, 'user' => $user]));
        }
    }
}
