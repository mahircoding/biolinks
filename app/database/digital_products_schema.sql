-- Create tables for digital product sales system

-- Table for digital products
CREATE TABLE `digital_products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `access_url` varchar(500) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `date` datetime NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for digital product orders
CREATE TABLE `digital_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL, -- Seller ID
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_whatsapp` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key constraints if needed
-- ALTER TABLE `digital_products` ADD CONSTRAINT `fk_digital_products_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
-- ALTER TABLE `digital_orders` ADD CONSTRAINT `fk_digital_orders_product` FOREIGN KEY (`product_id`) REFERENCES `digital_products` (`product_id`) ON DELETE CASCADE;
-- ALTER TABLE `digital_orders` ADD CONSTRAINT `fk_digital_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;