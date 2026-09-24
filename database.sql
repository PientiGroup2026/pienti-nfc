CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `title` varchar(150) NOT NULL,
  `department` varchar(100) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `location_url` varchar(500) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default_profile.jpg',
  `hotel_name` varchar(150) NOT NULL DEFAULT 'Pienti Group',
  `hotel_address` text DEFAULT NULL,
  `social_linkedin` varchar(255) DEFAULT NULL,
  `social_instagram` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `show_balloons` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Test Datası:
INSERT INTO `employees` (`slug`, `first_name`, `last_name`, `title`, `department`, `phone`, `mobile`, `email`, `website`, `extension`, `whatsapp`, `location_url`, `hotel_name`, `hotel_address`, `social_linkedin`, `social_instagram`) VALUES
('fb-manager', 'Ahmet', 'Yılmaz', 'Food & Beverage Manager', 'F&B Department', '+90 212 555 0000', '+90 532 123 4567', 'ahmet.yilmaz@premiumhotel.com', 'www.premiumhotel.com', '1205', '905321234567', 'https://maps.google.com/?q=istanbul', 'Premium Hotel Istanbul', 'Beşiktaş, Istanbul, Turkey', 'https://linkedin.com/', 'https://instagram.com/'),
('front-office-manager', 'Ayşe', 'Kaya', 'Front Office Manager', 'Rooms Division', '+90 212 555 0000', '+90 533 987 6543', 'ayse.kaya@premiumhotel.com', 'www.premiumhotel.com', '101', '905339876543', 'https://maps.google.com/?q=istanbul', 'Premium Hotel Istanbul', 'Beşiktaş, Istanbul, Turkey', 'https://linkedin.com/', 'https://instagram.com/');
