<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\DigitalProduct as DigitalProductModel;

class DigitalProductController extends Controller {

    public function index() {
        Authentication::guard();

        // Get all products for the current user
        $products = (new DigitalProductModel())->get_products_by_user($this->user->user_id);

        /* Prepare the View */
        $data = [
            'products' => $products
        ];

        $view = new \Altum\Views\View('digital-product/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function create() {
        Authentication::guard();

        if(!empty($_POST)) {
            // Validate CSRF token
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            // Validate required fields
            if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['access_url']) || !isset($_POST['price'])) {
                $_SESSION['error'][] = $this->language->global->error_message->empty_fields;
            }

            if(empty($_SESSION['error'])) {
                // Process image upload if provided
                $image = null;
                if(!empty($_FILES['image']['name'])) {
                    // Handle image upload
                    $upload_dir = UPLOADS_PATH . 'products/';
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $image = md5(time() . $_FILES['image']['name']) . '.' . $file_extension;
                    $upload_file = $upload_dir . $image;
                    
                    if(!move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                        $image = null;
                    }
                }

                // Prepare data for insertion
                $data = [
                    'user_id' => $this->user->user_id,
                    'name' => Database::clean_string($_POST['name']),
                    'description' => Database::clean_string($_POST['description']),
                    'image' => $image,
                    'access_url' => Database::clean_string($_POST['access_url']),
                    'price' => (float) $_POST['price'],
                    'status' => isset($_POST['status']) ? Database::clean_string($_POST['status']) : 'active',
                    'date' => \Altum\Date::$date
                ];

                // Insert product
                (new DigitalProductModel())->create_product($data);

                $_SESSION['success'][] = 'Product created successfully';
                redirect('digital-product');
            }
        }

        /* Prepare the View */
        $view = new \Altum\Views\View('digital-product/create', (array) $this);
        $this->add_view_content('content', $view->run());
    }

    public function update() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        // Check if product exists and belongs to current user
        $product = (new DigitalProductModel())->get_product($product_id);
        if(!$product || $product->user_id != $this->user->user_id) {
            redirect('digital-product');
        }

        if(!empty($_POST)) {
            // Validate CSRF token
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            // Validate required fields
            if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['access_url']) || !isset($_POST['price'])) {
                $_SESSION['error'][] = $this->language->global->error_message->empty_fields;
            }

            if(empty($_SESSION['error'])) {
                // Process image upload if provided
                $image = $product->image; // Keep existing image by default
                if(!empty($_FILES['image']['name'])) {
                    // Handle image upload
                    $upload_dir = UPLOADS_PATH . 'products/';
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $image = md5(time() . $_FILES['image']['name']) . '.' . $file_extension;
                    $upload_file = $upload_dir . $image;
                    
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                        // Delete old image if it exists
                        if($product->image && file_exists($upload_dir . $product->image)) {
                            unlink($upload_dir . $product->image);
                        }
                    } else {
                        $image = $product->image; // Keep old image if upload failed
                    }
                }

                // Prepare data for update
                $data = [
                    'name' => Database::clean_string($_POST['name']),
                    'description' => Database::clean_string($_POST['description']),
                    'image' => $image,
                    'access_url' => Database::clean_string($_POST['access_url']),
                    'price' => (float) $_POST['price'],
                    'status' => isset($_POST['status']) ? Database::clean_string($_POST['status']) : 'active'
                ];

                // Update product
                (new DigitalProductModel())->update_product($product_id, $data);

                $_SESSION['success'][] = 'Product updated successfully';
                redirect('digital-product');
            }
        }

        /* Prepare the View */
        $data = [
            'product' => $product
        ];

        $view = new \Altum\Views\View('digital-product/update', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        // Check if product exists and belongs to current user
        $product = (new DigitalProductModel())->get_product($product_id);
        if(!$product || $product->user_id != $this->user->user_id) {
            redirect('digital-product');
        }

        // Validate CSRF token
        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
        }

        if(empty($_SESSION['error'])) {
            // Delete product image if it exists
            if($product->image) {
                $image_path = UPLOADS_PATH . 'products/' . $product->image;
                if(file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            // Delete product
            (new DigitalProductModel())->delete_product($product_id);

            $_SESSION['success'][] = 'Product deleted successfully';
        }

        redirect('digital-product');
    }

    // Public method to display all products
    public function public_index() {
        // Get all active products
        $products = (new DigitalProductModel())->get_all_products();

        /* Prepare the View */
        $data = [
            'products' => $products
        ];

        $view = new \Altum\Views\View('digital-product/public-index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    // Public method to view a single product
    public function public_view() {
        $product_id = isset($this->params[0]) ? (int) $this->params[0] : null;
        
        // Get product details
        $product = (new DigitalProductModel())->get_product($product_id);
        if(!$product || $product->status != 'active') {
            redirect('notfound');
        }

        // Get seller details
        $seller = Database::get(['name'], 'users', ['user_id' => $product->user_id]);

        /* Prepare the View */
        $data = [
            'product' => $product,
            'seller' => $seller
        ];

        $view = new \Altum\Views\View('digital-product/public-view', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}