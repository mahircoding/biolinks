<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\DigitalProduct as DigitalProductModel;

class DigitalProduct extends Controller {

    public function index() {
        Authentication::guard();

        /* Auto migrate tables once */
        DigitalProductModel::migrate();

        $products = \Altum\Models\DigitalProduct::list_by_user($this->user->user_id);

        $view = new \Altum\Views\View('digital-product/index', (array) $this);
        $this->add_view_content('content', $view->run(['products' => $products]));
    }

    public function create() {
        Authentication::guard();
        DigitalProductModel::migrate();

        if(!empty($_POST)) {
            if(!Csrf::check()) redirect('digital-product');

            $name = Database::clean_string($_POST['name'] ?? '');
            $slug = Database::clean_string($_POST['slug'] ?? '');
            $description = Database::clean_string($_POST['description'] ?? '');
            $price_cents = (int) ($_POST['price_cents'] ?? 0);
            $currency = Database::clean_string($_POST['currency'] ?? 'USD');

            $file_uploaded = (!empty($_FILES['file']['name']));
            $file_path = '';

            if($file_uploaded) {
                $file_name = $_FILES['file']['name'];
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $tmp = $_FILES['file']['tmp_name'];

                if(!is_writable(UPLOADS_PATH . 'digital/')) {
                    $_SESSION['error'][] = sprintf($this->language->global->error_message->directory_not_writable, UPLOADS_PATH . 'digital/');
                }

                if(empty($_SESSION['error'])) {
                    if(!file_exists(UPLOADS_PATH . 'digital/')) @mkdir(UPLOADS_PATH . 'digital/', 0755, true);
                    $new_name = md5(time() . rand()) . '.' . $ext;
                    move_uploaded_file($tmp, UPLOADS_PATH . 'digital/' . $new_name);
                    $file_path = 'digital/' . $new_name;
                }
            }

            if(empty($_SESSION['error'])) {
                \Altum\Models\DigitalProduct::create([
                    'user_id' => $this->user->user_id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'price_cents' => $price_cents,
                    'currency' => $currency,
                    'file_path' => $file_path
                ]);

                redirect('digital-product');
            }
        }

        $view = new \Altum\Views\View('digital-product/create', (array) $this);
        $this->add_view_content('content', $view->run());
    }

    public function delete() {
        Authentication::guard();
        if(!Csrf::check()) redirect('digital-product');

        $product_id = (int) ($_POST['product_id'] ?? 0);
        $product = \Altum\Models\DigitalProduct::find_by_id($product_id);
        if($product && (int)$product->user_id === (int)$this->user->user_id) {
            if($product->file_path && file_exists(UPLOADS_PATH . $product->file_path)) @unlink(UPLOADS_PATH . $product->file_path);
            \Altum\Models\DigitalProduct::delete_by_id($product_id, $this->user->user_id);
        }
        redirect('digital-product');
    }
}


