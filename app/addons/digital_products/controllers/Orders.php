<?php
namespace Altum\Addons\DigitalProducts\Controllers;

use Altum\Controllers\Controller;
use Altum\Addons\DigitalProducts\Models\Order_model;

class Orders extends Controller {

    public function index() {
        $orders = (new Order_model())->get_by_user($this->user->user_id);
        $this->add_view_content('content', \Altum\Views\View::render('digital_products/dashboard/orders_index', ['orders' => $orders]));
    }

    public function checkout($id) {
        $product = database()->where('product_id', $id)->getOne('products');
        if(!$product) redirect();

        $email = input_post('email');
        $name = input_post('name');

        // TODO: Validasi & integrasi payment gateway

        // Generate secure token
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 day'));

        // Simpan order
        $order_id = database()->insert('orders', [
            'product_id' => $product->product_id,
            'buyer_email' => $email,
            'buyer_name' => $name,
            'status' => 'paid',
            'download_token' => $token,
            'download_expires' => $expires,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Link download secure
        $download_link = url('download/'.$token);

        // Kirim email
        $subject = "Pesanan Anda: {$product->name}";
        $body = "
            Hai {$name},<br><br>
            Terima kasih sudah membeli <b>{$product->name}</b>.<br>
            Klik link berikut untuk download file Anda (berlaku 24 jam):<br>
            <a href='{$download_link}'>{$download_link}</a>
        ";

        send_mail($email, $subject, $body);

        redirect('p/'.$product->slug.'?success=1');
    }

    public function download($token) {
        $order = database()->where('download_token', $token)->getOne('orders');
        if(!$order) die("Token tidak valid.");

        if(strtotime($order->download_expires) < time()) {
            die("Link download sudah kadaluarsa.");
        }

        $product = database()->where('product_id', $order->product_id)->getOne('products');
        if(!$product || empty($product->file_url)) die("Link file tidak tersedia.");

        // Redirect ke Google Drive/Dropbox/Link Eksternal
        header("Location: " . $product->file_url);
        exit;
    }



}
