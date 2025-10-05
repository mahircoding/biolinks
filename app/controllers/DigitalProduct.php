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
            $slug = Database::clean_string($_POST['slug'] ?? '');
            
            /* Auto-generate slug if empty */
            if(empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
                $slug = preg_replace('/-+/', '-', $slug);
                $slug = trim($slug, '-');
                
                /* Ensure unique slug */
                $original_slug = $slug;
                $counter = 1;
                while(Database::exists('product_id', \Altum\Models\DigitalProduct::$table, ['slug' => $slug])) {
                    $slug = $original_slug . '-' . $counter;
                    $counter++;
                }
            }
            
            $description = Database::clean_string($_POST['description'] ?? '');
            $price_cents = (int) ($_POST['price_cents'] ?? 0);
            $currency = Database::clean_string($_POST['currency'] ?? 'USD');

            $access_url = Database::clean_string($_POST['access_url'] ?? '');
            $file_path = '';

            if(empty($_SESSION['error'])) {
                \Altum\Models\DigitalProduct::create([
                    'user_id' => $this->user->user_id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'price_cents' => $price_cents,
                    'currency' => $currency,
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
            if($product->file_path && file_exists(UPLOADS_PATH . $product->file_path)) @unlink(UPLOADS_PATH . $product->file_path);
            \Altum\Models\DigitalProduct::delete_by_id($product_id, $this->user->user_id);
        }
        redirect('digital-product');
    }
}


