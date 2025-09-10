<?php
namespace Altum\Addons\DigitalProducts\Controllers;

use Altum\Controllers\Controller;
use Altum\Addons\DigitalProducts\Models\Product_model;

class Products extends Controller {

    public function index() {
        $products = (new Product_model())->get_by_user($this->user->user_id);
        $this->add_view_content('content', \Altum\Views\View::render('digital_products/dashboard/products_index', ['products' => $products]));
    }

    public function create() {
        $this->add_view_content('content', \Altum\Views\View::render('digital_products/dashboard/products_form'));
    }

    public function store() {
        $db = database();

        $name = input_post('name');
        $price = (float) input_post('price');
        $file_url = input_post('file_url'); // LINK EXTERNAL (Google Drive, Dropbox, dll)

        $db->insert('products', [
            'user_id' => $this->user->user_id,
            'name' => $name,
            'price' => $price,
            'file_url' => $file_url,
            'slug' => url_title($name, '-', true),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        redirect('dashboard/digital-products');
    }


    public function view($slug) {
        $product = (new Product_model())->get_by_slug($slug);
        if(!$product) redirect();

        $this->add_view_content('content', \Altum\Views\View::render('digital_products/public/product_view', ['product' => $product]));
    }
}
