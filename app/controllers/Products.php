<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Product;
use Altum\Models\User;
use Altum\Routing\Router;

class Products extends Controller {

    public function index() {
        Authentication::guard();

        /* Pagination */
        $total_rows = Database::simple_get('COUNT(*)', 'products', ['user_id' => $this->user->user_id]);
        
        /* Simple pagination without Paginator class */
        $page = $_GET['page'] ?? 1;
        $per_page = 25;
        $offset = ($page - 1) * $per_page;
        
        /* Get products */
        $products_result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = {$this->user->user_id} 
            ORDER BY `datetime` DESC 
            LIMIT {$offset}, {$per_page}
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $products[] = $row;
        }

        /* Get user stats */
        $product_model = new Product();
        $stats = $product_model->get_user_stats($this->user->user_id);

        /* Prepare the View */
        $data = [
            'products' => $products,
            'stats' => $stats,
            'total_rows' => $total_rows,
            'page' => $page,
            'per_page' => $per_page
        ];

        $view = new \Altum\Views\View('products/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function create() {
        Authentication::guard();

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['name'] = Database::clean_string($_POST['name']);
            $_POST['description'] = Database::clean_string($_POST['description']);
            $_POST['price'] = (int) $_POST['price'];
            $_POST['digital_link'] = Database::clean_string($_POST['digital_link']);

            /* Image upload */
            $image = null;
            if(!empty($_FILES['image']['name'])) {
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $file_extension = explode('.', $_FILES['image']['name']);
                $file_extension = strtolower(end($file_extension));

                if(in_array($file_extension, $allowed_extensions)) {
                    $image = md5(time() . rand()) . '.' . $file_extension;
                    $upload_path = UPLOADS_PATH . 'products/' . $image;

                    /* Create directory if not exists */
                    if(!is_dir(UPLOADS_PATH . 'products/')) {
                        mkdir(UPLOADS_PATH . 'products/', 0755, true);
                    }

                    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
                }
            }

            /* Insert to database */
            $product_model = new Product();
            $product_id = $product_model->create($this->user->user_id, [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'image' => $image,
                'digital_link' => $_POST['digital_link'],
                'status' => 1
            ]);

            /* Set a nice success message */
            $_SESSION['success'][] = 'Produk berhasil dibuat: ' . $_POST['name'];

            redirect('products');
        }

