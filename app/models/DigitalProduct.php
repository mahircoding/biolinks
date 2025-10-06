<?php

namespace Altum\Models;

use Altum\Database\Database;

class DigitalProduct {

    public static $table = 'digital_products';

    public static function migrate() {
        $sql = "CREATE TABLE IF NOT EXISTS `" . self::$table . "` (
            `product_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` INT UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `description` TEXT NULL,
            `price_cents` INT UNSIGNED NOT NULL DEFAULT 0,
            `currency` VARCHAR(8) NOT NULL DEFAULT 'USD',
            `file_path` VARCHAR(512) NULL,
            `access_url` VARCHAR(1024) NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`product_id`),
            UNIQUE KEY `uniq_slug` (`slug`),
            KEY `idx_user` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $ok = Database::$database->query($sql);

        /* Ensure new column exists if upgrading from older schema */
        @Database::$database->query("ALTER TABLE `" . self::$table . "` ADD COLUMN `access_url` VARCHAR(1024) NULL AFTER `file_path`");

        return $ok;
    }

    public static function find_by_slug($slug) {
        return Database::get(['product_id','user_id','name','slug','description','price_cents','currency','file_path','access_url'], self::$table, ['slug' => $slug]);
    }

    public static function find_by_id($product_id) {
        return Database::get(['product_id','user_id','name','slug','description','price_cents','currency','file_path','access_url'], self::$table, ['product_id' => $product_id]);
    }

    public static function list_by_user($user_id) {
        $result = Database::$database->query("SELECT * FROM `" . self::$table . "` WHERE `user_id` = '" . Database::clean_string($user_id) . "' ORDER BY `product_id` DESC");
        $rows = [];
        while($row = $result->fetch_object()) $rows[] = $row;
        return $rows;
    }

    public static function create($data) {
        return Database::insert(self::$table, $data);
    }

    public static function update_by_id($product_id, $data) {
        return Database::update(self::$table, $data, ['product_id' => $product_id]);
    }

    public static function delete_by_id($product_id, $user_id) {
        $product_id = Database::clean_string($product_id);
        $user_id = Database::clean_string($user_id);
        return Database::$database->query("DELETE FROM `" . self::$table . "` WHERE `product_id` = '{$product_id}' AND `user_id` = '{$user_id}'");
    }
}


