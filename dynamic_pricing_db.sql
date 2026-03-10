-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 03:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dynamic_pricing_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_daily_revenue` (IN `p_seller_id` INT, IN `p_date` DATE)   BEGIN
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as order_count,
        SUM(total_amount) as daily_revenue,
        COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as paid_orders
    FROM orders
    WHERE seller_id = p_seller_id AND DATE(created_at) = p_date
    GROUP BY DATE(created_at);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_low_stock_products` (IN `p_seller_id` INT)   BEGIN
    SELECT p.*, i.quantity_available, i.low_stock_threshold
    FROM products p
    INNER JOIN inventory i ON p.product_id = i.product_id
    WHERE p.seller_id = p_seller_id 
    AND p.is_active = 1
    AND i.quantity_available <= i.low_stock_threshold
    ORDER BY i.quantity_available ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_seller_stats` (IN `p_seller_id` INT)   BEGIN
    SELECT 
        sp.seller_id,
        sp.business_name,
        COUNT(DISTINCT p.product_id) as total_products,
        COUNT(DISTINCT o.order_id) as total_orders,
        COALESCE(SUM(o.total_amount), 0) as total_revenue,
        COALESCE(AVG(sr.rating), 0) as average_rating,
        COUNT(sr.rating_id) as review_count
    FROM seller_profiles sp
    LEFT JOIN products p ON sp.seller_id = p.seller_id AND p.is_active = 1
    LEFT JOIN orders o ON sp.seller_id = o.seller_id AND o.payment_status = 'paid'
    LEFT JOIN seller_ratings sr ON sp.seller_id = sr.seller_id
    WHERE sp.seller_id = p_seller_id
    GROUP BY sp.seller_id, sp.business_name;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `rate_id` int(11) NOT NULL,
  `from_currency` varchar(3) NOT NULL,
  `to_currency` varchar(3) NOT NULL,
  `rate` decimal(10,6) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`rate_id`, `from_currency`, `to_currency`, `rate`, `last_updated`) VALUES
(1, 'NGN', 'USD', 0.001300, '2025-10-12 17:31:26'),
(2, 'NGN', 'EUR', 0.001200, '2025-10-12 17:31:26'),
(3, 'NGN', 'GBP', 0.001000, '2025-10-12 17:31:26'),
(4, 'USD', 'NGN', 765.000000, '2025-10-12 17:31:26'),
(5, 'EUR', 'NGN', 830.000000, '2025-10-12 17:31:26'),
(6, 'GBP', 'NGN', 970.000000, '2025-10-12 17:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_available` int(11) DEFAULT 0,
  `quantity_reserved` int(11) DEFAULT 0,
  `reorder_point` int(11) DEFAULT 10,
  `low_stock_threshold` int(11) DEFAULT 20,
  `high_stock_threshold` int(11) DEFAULT 100,
  `last_restocked` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `product_id`, `quantity_available`, `quantity_reserved`, `reorder_point`, `low_stock_threshold`, `high_stock_threshold`, `last_restocked`, `created_at`, `updated_at`) VALUES
