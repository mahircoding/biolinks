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
            `status` VARCHAR(32) NOT NULL DEFAULT 'pending',
            `tripay_reference` VARCHAR(64) NULL,
            `payment_channel` VARCHAR(64) NULL,
            `paid_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`order_id`),
            KEY `idx_product` (`product_id`),
            UNIQUE KEY `uniq_token` (`download_token`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $ok = Database::$database->query($sql);

        /* Upgrade columns if table exists */
        @Database::$database->query("ALTER TABLE `" . self::$table . "` ADD COLUMN `status` VARCHAR(32) NOT NULL DEFAULT 'pending' AFTER `download_expires_at`");
        @Database::$database->query("ALTER TABLE `" . self::$table . "` ADD COLUMN `tripay_reference` VARCHAR(64) NULL AFTER `status`");
        @Database::$database->query("ALTER TABLE `" . self::$table . "` ADD COLUMN `payment_channel` VARCHAR(64) NULL AFTER `tripay_reference`");
        @Database::$database->query("ALTER TABLE `" . self::$table . "` ADD COLUMN `paid_at` DATETIME NULL AFTER `payment_channel`");

        return $ok;
    }

    public static function create($data) {
        return Database::insert(self::$table, $data);
    }

    public static function find_by_token($token) {
        return Database::get(['order_id','product_id','buyer_name','buyer_email','buyer_phone','amount_cents','currency','download_token','download_expires_at','created_at'], self::$table, ['download_token' => $token]);
    }

    public static function find_by_reference($reference) {
        return Database::get(['order_id','product_id','buyer_name','buyer_email','buyer_phone','amount_cents','currency','download_token','download_expires_at','status','tripay_reference','payment_channel','paid_at','created_at'], self::$table, ['tripay_reference' => $reference]);
    }

    public static function mark_paid($order_id, $channel) {
        return Database::update(self::$table, [
            'status' => 'paid',
            'payment_channel' => $channel,
            'paid_at' => date('Y-m-d H:i:s')
        ], [ 'order_id' => $order_id ]);
    }
}


