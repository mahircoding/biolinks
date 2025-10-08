<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class TripaySettings extends Controller {

    public function index() {
        Authentication::guard();

        /* Migrate Tripay columns to users table */
        $this->migrate_tripay_columns();

        /* Account header */
        $menu = new \Altum\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        /* Get user's current Tripay settings */
        $tripay_settings = Database::get(['tripay_merchant_code', 'tripay_api_key_public', 'tripay_api_key_secret'], 'users', ['user_id' => $this->user->user_id]);

        if(!empty($_POST)) {
            if(!Csrf::check()) redirect('tripay-settings');

            $tripay_merchant_code = Database::clean_string($_POST['tripay_merchant_code'] ?? '');
            $tripay_api_key_public = Database::clean_string($_POST['tripay_api_key_public'] ?? '');
            $tripay_api_key_secret = Database::clean_string($_POST['tripay_api_key_secret'] ?? '');

            if(empty($_SESSION['error'])) {
                Database::update('users', [
                    'tripay_merchant_code' => $tripay_merchant_code,
                    'tripay_api_key_public' => $tripay_api_key_public,
                    'tripay_api_key_secret' => $tripay_api_key_secret
                ], ['user_id' => $this->user->user_id]);

                $_SESSION['success'][] = 'Tripay settings berhasil disimpan!';
                redirect('tripay-settings');
            }
        }

        $view = new \Altum\Views\View('tripay-settings/index', (array) $this);
        $this->add_view_content('content', $view->run(['tripay_settings' => $tripay_settings]));
    }

    private function migrate_tripay_columns() {
        /* Add Tripay columns to users table if they don't exist */
        @Database::$database->query("ALTER TABLE `users` ADD COLUMN `tripay_merchant_code` VARCHAR(255) NULL AFTER `phone`");
        @Database::$database->query("ALTER TABLE `users` ADD COLUMN `tripay_api_key_public` VARCHAR(255) NULL AFTER `tripay_merchant_code`");
        @Database::$database->query("ALTER TABLE `users` ADD COLUMN `tripay_api_key_secret` VARCHAR(255) NULL AFTER `tripay_api_key_public`");
        
        /* Add addon status columns to users table if they don't exist */
        @Database::$database->query("ALTER TABLE `users` ADD COLUMN `addon_digital_products` TINYINT(1) NOT NULL DEFAULT 0 AFTER `tripay_api_key_secret`");
        @Database::$database->query("ALTER TABLE `users` ADD COLUMN `addon_tripay` TINYINT(1) NOT NULL DEFAULT 0 AFTER `addon_digital_products`");
    }

}
