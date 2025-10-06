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

        /* Account header */
        $menu = new \Altum\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        $view = new \Altum\Views\View('digital-product/index', (array) $this);
        $this->add_view_content('content', $view->run(['products' => $products]));
    }

    public function create() {
        Authentication::guard();
        DigitalProductModel::migrate();

        if(!empty($_POST)) {
            if(!Csrf::check()) redirect('digital-product');

            $name = Database::clean_string($_POST['name'] ?? '');
            
            /* Auto-generate slug - always generate unique alphanumeric slug */
            do {
                $slug = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
            } while(Database::exists('product_id', \Altum\Models\DigitalProduct::$table, ['slug' => $slug]));
            
            $description = Database::clean_string($_POST['description'] ?? '');
            $price_cents = (int) ($_POST['price_cents'] ?? 0);
            $currency = 'IDR'; // Fixed to IDR

            $access_url = Database::clean_string($_POST['access_url'] ?? '');
            $file_path = '';
            $image_path = '';

            /* Handle image upload */
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                if(in_array($_FILES['image']['type'], $allowed_types) && $_FILES['image']['size'] <= $max_size) {
                    $upload_dir = 'uploads/digital-products/';
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $image_filename = 'product_' . time() . '_' . $slug . '.' . $file_extension;
                    $image_path = $upload_dir . $image_filename;
                    
                    if(!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                        $_SESSION['error'][] = 'Gagal mengupload gambar';
                    }
                } else {
                    $_SESSION['error'][] = 'Format gambar tidak didukung atau ukuran terlalu besar (maksimal 2MB)';
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
                    'file_path' => $file_path,
                    'access_url' => $access_url,
                    'image_path' => $image_path
                ]);

                redirect('digital-product');
            }
        }

        /* Account header */
        $menu = new \Altum\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

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


