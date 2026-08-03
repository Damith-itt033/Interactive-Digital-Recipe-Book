-- Database creation script for Savory Share (Phase 2)

CREATE DATABASE IF NOT EXISTS `feastify_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `feastify_db`;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `categories`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(50) NOT NULL UNIQUE,
  `icon` VARCHAR(50) DEFAULT 'fa-solid fa-utensils'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Categories
INSERT INTO `categories` (`name`, `slug`, `icon`) VALUES
('Breakfast', 'breakfast', 'fa-solid fa-mug-hot'),
('Lunch', 'lunch', 'fa-solid fa-burger'),
('Dinner', 'dinner', 'fa-solid fa-bell-concierge'),
('Dessert', 'dessert', 'fa-solid fa-ice-cream'),
('Vegetarian', 'vegetarian', 'fa-solid fa-leaf')
ON DUPLICATE KEY UPDATE name=name;