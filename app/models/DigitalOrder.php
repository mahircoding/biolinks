<?php

namespace Altum\Models;

use Altum\Database\Database;

class DigitalOrder {

    public static $table = 'digital_orders';

    public static function migrate() {
        $sql = "CREATE TABLE IF NOT EXISTS `" . self::$table . "` (
            `order_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `product_id` INT UNSIGNED NOT NULL,
            `buyer_name` VARCHAR(255) NOT NULL,
            `buyer_email` VARCHAR(255) NOT NULL,
            `buyer_phone` VARCHAR(64) NULL,
            `amount_cents` INT UNSIGNED NOT NULL,
            `currency` VARCHAR(8) NOT NULL DEFAULT 'USD',
            `download_token` VARCHAR(64) NOT NULL,
            `download_expires_at` DATETIME NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`order_id`),
            KEY `idx_product` (`product_id`),
            UNIQUE KEY `uniq_token` (`download_token`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        return Database::$database->query($sql);
    }

    public static function create($data) {
        return Database::insert(self::$table, $data);
    }

    public static function find_by_token($token) {
        return Database::get(['order_id','product_id','buyer_name','buyer_email','buyer_phone','amount_cents','currency','download_token','download_expires_at','created_at'], self::$table, ['download_token' => $token]);
    }
}


