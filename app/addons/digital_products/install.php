<?php
/**
 * Install script untuk addon Digital Products
 */

$db = database();

/* Buat tabel products kalau belum ada */
$db->rawQuery("
    CREATE TABLE IF NOT EXISTS `products` (
        `product_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT UNSIGNED NOT NULL,
        `name` VARCHAR(191) NOT NULL,
        `description` TEXT NULL,
        `price` DECIMAL(10,2) NOT NULL DEFAULT 0,
        `file_url` TEXT NULL,
        `slug` VARCHAR(191) UNIQUE,
        `created_at` DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

/* Buat tabel orders kalau belum ada */
$db->rawQuery("
    CREATE TABLE IF NOT EXISTS `orders` (
        `order_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `product_id` INT UNSIGNED NOT NULL,
        `buyer_email` VARCHAR(191) NOT NULL,
        `buyer_name` VARCHAR(191) NOT NULL,
        `status` ENUM('pending','paid','failed') DEFAULT 'pending',
        `download_token` VARCHAR(255) NULL,
        `download_expires` DATETIME NULL,
        `created_at` DATETIME NOT NULL,
        CONSTRAINT `fk_orders_products` FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`) ON DELETE CASCADE
    ) ENGINE=In
