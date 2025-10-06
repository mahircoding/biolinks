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
            
            /* Auto-generate slug */
            do {
                $slug = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
            } while(Database::exists('product_id', \Altum\Models\DigitalProduct::$table, ['slug' => $slug]));
            
            $description = Database::clean_string($_POST['description'] ?? '');
            $price_cents = (int) ($_POST['price_cents'] ?? 0);
            $currency = 'IDR'; // Set currency to Indonesian Rupiah automatically

            $access_url = Database::clean_string($_POST['access_url'] ?? '');
            $file_path = '';
            $image = '';

            /* Handle image upload */
            if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                $image_allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $image_file_extension = explode('.', $_FILES['image']['name']);
                $image_file_extension = strtolower(end($image_file_extension));
                $image_file_temp = $_FILES['image']['tmp_name'];

                /* Check for upload errors */
                if($_FILES['image']['error']) {
                    $_SESSION['error'][] = 'Error uploading image file.';
                }

                /* Check file extension */
                if(!in_array($image_file_extension, $image_allowed_extensions)) {
                    $_SESSION['error'][] = 'Invalid image file type. Only JPG, JPEG, PNG, and GIF are allowed.';
                }

                /* Check file size (max 2MB) */
                if($_FILES['image']['size'] > 2097152) {
                    $_SESSION['error'][] = 'Image file size too large. Maximum 2MB allowed.';
                }

                /* If no errors, process the upload */
                if(empty($_SESSION['error'])) {
                    /* Create uploads directory if it doesn't exist */
                    if (!file_exists(UPLOADS_PATH . 'digital-products/')) {
                        mkdir(UPLOADS_PATH . 'digital-products/', 0755, true);
                    }

                    /* Generate unique filename */
                    $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;
                    
                    /* Move uploaded file */
                    if(move_uploaded_file($image_file_temp, UPLOADS_PATH . 'digital-products/' . $image_new_name)) {
                        $image = $image_new_name;
                    } else {
                        $_SESSION['error'][] = 'Failed to upload image file.';
                    }
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
                    'image' => $image,
                    'file_path' => $file_path,
                    'access_url' => $access_url
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
            /* Delete product file if exists */
            if($product->file_path && file_exists(UPLOADS_PATH . $product->file_path)) @unlink(UPLOADS_PATH . $product->file_path);
            
            /* Delete product image if exists */
            if($product->image && file_exists(UPLOADS_PATH . 'digital-products/' . $product->image)) @unlink(UPLOADS_PATH . 'digital-products/' . $product->image);
            
            \Altum\Models\DigitalProduct::delete_by_id($product_id, $this->user->user_id);
        }
        redirect('digital-product');
    }
}