(1, 1, 199, 0, 10, 7, 100, '2026-03-10 00:40:08', '2025-10-26 16:24:58', '2026-03-10 00:40:08'),
(2, 2, 99, 0, 10, 10, 100, '2026-03-10 00:40:08', '2025-10-29 23:21:36', '2026-03-10 00:40:08'),
(3, 3, 40, 0, 10, 3, 100, '2025-11-03 16:43:57', '2025-11-03 16:43:05', '2025-11-03 16:43:57'),
(4, 4, 30, 0, 10, 20, 100, '2025-11-03 17:00:43', '2025-11-03 16:58:33', '2025-11-03 17:00:43'),
(5, 5, 150, 0, 10, 15, 100, '2025-11-03 17:03:59', '2025-11-03 17:03:11', '2025-11-03 17:03:59'),
(6, 6, 160, 0, 10, 20, 500, '2025-11-03 17:15:53', '2025-11-03 17:14:29', '2025-11-03 17:15:53'),
(7, 7, 500, 0, 10, 30, 500, '2025-11-03 17:32:34', '2025-11-03 17:22:03', '2025-11-03 17:32:34'),
(8, 8, 200, 0, 10, 50, 500, '2025-11-03 17:36:20', '2025-11-03 17:34:23', '2025-11-03 17:36:20'),
(9, 9, 10, 0, 10, 20, 500, '2026-03-10 12:05:04', '2026-03-09 16:03:51', '2026-03-10 12:05:04'),
(10, 10, 897, 0, 10, 15, 500, '2026-03-10 13:40:06', '2026-03-09 17:12:47', '2026-03-10 13:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('order','payment','inventory','price_change','system') NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `is_sent` tinyint(1) DEFAULT 0,
  `scheduled_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `type`, `title`, `message`, `is_read`, `is_sent`, `scheduled_at`, `sent_at`, `created_at`) VALUES
(5, 13, 'price_change', 'Significant Price decreased - Bluetooth speaker JBL', 'Price for product \'Bluetooth speaker JBL\' has decreased by 15.4% (from 2000.00 to 1692.19). This change was triggered by our dynamic pricing system.', 0, 0, '2026-03-09 16:53:06', NULL, '2026-03-09 16:53:06'),
(6, 13, 'price_change', 'Significant Price decreased - Bread', 'Price for product \'Bread\' has decreased by 13.2% (from 19000.00 to 16500.00). This change was triggered by our dynamic pricing system.', 0, 0, '2026-03-09 17:19:22', NULL, '2026-03-09 17:19:22'),
(8, 13, 'price_change', 'Significant Price decreased - Bread', 'Price for product \'Bread\' has decreased by 90.3% (from 170000.00 to 16500.00). This change was triggered by our dynamic pricing system.', 0, 0, '2026-03-09 23:43:23', NULL, '2026-03-09 23:43:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','confirmed','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_number`, `buyer_id`, `seller_id`, `total_amount`, `order_status`, `payment_status`, `payment_method`, `shipping_address`, `cancellation_reason`, `paid_at`, `cancelled_at`, `created_at`, `updated_at`) VALUES
(5, 'ORD-8682FEDD', 19, 12, 1199.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-03-10 00:40:08', '2026-03-10 00:40:08'),
(6, 'ORD-8683A23F', 19, 13, 16500.00, 'cancelled', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-03-10 00:40:08', '2026-03-10 08:34:38'),
(7, 'ORD-D2C34825', 19, 13, 34709.29, 'delivered', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-03-10 02:08:44', '2026-03-10 08:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`) VALUES
(5, 5, 2, 1, 99.00, 99.00, '2026-03-10 00:40:08'),
(6, 5, 1, 1, 1100.00, 1100.00, '2026-03-10 00:40:08'),
(7, 6, 10, 1, 16500.00, 16500.00, '2026-03-10 00:40:08'),
(8, 7, 10, 2, 16500.00, 33000.00, '2026-03-10 02:08:44'),
(9, 7, 9, 1, 1709.29, 1709.29, '2026-03-10 02:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pricing_history`
--

CREATE TABLE `pricing_history` (
  `pricing_history_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `price_change_percent` decimal(5,2) DEFAULT NULL,
  `change_reason` varchar(255) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pricing_history`
--

INSERT INTO `pricing_history` (`pricing_history_id`, `product_id`, `old_price`, `new_price`, `price_change_percent`, `change_reason`, `changed_at`) VALUES
(1, 1, 20000.00, 10500.00, -47.50, 'Manual update', '2025-10-29 23:17:08'),
(2, 1, 1200.00, 1136.12, -5.32, 'Low stock adjustment (Stock: 1 of 7 units) - Price decreased by 5.3%', '2025-10-30 00:23:12'),
(3, 1, 1136.12, 1100.00, -3.18, 'Normal stock level adjustment (49 units) - Price decreased by 3.2%', '2025-10-30 00:23:12'),
(4, 2, 105.00, 101.92, -2.93, 'Low stock adjustment (Stock: 2 of 10 units) - Price decreased by 2.9%', '2025-10-30 00:24:04'),
(5, 2, 101.92, 99.00, -2.86, 'Normal stock level adjustment (40 units) - Price decreased by 2.9%', '2025-10-30 00:24:04'),
(6, 1, 1100.00, 1100.00, 0.00, 'Low stock adjustment (Stock: 5 of 7 units) - Price decreased by 0.0%', '2025-10-30 08:31:09'),
(7, 1, 1100.00, 1100.00, 0.00, 'Low stock adjustment (Stock: 5 of 7 units) - Price decreased by 0.0%', '2025-10-30 08:31:37'),
(8, 1, 1100.00, 1124.49, 2.23, 'Low stock adjustment (Stock: 2 of 7 units) - Price increased by 2.2%', '2025-10-30 08:31:37'),
(9, 1, 1124.49, 1124.49, 0.00, 'Low stock adjustment (Stock: 2 of 7 units) - Price decreased by 0.0%', '2025-11-03 16:33:28'),
(10, 1, 1124.49, 1100.00, -2.18, 'Normal stock level adjustment (14 units) - Price decreased by 2.2%', '2025-11-03 16:33:28'),
(11, 1, 1100.00, 1124.49, 2.23, 'Low stock adjustment (Stock: 2 of 7 units) - Price increased by 2.2%', '2025-11-03 16:39:26'),
(12, 1, 1124.49, 1124.49, 0.00, 'Low stock adjustment (Stock: 2 of 7 units) - Price decreased by 0.0%', '2025-11-03 16:40:03'),
(13, 1, 1124.49, 1100.00, -2.18, 'High stock adjustment (Stock: 200 exceeds 100 units) - Price decreased by 2.2%', '2025-11-03 16:40:03'),
(14, 2, 99.00, 102.43, 3.46, 'Low stock adjustment (Stock: 1 of 10 units) - Price increased by 3.5%', '2025-11-03 16:41:14'),
(15, 2, 102.43, 102.43, 0.00, 'Low stock adjustment (Stock: 1 of 10 units) - Price decreased by 0.0%', '2025-11-03 16:41:34'),
(16, 2, 102.43, 99.00, -3.35, 'High stock adjustment (Stock: 100 exceeds 100 units) - Price decreased by 3.3%', '2025-11-03 16:41:34'),
(17, 3, 200.00, 110.00, -45.00, 'Normal stock level adjustment (20 units) - Price decreased by 45.0%', '2025-11-03 16:43:37'),
(18, 3, 110.00, 110.00, 0.00, 'Low stock adjustment (Stock: 2 of 3 units) - Price decreased by 0.0%', '2025-11-03 16:43:57'),
(19, 4, 110.00, 110.00, 0.00, 'High stock adjustment (Stock: 100 exceeds 100 units) - Price decreased by 0.0%', '2025-11-03 16:59:54'),
(20, 4, 110.00, 113.57, 3.25, 'Low stock adjustment (Stock: 3 of 20 units) - Price increased by 3.2%', '2025-11-03 16:59:54'),
(21, 4, 113.57, 119.25, 5.00, 'Low stock adjustment (Stock: 3 of 20 units) - Price increased by 5.0%', '2025-11-03 17:00:43'),
(22, 4, 119.25, 110.00, -7.76, 'Normal stock level adjustment (30 units) - Price decreased by 7.8%', '2025-11-03 17:00:43'),
(23, 5, 7500.00, 5500.00, -26.67, 'High stock adjustment (Stock: 100 exceeds 100 units) - Price decreased by 26.7%', '2025-11-03 17:03:43'),
(24, 5, 5500.00, 5701.27, 3.66, 'Low stock adjustment (Stock: 8 of 15 units) - Price increased by 3.7%', '2025-11-03 17:03:43'),
(25, 5, 5701.27, 5701.27, 0.00, 'Low stock adjustment (Stock: 8 of 15 units) - Price decreased by 0.0%', '2025-11-03 17:03:59'),
(26, 5, 5701.27, 5500.00, -3.53, 'High stock adjustment (Stock: 150 exceeds 100 units) - Price decreased by 3.5%', '2025-11-03 17:03:59'),
(27, 6, 5000.00, 2750.00, -45.00, 'Normal stock level adjustment (50 units) - Price decreased by 45.0%', '2025-11-03 17:15:21'),
(28, 6, 2750.00, 2961.33, 7.68, 'Low stock adjustment (Stock: 5 of 20 units) - Price increased by 7.7%', '2025-11-03 17:15:21'),
(29, 6, 2961.33, 2961.33, 0.00, 'Low stock adjustment (Stock: 5 of 20 units) - Price decreased by 0.0%', '2025-11-03 17:15:53'),
(30, 6, 2961.33, 2750.00, -7.14, 'Normal stock level adjustment (160 units) - Price decreased by 7.1%', '2025-11-03 17:15:53'),
(31, 7, 16000.00, 12000.00, -25.00, 'Normal stock level adjustment (120 units) - Price decreased by 25.0%', '2025-11-03 17:22:46'),
(32, 7, 12000.00, 11471.25, -4.41, 'Low stock adjustment (Stock: 15 of 30 units) - Price decreased by 4.4%', '2025-11-03 17:31:34'),
(33, 7, 11471.25, 11000.00, -4.11, 'Low stock adjustment (Stock: 30 of 30 units) - Price decreased by 4.1%', '2025-11-03 17:31:34'),
(34, 7, 11000.00, 11748.33, 6.80, 'Low stock adjustment (Stock: 10 of 30 units) - Price increased by 6.8%', '2025-11-03 17:32:14'),
(35, 7, 11748.33, 11748.33, 0.00, 'Low stock adjustment (Stock: 10 of 30 units) - Price decreased by 0.0%', '2025-11-03 17:32:34'),
(36, 7, 11748.33, 11000.00, -6.37, 'High stock adjustment (Stock: 500 exceeds 500 units) - Price decreased by 6.4%', '2025-11-03 17:32:34'),
(37, 8, 4400.00, 4660.32, 5.92, 'Low stock adjustment (Stock: 20 of 50 units) - Price increased by 5.9%', '2025-11-03 17:35:46'),
(38, 8, 4660.32, 4660.32, 0.00, 'Low stock adjustment (Stock: 20 of 50 units) - Price decreased by 0.0%', '2025-11-03 17:36:20'),
(39, 8, 4660.32, 4400.00, -5.59, 'Normal stock level adjustment (200 units) - Price decreased by 5.6%', '2025-11-03 17:36:20'),
(40, 8, 4400.00, 12000.00, 172.73, 'Manual update', '2025-12-15 09:36:57'),
(41, 9, 2000.00, 1692.19, -15.39, 'Low stock adjustment (Stock: 5 of 20 units) - Price decreased by 15.4%', '2026-03-09 16:53:06'),
(42, 9, 1692.19, 1650.00, -2.49, 'Normal stock level adjustment (100 units) - Price decreased by 2.5%', '2026-03-09 17:07:22'),
(43, 9, 1650.00, 1776.80, 7.68, 'Low stock adjustment (Stock: 5 of 20 units) - Price increased by 7.7%', '2026-03-09 17:08:13'),
(44, 9, 1776.80, 1794.75, 1.01, 'Low stock adjustment (Stock: 1 of 20 units) - Price increased by 1.0%', '2026-03-09 17:08:34'),
(45, 10, 19000.00, 16500.00, -13.16, 'Low stock adjustment (Stock: 13 of 15 units) - Price decreased by 13.2%', '2026-03-09 17:19:22'),
(46, 10, 16500.00, 16500.00, 0.00, 'High stock adjustment (Stock: 1000 exceeds 500 units) - Price decreased by 0.0%', '2026-03-09 17:28:22'),
(47, 10, 16500.00, 170000.00, 930.30, 'Manual update', '2026-03-09 17:31:11'),
(48, 1, 1100.00, 1100.00, 0.00, 'High stock adjustment (Stock: 200 exceeds 100 units) - Price decreased by 0.0%', '2026-03-09 23:43:23'),
(49, 2, 99.00, 99.00, 0.00, 'High stock adjustment (Stock: 100 exceeds 100 units) - Price decreased by 0.0%', '2026-03-09 23:43:23'),
(50, 5, 5500.00, 5500.00, 0.00, 'High stock adjustment (Stock: 150 exceeds 100 units) - Price decreased by 0.0%', '2026-03-09 23:43:23'),
(51, 7, 11000.00, 11000.00, 0.00, 'High stock adjustment (Stock: 500 exceeds 500 units) - Price decreased by 0.0%', '2026-03-09 23:43:23'),
(52, 8, 12000.00, 4400.00, -63.33, 'Normal stock level adjustment (200 units) - Price decreased by 63.3%', '2026-03-09 23:43:23'),
(53, 9, 1794.75, 1709.29, -4.76, 'Low stock adjustment (Stock: 1 of 20 units) - Price decreased by 4.8%', '2026-03-09 23:43:23'),
(54, 10, 170000.00, 16500.00, -90.29, 'High stock adjustment (Stock: 1000 exceeds 500 units) - Price decreased by 90.3%', '2026-03-09 23:43:23'),
(55, 1, 1100.00, 1100.00, 0.00, 'High stock adjustment (Stock: 199 exceeds 100 units) - Price decreased by 0.0%', '2026-03-10 00:40:08'),
(56, 10, 16500.00, 16500.00, 0.00, 'High stock adjustment (Stock: 999 exceeds 500 units) - Price decreased by 0.0%', '2026-03-10 00:40:08'),
(57, 10, 16500.00, 16500.00, 0.00, 'High stock adjustment (Stock: 997 exceeds 500 units) - Price decreased by 0.0%', '2026-03-10 02:08:44'),
(58, 9, 1709.29, 1800.00, 5.31, 'Low stock adjustment (Stock: 0 of 20 units) - Price increased by 5.3%', '2026-03-10 02:08:44'),
(59, 10, 16500.00, 14000.00, -15.15, 'Manual update', '2026-03-10 07:46:23'),
(60, 10, 14000.00, 50000.00, 257.14, 'Manual update', '2026-03-10 08:53:59'),
(61, 10, 50000.00, 80000.00, 60.00, 'Manual update', '2026-03-10 09:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `pricing_rules`
--

CREATE TABLE `pricing_rules` (
  `rule_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rule_name` varchar(100) NOT NULL,
  `rule_type` enum('inventory_based','time_based','demand_based','competitor_based','seasonal') NOT NULL,
  `min_value` decimal(10,2) DEFAULT NULL,
  `max_value` decimal(10,2) DEFAULT NULL,
  `percentage_change` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `base_cost` decimal(10,2) NOT NULL,
  `cost_currency` varchar(3) DEFAULT 'NGN',
  `current_price` decimal(10,2) NOT NULL,
  `price_currency` varchar(3) DEFAULT 'NGN',
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_price_update` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `seller_id`, `product_name`, `product_description`, `category`, `sku`, `base_cost`, `cost_currency`, `current_price`, `price_currency`, `image_url`, `is_active`, `last_price_update`, `created_at`, `updated_at`) VALUES
(1, 12, 'Beans', 'Cooked Beans from Teay Kitchen', 'general', 'BRD', 1000.00, 'NGN', 1100.00, 'NGN', '/assets/images/products/68fe4b5a7746f_Gemini_Generated_Image_g2zhmsg2zhmsg2zh.png', 1, '2026-03-10 00:40:08', '2025-10-26 16:24:58', '2026-03-10 00:40:08'),
(2, 12, 'Water', 'water water water', 'general', 'WTR-213', 90.00, 'NGN', 99.00, 'NGN', '/assets/images/products/6902a18049637_download.png', 1, '2026-03-09 23:43:23', '2025-10-29 23:21:36', '2026-03-09 23:43:23'),
(3, 12, 'Cello tApe', 'Cello Tape to tape', 'general', 'TAP-123', 100.00, 'NGN', 110.00, 'NGN', '/assets/images/products/6908db993f777_AYOMIDE_68a5dc664958e-removebg-preview.png', 1, '2025-11-03 16:43:57', '2025-11-03 16:43:05', '2025-11-03 16:43:57'),
(4, 12, 'Maker', 'White board Maker with ink pigment', 'general', 'MRK-231', 100.00, 'NGN', 110.00, 'NGN', '/assets/images/products/6908df39eabf4_the art of a boy.jpeg', 1, '2025-11-03 17:00:43', '2025-11-03 16:58:33', '2025-11-03 17:00:43'),
(5, 12, 'Gaming Headset', 'Gaming Haeadset Logitech', 'general', 'Gmh121', 5000.00, 'NGN', 5500.00, 'NGN', '/assets/images/products/6908e04f0b41a_dbf29f46-639a-4abd-8dbd-2a6e420afceb.jpeg', 1, '2026-03-09 23:43:23', '2025-11-03 17:03:11', '2026-03-09 23:43:23'),
(6, 12, 'USB Cable', 'Fast Type C charger for your mobile devices', 'general', 'C2C12', 2500.00, 'NGN', 2750.00, 'NGN', '/assets/images/products/6908e2f59902b_Ton regard malicieux, une boussole vers l’inconnu.jpeg', 1, '2025-11-03 17:15:53', '2025-11-03 17:14:29', '2025-11-03 17:15:53'),
(7, 12, 'Smart Watch', 'Oraimo Watch 5 Lite', 'general', 'SMG121', 10000.00, 'NGN', 11000.00, 'NGN', '/assets/images/products/6908e4bb22e78_IMG-20251021-WA0022.jpg', 1, '2026-03-09 23:43:23', '2025-11-03 17:22:03', '2026-03-09 23:43:23'),
(8, 12, 'Bluetooth speaker', 'Bluetooth Speaker', 'general', 'BS123', 4000.00, 'NGN', 4400.00, 'NGN', '/assets/images/products/6908e79f6b22e_caacb037-c9cb-4fd3-a4fc-adbc195dcc51.jpeg', 1, '2026-03-09 23:43:23', '2025-11-03 17:34:23', '2026-03-09 23:43:23'),
(9, 13, 'Bluetooth speaker JBL', 'Spring&#039;s cache abstraction applies cache-aside to methods, reducing executions by storing and reusing results. When a method is invoked, the abstraction checks if it&#039;s been called with the same arguments before. If so, it returns the cached result. If not, it invokes the method, caches the result, and returns it.', 'general', 'JBL-1234', 1500.00, 'NGN', 1800.00, 'NGN', '/assets/images/products/69aeef679b00b_WhatsApp Image 2026-03-06 at 5.37.45 PM.jpeg', 1, '2026-03-10 02:08:44', '2026-03-09 16:03:51', '2026-03-10 02:08:44'),
(10, 13, 'Bread', 'Spring&#039;s cache abstraction applies cache-aside to methods, reducing executions by storing and reusing results. When a method is invoked, the abstraction checks if it&#039;s been called with the same arguments before. If so, it returns the cached result. If not, it invokes the method, caches the result, and returns it.Spring&#039;s cache abstraction applies cache-aside to methods, reducing executions by storing and reusing results. When a method is invoked, the abstraction checks if it&#039;s been called with the same arguments before. If so, it returns the cached result. If not, it invokes the method, caches the result, and returns it.', 'general', 'Prod-134', 15000.00, 'NGN', 80000.00, 'NGN', '/assets/images/products/69aeff8faa670_WhatsApp Image 2026-03-08 at 5.37.28 PM.jpeg', 1, '2026-03-10 09:05:43', '2026-03-09 17:12:47', '2026-03-10 09:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `product_rating_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review` text DEFAULT NULL,
  `helpful_count` int(11) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_analytics`
--

CREATE TABLE `seller_analytics` (
  `analytics_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_orders` int(11) DEFAULT 0,
  `completed_orders` int(11) DEFAULT 0,
  `total_revenue` decimal(12,2) DEFAULT 0.00,
  `paid_orders` int(11) DEFAULT 0,
  `unique_products_sold` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_profiles`
--

CREATE TABLE `seller_profiles` (
  `seller_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(200) NOT NULL,
  `business_email` varchar(100) DEFAULT NULL,
  `business_phone` varchar(20) DEFAULT NULL,
  `business_description` text DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `business_city` varchar(100) DEFAULT NULL,
  `business_state` varchar(100) DEFAULT NULL,
  `store_logo_url` varchar(255) DEFAULT NULL,
  `store_banner_url` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `verified_at` timestamp NULL DEFAULT NULL,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_profiles`
--

INSERT INTO `seller_profiles` (`seller_id`, `user_id`, `business_name`, `business_email`, `business_phone`, `business_description`, `business_address`, `business_city`, `business_state`, `store_logo_url`, `store_banner_url`, `is_verified`, `is_active`, `verified_at`, `deactivated_at`, `created_at`, `updated_at`) VALUES
(11, 14, 'Ayomide Stephen Taiwo', 'tolanirokibat84@gmail.com', '08114891459', '', '', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2025-10-15 01:25:43', '2025-10-15 01:25:43'),
(12, 15, 'Ayomide Stephen Taiwo', 'admin@dynamic.com', '081154361899', '', '', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2025-10-26 15:17:01', '2025-10-26 15:17:01'),
(13, 18, 'Clerk Isreal', 'clerk@gmail.com', '081154361899', '', '', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2026-03-09 16:00:42', '2026-03-09 16:00:42');

-- --------------------------------------------------------

--
-- Table structure for table `seller_ratings`
--

CREATE TABLE `seller_ratings` (
  `rating_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review` text DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `user_type` enum('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
  `is_active` tinyint(1) DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `full_name`, `phone`, `user_type`, `is_active`, `email_verified_at`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'teay', 'taiwoayomide899@gmail.com', '$2y$10$aGHPePZvXjoIcYj3IweyDO9ueSpAcJh5OtOFezOul6QWK0c.eBQra', 'Ayomide Stephen Taiwo', '081154361899', 'buyer', 1, NULL, NULL, '2025-10-12 18:47:09', '2025-10-12 18:47:09'),
(2, 'ayo', 'taiwoayomide76@gmail.com', '$2y$10$sWszfQHfMXNxWttODxe3Nu6YwLdtRu9yb2GXP2DozUDTTgTnWG2Wi', 'Ayomide Stephen Taiwo', '08114891459', 'seller', 1, NULL, NULL, '2025-10-14 20:20:20', '2025-10-15 01:14:36'),
(13, 'bola', 'taiwoayomide131@gmail.com', '$2y$10$7VCH2MlU3HocTL.f46eLvuIRKQpAP2BUv5N/DTF78HraXCzoGykzW', 'Ayomide Stephen Taiwo', '08114891459', 'buyer', 1, NULL, NULL, '2025-10-15 01:17:51', '2025-10-15 01:17:51'),
(14, 'ayomide', 'tolanirokibat84@gmail.com', '$2y$10$Uyazzxma1YCvSkNfD1c9G.Jgl91zwrYkH63bgk4OMy0a1uzsutqEK', 'Ayomide Stephen Taiwo', '08114891459', 'seller', 1, NULL, NULL, '2025-10-15 01:25:43', '2025-10-15 01:25:43'),
(15, 'admin', 'admin@dynamic.com', '$2y$10$hdNDiendvMCtHQl5LilBkenDW8.9XpQ6/yMUPe1rqIQTCrYWLzESG', 'Ayomide Stephen Taiwo', '081154361899', 'seller', 1, NULL, NULL, '2025-10-26 15:17:01', '2025-10-26 15:17:01'),
(16, 'ayobami', 'taiwoa@gmail.com', '$2y$10$7wFuIRalIAJhipC.cc3mK.QfaL0jkG/Q9If.I5uLsi36bGLZr8XDC', 'Ayomide Stephen Taiwo', '081154361899', 'buyer', 1, NULL, NULL, '2025-10-28 15:03:49', '2025-10-28 15:03:49'),
(17, 'ayobami111', 'taiwoayomide1@gmail.com', '$2y$10$UTXEA0UsZYv1cy5yzwSe4uBmhX6jZwvM4bIzRZlrG3OQnRvB14gri', 'Ayomide Stephen Taiwo', '08114891459', 'buyer', 1, NULL, NULL, '2025-12-15 09:33:55', '2025-12-15 09:33:55'),
(18, 'Clerk', 'clerk@gmail.com', '$2y$10$TYrrV9uFvyGZGuqjWzgfcOQ2MYXU36p6YbySQSH7FsbDedStjv3/6', 'Clerk Isreal', '081154361899', 'seller', 1, NULL, NULL, '2026-03-09 16:00:42', '2026-03-09 16:00:42'),
(19, 'Bark', 'bark@gmail.com', '$2y$10$m4wQEhPIgr3t0BF9bipgTuoSqLBetGriksBcVV2IeIoSi55Vzj1QC', 'Bark Emmanuel', '08114891459', 'buyer', 1, NULL, NULL, '2026-03-09 16:05:48', '2026-03-09 16:05:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_token` (`token`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`rate_id`),
  ADD UNIQUE KEY `unique_currency_pair` (`from_currency`,`to_currency`),
  ADD KEY `idx_currencies` (`from_currency`,`to_currency`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_type` (`type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_seller_id` (`seller_id`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_status` (`order_status`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `pricing_history`
--
ALTER TABLE `pricing_history`
  ADD PRIMARY KEY (`pricing_history_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_changed_at` (`changed_at`);

--
-- Indexes for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `idx_seller_id` (`seller_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_rule_type` (`rule_type`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_seller_id` (`seller_id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_sku` (`sku`);
ALTER TABLE `products` ADD FULLTEXT KEY `ft_product_search` (`product_name`,`product_description`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`product_rating_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `seller_analytics`
--
ALTER TABLE `seller_analytics`
  ADD PRIMARY KEY (`analytics_id`),
  ADD UNIQUE KEY `unique_seller_date` (`seller_id`,`date`),
  ADD KEY `idx_seller_id` (`seller_id`),
  ADD KEY `idx_date` (`date`);

--
-- Indexes for table `seller_profiles`
--
ALTER TABLE `seller_profiles`
  ADD PRIMARY KEY (`seller_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_verified` (`is_verified`);

--
-- Indexes for table `seller_ratings`
--
ALTER TABLE `seller_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `idx_seller_id` (`seller_id`),
  ADD KEY `idx_buyer_id` (`buyer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_user_type` (`user_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_tokens`
--
ALTER TABLE `api_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricing_history`
--
ALTER TABLE `pricing_history`
  MODIFY `pricing_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `product_rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_analytics`
--
ALTER TABLE `seller_analytics`
  MODIFY `analytics_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_profiles`
--
ALTER TABLE `seller_profiles`
  MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `seller_ratings`
--
ALTER TABLE `seller_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD CONSTRAINT `api_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles` (`seller_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pricing_history`
--
ALTER TABLE `pricing_history`
  ADD CONSTRAINT `pricing_history_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  ADD CONSTRAINT `pricing_rules_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles` (`seller_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pricing_rules_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles` (`seller_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `seller_analytics`
--
ALTER TABLE `seller_analytics`
  ADD CONSTRAINT `seller_analytics_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles` (`seller_id`) ON DELETE CASCADE;

--
-- Constraints for table `seller_profiles`
--
ALTER TABLE `seller_profiles`
  ADD CONSTRAINT `seller_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `seller_ratings`
--
ALTER TABLE `seller_ratings`
  ADD CONSTRAINT `seller_ratings_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles` (`seller_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seller_ratings_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