        /* Prepare the View */
        $view = new \Altum\Views\View('products/create', (array) $this);
        $this->add_view_content('content', $view->run());
    }

    public function update() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and belongs to user */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'user_id' => $this->user->user_id]);

        if(!$product) {
            redirect('products');
        }

        $product->settings = json_decode($product->settings);

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['name'] = Database::clean_string($_POST['name']);
            $_POST['description'] = Database::clean_string($_POST['description']);
            $_POST['price'] = (int) $_POST['price'];
            $_POST['digital_link'] = Database::clean_string($_POST['digital_link']);
            $_POST['status'] = (int) $_POST['status'];

            $update_data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'digital_link' => $_POST['digital_link'],
                'status' => $_POST['status']
            ];

            /* Image upload */
            if(!empty($_FILES['image']['name'])) {
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $file_extension = explode('.', $_FILES['image']['name']);
                $file_extension = strtolower(end($file_extension));

                if(in_array($file_extension, $allowed_extensions)) {
                    $image = md5(time() . rand()) . '.' . $file_extension;
                    $upload_path = UPLOADS_PATH . 'products/' . $image;

                    /* Create directory if not exists */
                    if(!is_dir(UPLOADS_PATH . 'products/')) {
                        mkdir(UPLOADS_PATH . 'products/', 0755, true);
                    }

                    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);

                    /* Delete old image */
                    if($product->image && file_exists(UPLOADS_PATH . 'products/' . $product->image)) {
                        unlink(UPLOADS_PATH . 'products/' . $product->image);
                    }

                    $update_data['image'] = $image;
                }
            }

            /* Update database */
            $product_model = new Product();
            $product_model->update($product_id, $update_data);

            /* Set a nice success message */
            $_SESSION['success'][] = 'Produk berhasil diperbarui: ' . $_POST['name'];

            redirect('products');
        }

        /* Prepare the View */
        $data = [
            'product' => $product
        ];

        $view = new \Altum\Views\View('products/update', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {
        Authentication::guard();

        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and belongs to user */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'user_id' => $this->user->user_id]);

        if(!$product) {
            redirect('products');
        }

        if(!empty($_POST)) {
            /* Delete the image */
            if($product->image && file_exists(UPLOADS_PATH . 'products/' . $product->image)) {
                unlink(UPLOADS_PATH . 'products/' . $product->image);
            }

            /* Delete from database */
            $product_model = new Product();
            $product_model->delete($product_id);

            /* Set a nice success message */
            $_SESSION['success'][] = 'Produk berhasil dihapus';

            redirect('products');
        }

        /* Prepare the View */
        $data = [
            'product' => $product
        ];

        $view = new \Altum\Views\View('products/delete', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function catalog() {
        /* Pagination */
        $total_rows = Database::simple_get('COUNT(*)', 'products', ['status' => 1]);
        $paginator = (new \Altum\Paginator($total_rows, 12, $_GET['page'] ?? 1, url('products/catalog?page=%d')));

        /* Search functionality */
        $search = $_GET['search'] ?? '';
        $where_clause = "`status` = 1";
        
        if(!empty($search)) {
            $search = Database::clean_string($search);
            $where_clause .= " AND (`name` LIKE '%{$search}%' OR `description` LIKE '%{$search}%')";
        }

        /* Get products */
        $products_result = Database::$database->query("
            SELECT p.*, u.name as seller_name 
            FROM `products` p
            LEFT JOIN `users` u ON p.user_id = u.user_id
            WHERE {$where_clause}
            ORDER BY `datetime` DESC 
            {$paginator->get_sql_limit()}
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $products[] = $row;
        }

        /* Prepare the View */
        $data = [
            'products' => $products,
            'paginator' => $paginator,
            'search' => $search
        ];

        $view = new \Altum\Views\View('products/catalog', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function view() {
        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Get product */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'status' => 1]);

        if(!$product) {
            redirect('products/catalog');
        }

        $product->settings = json_decode($product->settings);

        /* Get seller info */
        $seller = Database::get(['name', 'email'], 'users', ['user_id' => $product->user_id]);

        /* Increment views */
        $product_model = new Product();
        $product_model->increment_views($product_id);

        /* Check if current user already purchased this product */
        $already_purchased = false;
        if($this->user) {
            $order_model = new \Altum\Models\Order();
            $already_purchased = $order_model->check_customer_purchased($this->user->email, $product_id);
        }

        /* Prepare the View */
        $data = [
            'product' => $product,
            'seller' => $seller,
            'already_purchased' => $already_purchased
        ];

        $view = new \Altum\Views\View('products/view', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }

    public function store() {
        $username = isset($this->params[0]) ? $this->params[0] : null;

        /* Get user by username */
        $user = Database::get(['user_id', 'name', 'email'], 'users', ['name' => $username]);

        if(!$user) {
            redirect('products/catalog');
        }

        /* Get user's active products */
        $products_result = Database::$database->query("
            SELECT * FROM `products` 
            WHERE `user_id` = {$user->user_id} AND `status` = 1
            ORDER BY `datetime` DESC
        ");

        $products = [];
        while($row = $products_result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $products[] = $row;
        }

        /* Get user stats */
        $product_model = new Product();
        $stats = $product_model->get_user_stats($user->user_id);

        /* Prepare the View */
        $data = [
            'store_user' => $user,
            'products' => $products,
            'stats' => $stats
        ];

        $view = new \Altum\Views\View('products/store', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}