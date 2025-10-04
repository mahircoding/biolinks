<?php

namespace Altum\Controllers;

use Altum\Controllers\Controller;
use Altum\Models\Product;
use Altum\Models\User;

class Store extends Controller {

    public function index() {

        /* Get the user from the URL */
        $username = $this->params[0] ?? null;

        if(!$username) {
            redirect();
        }

        /* Get the user data */
        $user = (new User())->get_by_username($username);

        if(!$user) {
            redirect();
        }

        /* Get the user's products */
        $products = (new Product())->get_active_products_by_user($user->id, 1, 12);

        /* Get user stats */
        $user_stats = [
            'total_products' => (new Product())->get_user_products_count($user->id),
            'total_sales' => (new Product())->get_user_sales_count($user->id),
            'total_revenue' => (new Product())->get_user_total_revenue($user->id)
        ];

        /* Set custom title */
        $this->title = $user->name . ' - ' . $this->language->store->title;

        /* Prepare the view */
        $data = [
            'user' => $user,
            'products' => $products,
            'user_stats' => $user_stats,
            'page' => isset($_GET['page']) ? (int) $_GET['page'] : 1,
            'max_items' => 12,
            'total_products' => $user_stats['total_products']
        ];

        $view = new \Altum\Views\View('store/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function products() {

        /* Get the user from the URL */
        $username = $this->params[0] ?? null;

        if(!$username) {
            redirect();
        }

        /* Get the user data */
        $user = (new User())->get_by_username($username);

        if(!$user) {
            redirect();
        }

        /* Get pagination */
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $max_items = 12;
        $offset = ($page - 1) * $max_items;

        /* Get the user's products */
        $products = (new Product())->get_user_products($user->id, $page, $max_items);

        /* Get total products for pagination */
        $total_products = (new Product())->get_user_products_count($user->id);
        $total_pages = ceil($total_products / $max_items);

        /* Get user stats */
        $user_stats = [
            'total_products' => $total_products,
            'total_sales' => (new Product())->get_user_sales_count($user->id),
            'total_revenue' => (new Product())->get_user_total_revenue($user->id)
        ];

        /* Set custom title */
        $this->title = $user->name . ' - ' . $this->language->store->products_title;

        /* Prepare the view */
        $data = [
            'user' => $user,
            'products' => $products,
            'user_stats' => $user_stats,
            'page' => $page,
            'max_items' => $max_items,
            'total_products' => $total_products,
            'total_pages' => $total_pages,
            'start_range' => $this->get_start_range($page, $total_pages),
            'end_range' => $this->get_end_range($page, $total_pages),
            'previous_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $total_pages ? $page + 1 : null
        ];

        $view = new \Altum\Views\View('store/products', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function product() {

        /* Get the user from the URL */
        $username = $this->params[0] ?? null;

        if(!$username) {
            redirect();
        }

        /* Get the user data */
        $user = (new User())->get_by_username($username);

        if(!$user) {
            redirect();
        }

        /* Get the product from the URL */
        $product_id = $this->params[1] ?? null;

        if(!$product_id) {
            redirect('store/' . $username);
        }

        /* Get the product data */
        $product = (new Product())->get_product_by_id_and_user_id($product_id, $user->id);

        if(!$product) {
            redirect('store/' . $username);
        }

        /* Increment product views */
        (new Product())->increment_views($product_id);

        /* Log the view */
        (new Product())->log_activity($product_id, null, 'view');

        /* Set custom title */
        $this->title = $product['name'] . ' - ' . $user->name;

        /* Prepare the view */
        $data = [
            'user' => $user,
            'product' => $product
        ];

        $view = new \Altum\Views\View('store/product', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    private function get_start_range($page, $total_pages) {
        $range = 3;
        
        if($total_pages <= 7) {
            return 1;
        }
        
        if($page <= 4) {
            return 1;
        }
        
        if($page > $total_pages - 3) {
            return $total_pages - 6;
        }
        
        return $page - $range;
    }

    private function get_end_range($page, $total_pages) {
        $range = 3;
        
        if($total_pages <= 7) {
            return $total_pages;
        }
        
        if($page <= 4) {
            return 7;
        }
        
        if($page > $total_pages - 3) {
            return $total_pages;
        }
        
        return $page + $range;
    }
}