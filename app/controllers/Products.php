<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
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

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {
            
            switch($_POST['request_type']) {
                case 'create':
                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['price'] = (int) $_POST['price'];
                    $_POST['category'] = Database::clean_string($_POST['category']);
                    $_POST['digital_link'] = isset($_POST['file']) ? Database::clean_string($_POST['file']) : '';

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
                        'name' => $_POST['title'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'image' => $image,
                        'digital_link' => $_POST['digital_link'],
                        'category' => $_POST['category'],
                        'status' => 1
                    ]);

                    /* Set a nice success message */
                    $_SESSION['success'][] = 'Produk berhasil dibuat: ' . $_POST['title'];

                    redirect('products');
                    break;
            }
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

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {
            
            switch($_POST['request_type']) {
                case 'update':
                    /* Clean some posted variables */
                    $_POST['title'] = Database::clean_string($_POST['title']);
                    $_POST['description'] = Database::clean_string($_POST['description']);
                    $_POST['price'] = (int) $_POST['price'];
                    $_POST['category'] = Database::clean_string($_POST['category']);
                    $_POST['digital_link'] = Database::clean_string($_POST['digital_link']);
                    $_POST['is_enabled'] = isset($_POST['is_enabled']) ? 1 : 0;

                    $update_data = [
                        'name' => $_POST['title'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'category' => $_POST['category'],
                        'digital_link' => $_POST['digital_link'],
                        'is_enabled' => $_POST['is_enabled']
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
                    $_SESSION['success'][] = 'Produk berhasil diperbarui: ' . $_POST['title'];

                    redirect('products/' . $product_id);
                    break;
            }
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

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {
            
            switch($_POST['request_type']) {
                case 'delete':
                    /* Validate confirmation */
                    if(!isset($_POST['confirmation']) || strtoupper($_POST['confirmation']) !== 'DELETE') {
                        $_SESSION['error'][] = 'Please type "DELETE" to confirm deletion.';
                        redirect('products/' . $product_id . '/delete');
                    }

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
                    break;
            }
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
        Authentication::guard();

        $product_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Check if product exists and belongs to user */
        $product = Database::get('*', 'products', ['product_id' => $product_id, 'user_id' => $this->user->user_id]);

        if(!$product) {
            redirect('products');
        }

        $product->settings = json_decode($product->settings);

        /* Increment views */
        $product_model = new Product();
        $product_model->increment_views($product_id);

        /* Prepare the View */
        $data = [
            'product' => $product
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