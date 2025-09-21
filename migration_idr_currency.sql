-- Migration script to update existing products and orders tables to use IDR currency
-- AND add guest checkout functionality
-- Run this script if you already have existing data

-- Update products table
ALTER TABLE `products` MODIFY COLUMN `price` decimal(15,0) NOT NULL DEFAULT 0 COMMENT 'Price in IDR (Rupiah)';

-- Update orders table  
ALTER TABLE `orders` MODIFY COLUMN `amount` decimal(15,0) NOT NULL COMMENT 'Amount in IDR (Rupiah)';

-- Add guest checkout fields to orders table
ALTER TABLE `orders` MODIFY COLUMN `user_id` int(11) DEFAULT NULL COMMENT 'NULL for guest orders';
ALTER TABLE `orders` ADD COLUMN `customer_name` varchar(255) DEFAULT NULL COMMENT 'Guest customer name' AFTER `amount`;
ALTER TABLE `orders` ADD COLUMN `customer_email` varchar(255) DEFAULT NULL COMMENT 'Guest customer email' AFTER `customer_name`;
ALTER TABLE `orders` ADD COLUMN `customer_phone` varchar(20) DEFAULT NULL COMMENT 'Guest customer phone' AFTER `customer_email`;
ALTER TABLE `orders` ADD INDEX `customer_email` (`customer_email`);

-- Update existing prices from decimal to integer (multiply by 100 if coming from USD cents, or set appropriate IDR values)
-- Uncomment and modify as needed:
-- UPDATE `products` SET `price` = `price` * 15000 WHERE `price` > 0; -- Example: convert from USD to IDR
-- UPDATE `orders` SET `amount` = `amount` * 15000 WHERE `amount` > 0; -- Example: convert from USD to IDR