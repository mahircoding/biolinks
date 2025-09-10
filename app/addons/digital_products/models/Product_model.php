<?php
namespace Altum\Addons\DigitalProducts\Models;

class Product_model {
    public function get_by_user($user_id) {
        return database()->where('user_id', $user_id)->get('products');
    }

    public function get_by_slug($slug) {
        return database()->where('slug', $slug)->getOne('products');
    }
}
