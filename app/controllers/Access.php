<?php

namespace Altum\Controllers;

use Altum\Database\Database;

class Access extends Controller {

    public function index() {
        $token = $_GET['token'] ?? null;
        
        if(!$token) {
            redirect('access/error');
        }
        
        /* Check if token exists and is valid */
        $access_token = Database::get('*', 'guest_access_tokens', [
            'token' => $token,
            'expires_at[>]' => date('Y-m-d H:i:s')
        ]);
        
        if(!$access_token) {
            redirect('access/error');
        }
        
        /* Get order details */
        $order = Database::get('*', 'orders', ['order_id' => $access_token->order_id]);
        
        if(!$order) {
            redirect('access/error');
        }
        
        /* Get product details */
        $product = Database::get('*', 'products', ['product_id' => $order->product_id]);
        
        if(!$product) {
            redirect('access/error');
        }
        
        /* Mark token as used */
        Database::update('guest_access_tokens', [
            'used_at' => date('Y-m-d H:i:s')
        ], ['id' => $access_token->id]);
        
        /* Prepare data for view */
        $data = (object) [
            'order' => $order,
            'product' => $product,
            'access_token' => $access_token
        ];
        
        /* Set page details */
        $data->title = 'Akses Produk - ' . $product->name;
        
        $view = new \Altum\Views\View('access/product', (array) $data);
        $this->add_view_content('content', $view->run());
    }

    public function verify() {
        /* Handle email verification for guest access */
        $product_id = $_POST['product_id'] ?? null;
        $email = $_POST['email'] ?? null;
        
        if(!$product_id || !$email) {
            $_SESSION['error'][] = 'Data tidak lengkap.';
            redirect('');
        }
        
        /* Check if user has purchased this product */
        $order = Database::get('*', 'orders', [
            'product_id' => $product_id,
            'customer_email' => $email,
            'status' => 'completed'
        ]);
        
        if(!$order) {
            $_SESSION['error'][] = 'Email tidak ditemukan atau belum melakukan pembelian produk ini.';
            redirect('product/' . $product_id);
        }
        
        /* Check if access token already exists */
        $existing_token = Database::get('*', 'guest_access_tokens', [
            'order_id' => $order->order_id,
            'email' => $email,
            'expires_at[>]' => date('Y-m-d H:i:s')
        ]);
        
        if($existing_token) {
            /* Use existing token */
            $access_token = $existing_token->token;
        } else {
            /* Generate new access token */
            $access_token = md5($order->order_id . $email . time());
            
            /* Store access token in database */
            Database::insert('guest_access_tokens', [
                'order_id' => $order->order_id,
                'token' => $access_token,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ]);
        }
        
        /* Redirect to access page */
        redirect('access?token=' . $access_token);
    }

    public function error() {
        /* Show error page for invalid access */
        $data = (object) [
            'title' => 'Akses Tidak Valid'
        ];
        
        $view = new \Altum\Views\View('access/error', (array) $data);
        $this->add_view_content('content', $view->run());
    }
}