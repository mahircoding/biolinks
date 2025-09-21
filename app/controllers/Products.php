<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Product;

class Products extends Controller {

    public function index() {
        Authentication::guard();

        /* Get the products */
        $products_result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = {$this->user->user_id} 
            ORDER BY `datetime` DESC
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '{}');
            $products[] = $row;
        }

        /* Prepare the view */
        $data = [
            'products' => $products
        ];

        $view = new \Altum\Views\View('products/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function create() {
        Authentication::guard();

        if(!empty($_POST)) {
            $name = Database::clean_string($_POST['name']);
            $description = Database::clean_string($_POST['description']);
            $price = (float) $_POST['price'];
            $digital_link = Database::clean_string($_POST['digital_link']);
            $status = isset($_POST['status']) ? 1 : 0;

            /* Basic validation */
            if(empty($name) || empty($description) || $price < 0) {
                $error = 'Please fill all required fields correctly';
            } else {
                /* Handle image upload */
                $image = null;
                if(!empty($_FILES['image']['name'])) {
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    
                    if(in_array($file_extension, $allowed_extensions)) {
                        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/products/';
                        if(!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        
                        $image_name = md5(time() . rand()) . '.' . $file_extension;
                        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                            $image = $image_name;
                        }
                    }
                }

                /* Insert to database */
                $product_id = md5($this->user->user_id . $name . time());

                Database::insert('products', [
                    'product_id' => $product_id,
                    'user_id' => $this->user->user_id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'image' => $image,
                    'digital_link' => $digital_link,
                    'status' => $status,
                    'datetime' => \Altum\Date::$date
                ]);

                redirect('products');
            }
        }

        $view = new \Altum\Views\View('products/create', (array) $this);
        $this->add_view_content('content', $view->run([]));
    }

    public function view() {
        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and is active */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'status' => 1]);
        if(!$product) {
            redirect('');
        }

        /* Get product owner info */
        $product_owner = Database::get(['name', 'email'], 'users', ['user_id' => $product->user_id]);

        /* Increment views */
        Database::$database->query("UPDATE `products` SET `views` = `views` + 1 WHERE `product_id` = '" . Database::clean_string($product_id) . "'");

        /* Check if user has already purchased this product */
        $has_purchased = false;
        if($this->user) {
            $order = Database::get('*', 'orders', ['user_id' => $this->user->user_id, 'product_id' => $product_id, 'status' => 'completed']);
            $has_purchased = (bool) $order;
        }

        /* Prepare the view */
        $data = [
            'product' => $product,
            'product_owner' => $product_owner,
            'has_purchased' => $has_purchased
        ];

        $view = new \Altum\Views\View('products/view', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function catalog() {
        /* Get the products */
        $products_result = Database::$database->query("
            SELECT p.*, u.name as seller_name 
            FROM `products` p
            LEFT JOIN `users` u ON p.user_id = u.user_id
            WHERE p.status = 1 
            ORDER BY p.datetime DESC
            LIMIT 20
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $products[] = $row;
        }

        /* Prepare the view */
        $data = [
            'products' => $products
        ];

        $view = new \Altum\Views\View('products/catalog', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}

    public function update() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and belongs to the user */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'user_id' => $this->user->user_id]);
        if(!$product) {
            redirect('products');
        }

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['description'] = input_clean($_POST['description']);
            $_POST['price'] = (float) $_POST['price'];
            $_POST['digital_link'] = input_clean($_POST['digital_link']);
            $_POST['status'] = (int) isset($_POST['status']);

            /* Check for any errors */
            $required_fields = ['name', 'description', 'price'];
            foreach($required_fields as $field) {
                if(empty($_POST[$field])) {
                    \Altum\Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if($_POST['price'] < 0) {
                \Altum\Alerts::add_field_error('price', l('products.error_message.invalid_price'));
            }

            /* Image upload */
            $image = $product->image;
            if(!empty($_FILES['image']['name'])) {
                $image_allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $image_file_extension = explode('.', $_FILES['image']['name']);
                $image_file_extension = strtolower(end($image_file_extension));

                if($_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE) {
                    \Altum\Alerts::add_field_error('image', sprintf(l('global.error_message.file_size_limit'), get_max_upload()));
                }

                if($_FILES['image']['error'] && $_FILES['image']['error'] != UPLOAD_ERR_INI_SIZE) {
                    \Altum\Alerts::add_field_error('image', l('global.error_message.file_upload'));
                }

                if(!in_array($image_file_extension, $image_allowed_extensions)) {
                    \Altum\Alerts::add_field_error('image', l('global.error_message.invalid_file_type'));
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(UPLOADS_PATH . 'products/')) {
                        \Altum\Alerts::add_field_error('image', sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . 'products/'));
                    }
                }

                if(!\Altum\Alerts::has_field_errors()) {
                    /* Delete the previous image if exists */
                    if($product->image && file_exists(UPLOADS_PATH . 'products/' . $product->image)) {
                        unlink(UPLOADS_PATH . 'products/' . $product->image);
                    }

                    $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;

                    /* Generate the image thumbnail */
                    \Altum\Uploads::resize_image($_FILES['image']['tmp_name'], UPLOADS_PATH . 'products/' . $image_new_name, 500, 500, 100);

                    $image = $image_new_name;
                }
            }

            /* If there are no errors, continue */
            if(!\Altum\Alerts::has_field_errors() && !\Altum\Alerts::has_errors()) {

                /* Update to database */
                Database::update('products', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price'],
                    'image' => $image,
                    'digital_link' => $_POST['digital_link'],
                    'status' => $_POST['status']
                ], ['product_id' => $product_id]);

                /* Set a nice success message */
                \Altum\Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('products');

                redirect('products');
            }
        }

        /* Set default values */
        $values = [
            'name' => $_POST['name'] ?? $product->name,
            'description' => $_POST['description'] ?? $product->description,
            'price' => $_POST['price'] ?? $product->price,
            'digital_link' => $_POST['digital_link'] ?? $product->digital_link,
            'status' => $_POST['status'] ?? $product->status
        ];

        /* Prepare the view */
        $data = [
            'product' => $product,
            'values' => $values
        ];

        $view = new \Altum\Views\View('products/update', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and belongs to the user */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'user_id' => $this->user->user_id]);
        if(!$product) {
            redirect('products');
        }

        if(!empty($_POST)) {
            /* Delete the image if exists */
            if($product->image && file_exists(UPLOADS_PATH . 'products/' . $product->image)) {
                unlink(UPLOADS_PATH . 'products/' . $product->image);
            }

            /* Delete from database */
            Database::$database->query("DELETE FROM `products` WHERE `product_id` = '" . Database::clean_string($product_id) . "'");

            /* Set a nice success message */
            \Altum\Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $product->name . '</strong>'));

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('products');

            redirect('products');
        }

        /* Prepare the view */
        $data = [
            'product' => $product
        ];

        $view = new \Altum\Views\View('products/delete', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function view() {
        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and is active */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'status' => 1]);
        if(!$product) {
            redirect('');
        }

        /* Get product owner info */
        $product_owner = Database::get(['name', 'email'], 'users', ['user_id' => $product->user_id]);

        /* Increment views */
        Database::$database->query("UPDATE `products` SET `views` = `views` + 1 WHERE `product_id` = '" . Database::clean_string($product_id) . "'");

        /* Check if user has already purchased this product */
        $has_purchased = false;
        if($this->user) {
            $order = Database::get('*', 'orders', ['user_id' => $this->user->user_id, 'product_id' => $product_id, 'status' => 'completed']);
            $has_purchased = (bool) $order;
        }

        /* Prepare the view */
        $data = [
            'product' => $product,
            'product_owner' => $product_owner,
            'has_purchased' => $has_purchased
        ];

        $view = new \Altum\Views\View('products/view', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function catalog() {
        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['search'], ['datetime'], ['product_id', 'name', 'price', 'sales', 'views', 'datetime']));
        $filters->set_default_order_by('datetime', 'DESC');
        $filters->set_default_results_per_page(12);

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `products` WHERE `status` = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('products/catalog?' . $filters->get_get() . '&page=%d')));

        /* Get the products */
        $products_result = Database::$database->query("
            SELECT p.*, u.name as seller_name 
            FROM `products` p
            LEFT JOIN `users` u ON p.user_id = u.user_id
            WHERE p.status = 1 
            {$filters->get_sql_where()} 
            {$filters->get_sql_order_by()} 
            {$paginator->get_sql_limit()}
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $products[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'products' => $products,
            'total_products' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\Views\View('products/catalog', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}