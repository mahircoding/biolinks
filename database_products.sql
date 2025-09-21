-- Tabel untuk menyimpan produk digital
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `digital_link` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  `views` int(11) NOT NULL DEFAULT 0,
  `sales` int(11) NOT NULL DEFAULT 0,
  `settings` text DEFAULT NULL COMMENT 'JSON settings',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `datetime` (`datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk menyimpan order/transaksi
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(64) NOT NULL,
  `transaction_id` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` varchar(64) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'midtrans',
  `status` enum('pending','processing','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_details` text DEFAULT NULL COMMENT 'JSON payment details from gateway',
  `settings` text DEFAULT NULL COMMENT 'JSON additional settings',
  `datetime` datetime NOT NULL,
  `completed_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `status` (`status`),
  KEY `datetime` (`datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk menyimpan log aktivitas produk
CREATE TABLE IF NOT EXISTS `product_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(64) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL COMMENT 'view, purchase, download, etc',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `additional_data` text DEFAULT NULL COMMENT 'JSON additional data',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`),
  KEY `datetime` (`datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk menyimpan kategori produk (opsional)
CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk menghubungkan produk dengan kategori
CREATE TABLE IF NOT EXISTS `product_category_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(64) NOT NULL,
  `category_id` varchar(64) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_category` (`product_id`, `category_id`),
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default categories
INSERT INTO `product_categories` (`category_id`, `name`, `description`, `status`, `sort_order`, `datetime`) VALUES
('ebook', 'E-Book', 'Digital books and publications', 1, 1, NOW()),
('course', 'Online Course', 'Educational courses and tutorials', 1, 2, NOW()),
('software', 'Software', 'Digital software and applications', 1, 3, NOW()),
('template', 'Template', 'Design templates and themes', 1, 4, NOW()),
('audio', 'Audio', 'Music, podcasts, and audio content', 1, 5, NOW()),
('video', 'Video', 'Video content and tutorials', 1, 6, NOW()),
('other', 'Other', 'Other digital products', 1, 99, NOW());