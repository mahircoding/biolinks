<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\DigitalProduct as DigitalProductModel;
use Altum\Models\DigitalOrder as DigitalOrderModel;

class DigitalOrder extends Controller {

    public function index() {
        /* Public product landing by slug: /digital-order/{slug} */
        $params = \Altum\Routing\Router::get_params();
        $slug = isset($params[0]) ? Database::clean_string($params[0]) : '';

        DigitalProductModel::migrate();
        DigitalOrderModel::migrate();

        $product = DigitalProductModel::find_by_slug($slug);
        if(!$product) redirect('notfound');

        $view = new \Altum\Views\View('digital-order/public-view', (array) $this);
        $this->add_view_content('content', $view->run(['product' => $product]));
    }

    public function checkout() {
        /* Form POST: name, email, phone, slug */
        if(empty($_POST)) redirect('notfound');

        $slug = Database::clean_string($_POST['slug'] ?? '');
        $name = Database::clean_string($_POST['name'] ?? '');
        $email = Database::clean_string($_POST['email'] ?? '');
        $phone = Database::clean_string($_POST['phone'] ?? '');

        $product = DigitalProductModel::find_by_slug($slug);
        if(!$product) redirect('notfound');

        $token = bin2hex(random_bytes(16));
        $expires_at = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 3); /* 3 days */

        DigitalOrderModel::create([
            'product_id' => $product->product_id,
            'buyer_name' => $name,
            'buyer_email' => $email,
            'buyer_phone' => $phone,
            'amount_cents' => (int)$product->price_cents,
            'currency' => $product->currency,
            'download_token' => $token,
            'download_expires_at' => $expires_at
        ]);

        /* Email link: use direct access_url if present, else token download link */
        $download_url = !empty($product->access_url)
            ? $product->access_url
            : url('digital-order/download/' . $token);

        $content = '<p>Terima kasih atas pesanan Anda.</p>' .
                   '<p>Produk: <strong>' . $product->name . '</strong></p>' .
                   '<p>Akses produk Anda:<br />' .
                   '<a href="' . $download_url . '">' . $download_url . '</a></p>';

        send_mail($this->settings, $email, 'Akses Produk Digital - {{WEBSITE_TITLE}}', $content, false);

        $view = new \Altum\Views\View('digital-order/thank-you', (array) $this);
        $this->add_view_content('content', $view->run(['email' => $email]));
    }

    public function download() {
        $params = \Altum\Routing\Router::get_params();
        $token = isset($params[0]) ? Database::clean_string($params[0]) : '';

        $order = DigitalOrderModel::find_by_token($token);
        if(!$order) redirect('notfound');

        $product = DigitalProductModel::find_by_id($order->product_id);
        if(!$product) redirect('notfound');

        /* If access_url set, redirect there (no expiry check) */
        if(!empty($product->access_url)) {
            header('Location: ' . $product->access_url);
            exit;
        }

        /* For file downloads, keep expiry enforcement */
        if(strtotime($order->download_expires_at) < time()) redirect('notfound');

        $full_path = UPLOADS_PATH . $product->file_path;
        if(!file_exists($full_path)) redirect('notfound');

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($full_path) . '"');
        header('Content-Length: ' . filesize($full_path));
        readfile($full_path);
        exit;
    }
}


