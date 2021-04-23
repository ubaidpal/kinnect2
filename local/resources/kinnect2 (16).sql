-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2016 at 04:08 PM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kinnect2`
--

-- --------------------------------------------------------

--
-- Table structure for table `store_albums`
--

CREATE TABLE IF NOT EXISTS `store_albums` (
  `album_id` int(10) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owner_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `view_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `search` tinyint(4) NOT NULL DEFAULT '1',
  `type` enum('wall','profile','message','comment','blog') COLLATE utf8_unicode_ci NOT NULL,
  `he_featured` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_albums`
--

INSERT INTO `store_albums` (`album_id`, `title`, `description`, `owner_type`, `owner_id`, `category_id`, `photo_id`, `view_count`, `comment_count`, `search`, `type`, `he_featured`, `created_at`, `updated_at`) VALUES
(0, 'Product Album', 'Agatha Christie''s The ABC Murders''s album''', 'product', 1, 0, 0, 0, 0, 1, '', 0, '2016-02-24 05:12:57', '2016-02-24 05:12:57'),
(0, 'Product Album', 'Unravel''s album''', 'product', 2, 0, 0, 0, 0, 1, '', 0, '2016-03-02 02:20:07', '2016-03-02 02:20:07'),
(0, 'Product Album', 'Call of Duty: Black Ops III  Limited Edition PS4''s album''', 'product', 3, 0, 0, 0, 0, 1, '', 0, '2016-03-02 02:57:28', '2016-03-02 02:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `store_album_photos`
--

CREATE TABLE IF NOT EXISTS `store_album_photos` (
  `photo_id` int(10) unsigned NOT NULL,
  `album_id` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `owner_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `view_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `he_featured` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_album_photos`
--

INSERT INTO `store_album_photos` (`photo_id`, `album_id`, `title`, `parent_id`, `description`, `order`, `owner_type`, `owner_id`, `file_id`, `view_count`, `comment_count`, `he_featured`, `created_at`, `updated_at`) VALUES
(0, 0, 'Agatha Christie''s The ABC Murders', 0, '', 0, 'product', 1, 0, 0, 0, 0, '2016-02-24 05:12:57', '2016-02-24 05:12:57'),
(0, 0, 'Unravel', 0, '', 0, 'product', 2, 2, 0, 0, 0, '2016-03-02 02:20:07', '2016-03-02 02:20:07'),
(0, 0, 'Call of Duty: Black Ops III  Limited Edition PS4', 0, '', 0, 'product', 3, 6, 0, 0, 0, '2016-03-02 02:57:29', '2016-03-02 02:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `store_delivery_addresses`
--

CREATE TABLE IF NOT EXISTS `store_delivery_addresses` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `st_address_1` text COLLATE utf8_unicode_ci NOT NULL,
  `st_address_2` text COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_delivery_addresses`
--

INSERT INTO `store_delivery_addresses` (`id`, `user_id`, `country_id`, `first_name`, `last_name`, `order_id`, `st_address_1`, `st_address_2`, `city`, `state`, `zip_code`, `phone_number`, `email`, `created_at`, `updated_at`) VALUES
(1, 5937, 1, 'zahid', 'khurshid', 1, 'sadat', 'colonyt', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 5937, 1, 'zahid', 'khurshid', 2, 'sadat', 'colonyt', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 5937, 1, 'zahid', 'khurshid', 3, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 5937, 5, 'zahid', 'khurshid', 4, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 5937, 5, 'zahid', 'khurshid', 5, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 5937, 5, 'zahid', 'khurshid', 6, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 5937, 5, 'zahid', 'khurshid', 7, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 5937, 5, 'zahid', 'khurshid', 8, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 5937, 5, 'zahid', 'khurshid', 1, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 5937, 5, 'zahid', 'khurshid', 3, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 5937, 5, 'zahid', 'khurshid', 4, 'sadat', 'colony', 'rahim yar kjhan', 'NULL', '9999', '333333', 'zaars@gmail.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `store_orders`
--

CREATE TABLE IF NOT EXISTS `store_orders` (
  `id` int(10) unsigned NOT NULL,
  `order_number` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `delivery_address_id` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_deleted` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `total_price` double(11,2) NOT NULL,
  `total_shiping_cost` float(11,2) NOT NULL,
  `total_discount` decimal(11,2) NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approved_date` datetime NOT NULL,
  `shiping_date` datetime NOT NULL,
  `received_date` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_orders`
--

INSERT INTO `store_orders` (`id`, `order_number`, `customer_id`, `seller_id`, `delivery_address_id`, `payment_type`, `status`, `is_deleted`, `total_price`, `total_shiping_cost`, `total_discount`, `total_quantity`, `created_at`, `approved_date`, `shiping_date`, `received_date`, `updated_at`) VALUES
(1, '7B0485CB68', 5937, 5934, 9, 1, 3, '0', 200.00, 0.00, '8.00', 1, '0000-00-00 00:00:00', '2016-03-07 07:44:58', '2016-03-07 06:42:26', '2016-03-07 06:42:32', '2016-03-07 02:44:58'),
(2, '7B0425CB67', 5937, 5934, 9, 1, 3, '0', 200.00, 0.00, '8.00', 1, '0000-00-00 00:00:00', '2016-03-07 07:44:54', '2016-03-07 06:41:28', '2016-03-07 06:41:36', '2016-03-07 02:44:54'),
(3, '7B04898CB6', 5937, 5934, 10, 1, 6, '0', 200.00, 0.00, '20.00', 1, '0000-00-00 00:00:00', '2016-03-07 12:17:27', '2016-03-07 12:18:14', '2016-03-07 12:18:37', '2016-03-07 07:18:37'),
(4, '7B0485CB67', 5937, 5934, 11, 1, 6, '0', 200.00, 10.00, '20.00', 1, '0000-00-00 00:00:00', '2016-03-07 12:15:54', '2016-03-07 12:17:05', '2016-03-07 12:18:20', '2016-03-07 07:18:20');

-- --------------------------------------------------------

--
-- Table structure for table `store_order_delivery_info`
--

CREATE TABLE IF NOT EXISTS `store_order_delivery_info` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `courier_service_name` varchar(255) NOT NULL,
  `courier_service_url` varchar(255) NOT NULL,
  `order_tracking_number` varchar(255) NOT NULL,
  `delivery_estimated_time` varchar(255) NOT NULL,
  `date_to_be_delivered` date NOT NULL,
  `delivery_charges_paid` enum('1','0') NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_order_delivery_info`
--

INSERT INTO `store_order_delivery_info` (`id`, `seller_id`, `order_id`, `courier_service_name`, `courier_service_url`, `order_tracking_number`, `delivery_estimated_time`, `date_to_be_delivered`, `delivery_charges_paid`, `created_at`, `updated_at`) VALUES
(1, 5934, 1, 'this is test', 'this is test', 'this is test', 'this is test', '0000-00-00', '1', '2016-03-03 05:49:25', '2016-03-03 10:49:25'),
(2, 5934, 1, 'this is test', 'this is test', 'this is test', 'this is test', '2016-03-10', '1', '2016-03-03 05:50:47', '2016-03-03 10:50:47'),
(3, 5934, 2, 'this is test', 'this is test', 'this is test', 'this is test', '0000-00-00', '1', '2016-03-03 06:00:30', '2016-03-03 11:00:30'),
(4, 5934, 1, 'this is testum second', 'this is testum second', 'this is testum second', 'this is testum secon', '0000-00-00', '1', '2016-03-03 06:01:02', '2016-03-03 11:01:02'),
(5, 5934, 2, 'sdfsfdad', 'sadfasd', 'asdf', 'sadfas', '0000-00-00', '1', '2016-03-03 06:05:15', '2016-03-03 11:05:15'),
(6, 5934, 1, 'sdfsfdad', 'sadfasd', 'asdf', 'sadfas', '0000-00-00', '', '2016-03-03 06:06:03', '2016-03-03 11:06:03'),
(7, 5934, 2, 'test', 'testyum', '', 'testim', '2016-03-15', '', '2016-03-03 08:22:53', '2016-03-03 13:22:53'),
(8, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:29:31', '2016-03-03 13:29:31'),
(9, 5934, 1, 'atsefd', 'sdfs', 'sdfwe', 'wefw', '0000-00-00', '1', '2016-03-03 08:31:56', '2016-03-03 13:31:56'),
(10, 5934, 2, 'sdfsfdad', 'sadfasd', '12342134123', 'sadfas', '0000-00-00', '1', '2016-03-03 08:33:46', '2016-03-03 13:33:46'),
(11, 5934, 2, 'sdfsfdad', 'sadfasd', '123123', 'sadfas', '2015-12-15', '1', '2016-03-03 08:37:26', '2016-03-03 13:37:26'),
(12, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:41:41', '2016-03-03 13:41:41'),
(13, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:45:03', '2016-03-03 13:45:03'),
(14, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:45:12', '2016-03-03 13:45:12'),
(15, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:45:48', '2016-03-03 13:45:48'),
(16, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:46:07', '2016-03-03 13:46:07'),
(17, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:46:15', '2016-03-03 13:46:15'),
(18, 5934, 2, 'test', 'testyum', '123412423151234534', 'testim', '2016-03-15', '', '2016-03-03 08:47:47', '2016-03-03 13:47:47'),
(19, 5934, 1, 'yeh lo', 'yeh lo g', '234234234', '1231', '0000-00-00', '1', '2016-03-03 08:50:58', '2016-03-03 13:50:58'),
(20, 5934, 3, 'okay', 'okay', 'okay', 'okay', '2016-01-01', '1', '2016-03-07 01:40:52', '2016-03-07 06:40:52'),
(21, 5934, 2, 'okay', 'okay', 'okay', 'okay', '2016-01-01', '1', '2016-03-07 01:41:28', '2016-03-07 06:41:28'),
(22, 5934, 1, 'sdfsfdad', 'sadfasd', 'sadfasd', 'sadfasd', '2016-12-13', '1', '2016-03-07 01:42:26', '2016-03-07 06:42:26'),
(23, 5934, 4, 'DHL Paksitan', 'dhl.com/tracking/order?gfkl9872139', 'gfkl9872139', '10-15 Days', '2016-03-15', '0', '2016-03-07 03:01:55', '2016-03-07 08:01:55'),
(24, 5934, 4, 'Leopard Courier service Pakistan.', 'Leopard.com', 'Leopard.com/orderTrack=HGU87672934', '5 Days', '2016-05-05', '1', '2016-03-07 07:17:04', '2016-03-07 12:17:04'),
(25, 5934, 3, 'Leopard', 'leopard.com', 'leopard.com/orderTrack=KJI87893567', '5 Days', '2016-07-07', '1', '2016-03-07 07:18:13', '2016-03-07 12:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `store_order_items`
--

CREATE TABLE IF NOT EXISTS `store_order_items` (
  `id` int(10) unsigned NOT NULL,
  `product_price` double(11,2) NOT NULL,
  `product_discount` double(11,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_order_items`
--

INSERT INTO `store_order_items` (`id`, `product_price`, `product_discount`, `quantity`, `product_id`, `order_id`, `created_at`, `updated_at`) VALUES
(3, 123.00, 321.00, 123, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 123.00, 321.00, 123, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 200.00, 20.00, 1, 2, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 200.00, 20.00, 1, 2, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `store_order_status_log`
--

CREATE TABLE IF NOT EXISTS `store_order_status_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `status_changed_from` int(11) NOT NULL,
  `status_changed_to` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_order_status_log`
--

INSERT INTO `store_order_status_log` (`id`, `user_id`, `ip`, `status_changed_from`, `status_changed_to`, `created_at`, `updated_at`) VALUES
(1, 5934, '127.0.0.1', 3, 4, '2016-02-24 05:37:40', '2016-02-24 10:37:40'),
(2, 5934, '127.0.0.1', 4, 5, '2016-02-24 05:38:27', '2016-02-24 10:38:27'),
(3, 5937, '127.0.0.1', 5, 6, '2016-02-24 05:38:35', '2016-02-24 10:38:35'),
(4, 5934, '::1', 3, 4, '2016-02-24 05:41:01', '2016-02-24 10:41:01'),
(5, 5934, '::1', 4, 5, '2016-02-24 05:43:34', '2016-02-24 10:43:34'),
(6, 5937, '::1', 5, 6, '2016-02-24 05:44:11', '2016-02-24 10:44:11'),
(7, 5937, '::1', 3, 0, '2016-02-24 06:11:02', '2016-02-24 11:11:02'),
(8, 5937, '::1', 3, 0, '2016-02-24 06:11:55', '2016-02-24 11:11:55'),
(9, 5937, '::1', 3, 0, '2016-02-24 06:14:34', '2016-02-24 11:14:34'),
(10, 5934, '::1', 3, 4, '2016-02-26 06:38:56', '2016-02-26 11:38:56'),
(11, 5934, '::1', 4, 5, '2016-02-26 06:39:15', '2016-02-26 11:39:15'),
(12, 5937, '::1', 5, 6, '2016-02-26 06:39:22', '2016-02-26 11:39:22'),
(13, 5934, '::1', 3, 4, '2016-02-26 06:39:58', '2016-02-26 11:39:58'),
(14, 5934, '::1', 4, 5, '2016-02-26 06:40:01', '2016-02-26 11:40:01'),
(15, 5937, '::1', 5, 6, '2016-02-26 06:40:09', '2016-02-26 11:40:09'),
(16, 5937, '::1', 6, 6, '2016-02-26 06:40:43', '2016-02-26 11:40:43'),
(17, 5937, '::1', 6, 6, '2016-02-26 06:41:11', '2016-02-26 11:41:11'),
(18, 5934, '::1', 3, 4, '2016-02-29 05:06:27', '2016-02-29 10:06:27'),
(19, 5934, '::1', 4, 5, '2016-02-29 05:07:09', '2016-02-29 10:07:09'),
(20, 5937, '::1', 5, 6, '2016-02-29 05:07:15', '2016-02-29 10:07:15'),
(21, 5937, '::1', 6, 6, '2016-02-29 05:07:26', '2016-02-29 10:07:26'),
(22, 5937, '::1', 6, 6, '2016-02-29 05:08:11', '2016-02-29 10:08:11'),
(23, 5934, '::1', 3, 4, '2016-03-03 00:53:07', '2016-03-03 05:53:07'),
(24, 5934, '::1', 3, 4, '2016-03-03 03:51:03', '2016-03-03 08:51:03'),
(25, 5934, '::1', 4, 5, '2016-03-03 03:54:15', '2016-03-03 08:54:15'),
(26, 5934, '::1', 3, 4, '2016-03-03 05:41:16', '2016-03-03 10:41:16'),
(27, 5934, '::1', 4, 5, '2016-03-03 05:45:11', '2016-03-03 10:45:11'),
(28, 5934, '::1', 4, 5, '2016-03-03 05:46:07', '2016-03-03 10:46:07'),
(29, 5934, '::1', 4, 5, '2016-03-03 05:49:25', '2016-03-03 10:49:25'),
(30, 5934, '::1', 4, 5, '2016-03-03 05:50:47', '2016-03-03 10:50:47'),
(31, 5934, '::1', 4, 5, '2016-03-03 06:00:31', '2016-03-03 11:00:31'),
(32, 5934, '::1', 4, 5, '2016-03-03 06:01:03', '2016-03-03 11:01:03'),
(33, 5937, '::1', 5, 6, '2016-03-03 06:01:49', '2016-03-03 11:01:49'),
(34, 5937, '::1', 5, 6, '2016-03-03 06:02:07', '2016-03-03 11:02:07'),
(35, 5937, '::1', 6, 6, '2016-03-03 06:03:18', '2016-03-03 11:03:18'),
(36, 5934, '::1', 3, 4, '2016-03-03 06:05:01', '2016-03-03 11:05:01'),
(37, 5934, '::1', 3, 4, '2016-03-03 06:05:04', '2016-03-03 11:05:04'),
(38, 5934, '::1', 4, 5, '2016-03-03 06:05:15', '2016-03-03 11:05:15'),
(39, 5937, '::1', 5, 6, '2016-03-03 06:05:33', '2016-03-03 11:05:33'),
(40, 5934, '::1', 4, 5, '2016-03-03 06:06:04', '2016-03-03 11:06:04'),
(41, 5937, '::1', 5, 6, '2016-03-03 06:06:11', '2016-03-03 11:06:11'),
(42, 5934, '::1', 3, 4, '2016-03-03 06:20:50', '2016-03-03 11:20:50'),
(43, 5934, '::1', 4, 5, '2016-03-03 08:22:53', '2016-03-03 13:22:53'),
(44, 5934, '::1', 4, 5, '2016-03-03 08:29:31', '2016-03-03 13:29:31'),
(45, 5934, '::1', 3, 4, '2016-03-03 08:29:49', '2016-03-03 13:29:49'),
(46, 5937, '::1', 5, 6, '2016-03-03 08:30:56', '2016-03-03 13:30:56'),
(47, 5934, '::1', 4, 5, '2016-03-03 08:31:56', '2016-03-03 13:31:56'),
(48, 5934, '::1', 3, 4, '2016-03-03 08:33:07', '2016-03-03 13:33:07'),
(49, 5934, '::1', 4, 5, '2016-03-03 08:33:46', '2016-03-03 13:33:46'),
(50, 5934, '::1', 3, 4, '2016-03-03 08:36:54', '2016-03-03 13:36:54'),
(51, 5934, '::1', 4, 5, '2016-03-03 08:37:26', '2016-03-03 13:37:26'),
(52, 5934, '::1', 4, 5, '2016-03-03 08:41:41', '2016-03-03 13:41:41'),
(53, 5934, '::1', 4, 5, '2016-03-03 08:47:47', '2016-03-03 13:47:47'),
(54, 5937, '::1', 5, 6, '2016-03-03 08:50:11', '2016-03-03 13:50:11'),
(55, 5934, '::1', 4, 5, '2016-03-03 08:50:58', '2016-03-03 13:50:58'),
(56, 5937, '::1', 5, 6, '2016-03-03 08:51:03', '2016-03-03 13:51:03'),
(57, 5937, '::1', 2, 0, '2016-03-04 08:17:39', '2016-03-04 13:17:39'),
(58, 5937, '::1', 3, 0, '2016-03-04 08:23:20', '2016-03-04 13:23:20'),
(59, 5934, '127.0.0.1', 3, 4, '2016-03-04 09:55:46', '2016-03-04 14:55:46'),
(60, 5937, '::1', 3, 0, '2016-03-06 23:46:51', '2016-03-07 04:46:51'),
(61, 5934, '::1', 3, 0, '2016-03-06 23:55:52', '2016-03-07 04:55:52'),
(62, 5937, '::1', 3, 0, '2016-03-07 00:04:04', '2016-03-07 05:04:04'),
(63, 5937, '::1', 2, 0, '2016-03-07 00:25:48', '2016-03-07 05:25:48'),
(64, 5937, '::1', 2, 0, '2016-03-07 00:25:57', '2016-03-07 05:25:57'),
(65, 5934, '::1', 3, 4, '2016-03-07 00:47:30', '2016-03-07 05:47:30'),
(66, 5934, '::1', 3, 0, '2016-03-07 00:47:45', '2016-03-07 05:47:45'),
(67, 5937, '::1', 3, 0, '2016-03-07 01:12:41', '2016-03-07 06:12:41'),
(68, 5937, '::1', 0, 0, '2016-03-07 01:12:43', '2016-03-07 06:12:43'),
(69, 5937, '::1', 3, 0, '2016-03-07 01:14:35', '2016-03-07 06:14:35'),
(70, 5937, '::1', 3, 0, '2016-03-07 01:17:25', '2016-03-07 06:17:25'),
(71, 5937, '::1', 3, 0, '2016-03-07 01:18:22', '2016-03-07 06:18:22'),
(72, 5937, '::1', 3, 0, '2016-03-07 01:22:02', '2016-03-07 06:22:02'),
(73, 5937, '::1', 3, 0, '2016-03-07 01:23:45', '2016-03-07 06:23:45'),
(74, 5937, '::1', 3, 0, '2016-03-07 01:23:49', '2016-03-07 06:23:49'),
(75, 5937, '::1', 3, 0, '2016-03-07 01:25:15', '2016-03-07 06:25:15'),
(76, 5937, '::1', 3, 0, '2016-03-07 01:26:09', '2016-03-07 06:26:09'),
(77, 5937, '::1', 0, 0, '2016-03-07 01:26:10', '2016-03-07 06:26:10'),
(78, 5937, '::1', 3, 0, '2016-03-07 01:29:13', '2016-03-07 06:29:13'),
(79, 5937, '::1', 3, 0, '2016-03-07 01:29:20', '2016-03-07 06:29:20'),
(80, 5937, '::1', 3, 0, '2016-03-07 01:29:53', '2016-03-07 06:29:53'),
(81, 5937, '::1', 3, 0, '2016-03-07 01:30:00', '2016-03-07 06:30:00'),
(82, 5937, '::1', 3, 0, '2016-03-07 01:34:02', '2016-03-07 06:34:02'),
(83, 5937, '::1', 3, 0, '2016-03-07 01:38:04', '2016-03-07 06:38:04'),
(84, 5934, '::1', 3, 4, '2016-03-07 01:39:23', '2016-03-07 06:39:23'),
(85, 5934, '::1', 4, 5, '2016-03-07 01:40:52', '2016-03-07 06:40:52'),
(86, 5934, '::1', 4, 5, '2016-03-07 01:41:28', '2016-03-07 06:41:28'),
(87, 5937, '::1', 5, 6, '2016-03-07 01:41:36', '2016-03-07 06:41:36'),
(88, 5934, '::1', 3, 4, '2016-03-07 01:42:10', '2016-03-07 06:42:10'),
(89, 5934, '::1', 4, 5, '2016-03-07 01:42:27', '2016-03-07 06:42:27'),
(90, 5937, '::1', 5, 6, '2016-03-07 01:42:32', '2016-03-07 06:42:32'),
(91, 5934, '::1', 3, 4, '2016-03-07 01:51:15', '2016-03-07 06:51:15'),
(92, 5934, '::1', 3, 0, '2016-03-07 02:07:14', '2016-03-07 07:07:14'),
(93, 5937, '::1', 4, 0, '2016-03-07 02:09:12', '2016-03-07 07:09:12'),
(94, 5934, '::1', 3, 0, '2016-03-07 02:26:14', '2016-03-07 07:26:14'),
(95, 5934, '::1', 3, 0, '2016-03-07 02:32:47', '2016-03-07 07:32:47'),
(96, 5934, '::1', 3, 0, '2016-03-07 02:32:54', '2016-03-07 07:32:54'),
(97, 5934, '::1', 3, 4, '2016-03-07 02:44:51', '2016-03-07 07:44:51'),
(98, 5934, '::1', 3, 4, '2016-03-07 02:44:54', '2016-03-07 07:44:54'),
(99, 5934, '::1', 3, 4, '2016-03-07 02:44:58', '2016-03-07 07:44:58'),
(100, 5934, '::1', 3, 4, '2016-03-07 02:45:49', '2016-03-07 07:45:49'),
(101, 5934, '::1', 4, 5, '2016-03-07 03:01:55', '2016-03-07 08:01:55'),
(102, 5934, '::1', 3, 4, '2016-03-07 07:15:54', '2016-03-07 12:15:54'),
(103, 5934, '::1', 4, 5, '2016-03-07 07:17:05', '2016-03-07 12:17:05'),
(104, 5934, '::1', 3, 4, '2016-03-07 07:17:27', '2016-03-07 12:17:27'),
(105, 5934, '::1', 4, 5, '2016-03-07 07:18:14', '2016-03-07 12:18:14'),
(106, 5937, '::1', 5, 6, '2016-03-07 07:18:20', '2016-03-07 12:18:20'),
(107, 5937, '::1', 5, 6, '2016-03-07 07:18:37', '2016-03-07 12:18:37');

-- --------------------------------------------------------

--
-- Table structure for table `store_order_transactions`
--

CREATE TABLE IF NOT EXISTS `store_order_transactions` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gateway_transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gateway_parent_transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gateway_order_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_products`
--

CREATE TABLE IF NOT EXISTS `store_products` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double(11,2) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `discount` double(11,2) NOT NULL,
  `length` double(11,2) NOT NULL,
  `width` double(11,2) NOT NULL,
  `height` double(11,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sold` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `sub_category_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_products`
--

INSERT INTO `store_products` (`id`, `title`, `price`, `description`, `discount`, `length`, `width`, `height`, `quantity`, `sold`, `owner_id`, `category_id`, `sub_category_id`, `created_at`, `updated_at`) VALUES
(2, 'Unravel', 200.00, '<p>Puzzle adventure...</p>', 20.00, 123.00, 123.00, 1231.00, 254, 246, 5934, 2, 5, '2016-03-02 02:20:07', '2016-03-03 06:05:33');

-- --------------------------------------------------------

--
-- Table structure for table `store_product_categories`
--

CREATE TABLE IF NOT EXISTS `store_product_categories` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `category_parent_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_product_categories`
--

INSERT INTO `store_product_categories` (`id`, `name`, `owner_id`, `category_parent_id`, `created_at`, `updated_at`) VALUES
(2, 'Games', 5934, 0, '2016-02-11 06:12:36', '2016-02-18 05:46:51'),
(3, 'Consoles', 5934, 0, '2016-02-11 06:12:45', '2016-02-11 06:12:45'),
(4, 'Accessories', 5934, 0, '2016-02-11 06:13:00', '2016-02-11 06:13:00'),
(5, 'Adventure', 5934, 2, '2016-02-11 06:13:14', '2016-02-11 06:13:14'),
(6, 'Action', 5934, 2, '2016-02-11 06:13:20', '2016-02-11 06:13:20'),
(7, 'First Person Shooting (FPS)', 5934, 2, '2016-02-11 06:13:26', '2016-02-11 06:13:45'),
(8, 'Playstation 1 (PS1)', 5934, 3, '2016-02-11 06:14:08', '2016-02-11 06:14:08'),
(9, 'Playstation 2 (PS2)', 5934, 3, '2016-02-11 06:14:17', '2016-02-11 06:14:46'),
(10, 'Playstation 3 (PS3)', 5934, 3, '2016-02-11 06:14:30', '2016-02-11 06:14:30'),
(11, 'Playstation 4 (PS4)', 5934, 3, '2016-02-11 06:15:00', '2016-02-11 06:15:00'),
(12, 'Controllers', 5934, 4, '2016-02-11 06:47:19', '2016-02-11 06:47:19'),
(13, 'HDMI Cable', 5934, 4, '2016-02-11 07:00:48', '2016-02-11 07:00:48'),
(14, 'T.V', 5934, 0, '2016-02-11 07:03:58', '2016-02-11 07:03:58'),
(15, '4k UHD', 5934, 14, '2016-02-11 07:04:12', '2016-02-11 07:04:12'),
(16, 'Dresses', 5132, 0, '2016-02-16 04:02:35', '2016-02-16 04:02:35'),
(17, 'Shilwar Kameez', 5132, 16, '2016-02-16 04:02:42', '2016-02-16 04:02:42'),
(19, 'PSP', 5934, 0, '2016-02-18 04:00:47', '2016-02-18 04:00:47'),
(20, 'PSP VITA', 5934, 19, '2016-02-18 04:02:41', '2016-02-18 04:02:41'),
(21, 'Sports Accessories', 5934, 0, '2016-02-18 05:46:11', '2016-02-18 05:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `store_product_features`
--

CREATE TABLE IF NOT EXISTS `store_product_features` (
  `id` int(10) unsigned NOT NULL,
  `key_feature_type` int(11) NOT NULL,
  `pr_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_product_features`
--

INSERT INTO `store_product_features` (`id`, `key_feature_type`, `pr_id`, `title`, `detail`, `created_at`, `updated_at`) VALUES
(3, 1, 2, 'Washable', 'By Hands only', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 2, 2, 'Tareable', 'Nop', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `store_product_reviews`
--

CREATE TABLE IF NOT EXISTS `store_product_reviews` (
  `id` int(10) unsigned NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `is_revised` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_product_reviews`
--

INSERT INTO `store_product_reviews` (`id`, `description`, `owner_id`, `product_id`, `rating`, `is_revised`, `created_at`, `updated_at`) VALUES
(3, 'okay is it updating?', 5937, 2, 2, '0', '0000-00-00 00:00:00', '2016-03-04 09:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `store_product_shipping_cost`
--

CREATE TABLE IF NOT EXISTS `store_product_shipping_cost` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `shipping_cost` double NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_product_shipping_cost`
--

INSERT INTO `store_product_shipping_cost` (`id`, `region_id`, `status`, `shipping_cost`, `product_id`, `created_at`, `updated_at`) VALUES
(30, 1, '1', 100, 2, '2016-03-08 09:37:55', '2016-03-08 09:37:55'),
(31, 2, '1', 200, 2, '2016-03-08 09:37:55', '2016-03-08 09:37:55'),
(32, 3, '1', 100, 2, '2016-03-08 09:37:55', '2016-03-08 09:37:55'),
(33, 4, '1', 300, 2, '2016-03-08 09:37:55', '2016-03-08 09:37:55'),
(34, 5, '1', 400, 2, '2016-03-08 09:37:55', '2016-03-08 09:37:55');

-- --------------------------------------------------------

--
-- Table structure for table `store_product_shipping_countries`
--

CREATE TABLE IF NOT EXISTS `store_product_shipping_countries` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_product_shipping_countries`
--

INSERT INTO `store_product_shipping_countries` (`id`, `region_id`, `product_id`, `country_id`, `created_at`, `updated_at`) VALUES
(7, 3, 2, 11, '2016-03-08 09:24:16', '2016-03-08 09:24:16'),
(8, 3, 2, 25, '2016-03-08 09:24:16', '2016-03-08 09:24:16'),
(9, 3, 2, 162, '2016-03-08 09:24:16', '2016-03-08 09:24:16');

-- --------------------------------------------------------

--
-- Table structure for table `store_product_shipping_regions`
--

CREATE TABLE IF NOT EXISTS `store_product_shipping_regions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_product_shipping_regions`
--

INSERT INTO `store_product_shipping_regions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'africa', '2016-03-08 00:53:36', '0000-00-00 00:00:00'),
(2, 'americas', '2016-03-08 00:53:36', '0000-00-00 00:00:00'),
(3, 'asia', '2016-03-08 00:54:01', '0000-00-00 00:00:00'),
(4, 'europe', '2016-03-08 00:54:01', '0000-00-00 00:00:00'),
(5, 'oceania', '2016-03-08 00:54:14', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `store_product_statics`
--

CREATE TABLE IF NOT EXISTS `store_product_statics` (
  `id` int(11) NOT NULL,
  `stat_type` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('1','2') NOT NULL,
  `user_age` int(3) DEFAULT NULL,
  `user_gender` enum('1','2','','') DEFAULT NULL,
  `user_region` int(50) NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `product_owner_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_product_statics`
--

INSERT INTO `store_product_statics` (`id`, `stat_type`, `user_id`, `user_type`, `user_age`, `user_gender`, `user_region`, `user_ip`, `product_owner_id`, `product_id`, `created_at`, `updated_at`) VALUES
(53, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 21:53:38', '2016-02-29 13:53:38'),
(54, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 12:53:42', '2016-02-29 13:53:42'),
(55, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 05:53:44', '2016-02-29 13:53:44'),
(56, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 07:55:56', '2016-02-29 13:55:56'),
(57, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 13:53:42', '2016-02-29 13:53:42'),
(58, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 13:53:42', '2016-02-29 13:53:42'),
(59, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 16:53:42', '2016-02-29 13:53:42'),
(60, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-02-29 17:53:42', '2016-02-29 13:53:42'),
(61, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-03-01 06:53:36', '2016-03-01 06:53:36'),
(62, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 1, '2016-03-02 06:39:12', '2016-03-02 06:39:12'),
(63, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-02 07:23:07', '2016-03-02 07:23:07'),
(64, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-02 07:43:27', '2016-03-02 07:43:27'),
(65, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 07:58:51', '2016-03-02 07:58:51'),
(66, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:18:35', '2016-03-02 08:18:35'),
(67, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:20:54', '2016-03-02 08:20:54'),
(68, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:21:48', '2016-03-02 08:21:48'),
(69, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:23:06', '2016-03-02 08:23:06'),
(70, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:23:29', '2016-03-02 08:23:29'),
(71, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:23:42', '2016-03-02 08:23:42'),
(72, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:26:30', '2016-03-02 08:26:30'),
(73, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:26:53', '2016-03-02 08:26:53'),
(74, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:29:58', '2016-03-02 08:29:58'),
(75, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:30:24', '2016-03-02 08:30:24'),
(76, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:32:06', '2016-03-02 08:32:06'),
(77, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:33:12', '2016-03-02 08:33:12'),
(78, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:34:13', '2016-03-02 08:34:13'),
(79, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:34:46', '2016-03-02 08:34:46'),
(80, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 08:35:31', '2016-03-02 08:35:31'),
(81, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-02 11:34:21', '2016-03-02 11:34:21'),
(82, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-02 11:34:46', '2016-03-02 11:34:46'),
(83, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 11:35:11', '2016-03-02 11:35:11'),
(84, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 11:35:26', '2016-03-02 11:35:26'),
(85, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 11:35:39', '2016-03-02 11:35:39'),
(86, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 3, '2016-03-02 11:36:25', '2016-03-02 11:36:25'),
(87, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-03 05:52:01', '2016-03-03 05:52:01'),
(88, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-03 05:57:04', '2016-03-03 05:57:04'),
(89, 'sale', 1, '', 1, '', 0, '5934', 2, 0, '2016-03-03 11:03:18', '2016-03-03 11:03:18'),
(90, 'sale', 1, '', 1, '', 0, '5934', 2, 0, '2016-03-03 11:05:33', '2016-03-03 11:05:33'),
(91, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-04 06:36:45', '2016-03-04 06:36:45'),
(92, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-04 06:36:57', '2016-03-04 06:36:57'),
(93, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-04 12:00:36', '2016-03-04 12:00:36'),
(94, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-07 07:43:09', '2016-03-07 07:43:09'),
(95, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-07 09:55:49', '2016-03-07 09:55:49'),
(96, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-07 11:47:15', '2016-03-07 11:47:15'),
(97, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-08 09:53:42', '2016-03-08 09:53:42'),
(98, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-08 10:55:08', '2016-03-08 10:55:08'),
(99, 'view', 5937, '1', 21, '1', 162, '::1', 5934, 2, '2016-03-08 10:56:48', '2016-03-08 10:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `store_storage_files`
--

CREATE TABLE IF NOT EXISTS `store_storage_files` (
  `file_id` bigint(20) unsigned NOT NULL,
  `parent_file_id` int(11) DEFAULT NULL,
  `type` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_type` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `storage_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mime_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mime_major` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `hash` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `is_temp` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store_storage_files`
--

INSERT INTO `store_storage_files` (`file_id`, `parent_file_id`, `type`, `parent_type`, `parent_id`, `user_id`, `storage_path`, `extension`, `name`, `mime_type`, `mime_major`, `size`, `hash`, `is_temp`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'album_photo', NULL, -1, '-1/14563159263056cd9e16ba2112.24725287.jpg', 'jpg', 'Ali-zafar cover.jpg', 'image/jpeg', '', 52698, '4b8224e8d3ab76a32b7feb96d1f1137ffbd8de19', 0, '2016-02-24 07:12:06', '2016-02-24 07:12:06'),
(2, NULL, NULL, 'album_photo', 0, 5934, '5934/5934_14569032071353933838.jpg', 'jpg', '14569032071353933838', 'image/jpeg', 'image', 73444, '8ee61aff7011dd7dc091823210aec85677178e3a', 0, '2016-03-02 02:19:41', '2016-03-02 02:20:07'),
(3, 2, 'product_profile', 'album_photo', 5934, 5934, '5934/5934_14569032071139278152.jpg', 'jpg', '5934_14569032071139278152.jpg', 'image/jpeg', 'image', 0, '', 0, '2016-03-02 02:20:08', '2016-03-02 02:20:08'),
(4, 2, 'product_thumb', 'album_photo', 5934, 5934, '5934/5934_1456903208128473842.jpg', 'jpg', '5934_1456903208128473842.jpg', 'image/jpeg', 'image', 0, '', 0, '2016-03-02 02:20:08', '2016-03-02 02:20:08'),
(5, 2, 'product_icon', 'album_photo', 5934, 5934, '5934/5934_14569032081072284964.jpg', 'jpg', '5934_14569032081072284964.jpg', 'image/jpeg', 'image', 0, '', 0, '2016-03-02 02:20:08', '2016-03-02 02:20:08'),
(6, NULL, NULL, 'album_photo', 0, 5934, '5934/5934_14569054491129566121.png', 'png', '14569054491129566121', 'image/png', 'image', 171045, '92f2b2014d0c9b98c5e707a3d8728b042d457a2c', 0, '2016-03-02 02:56:52', '2016-03-02 02:57:29'),
(7, 6, 'product_profile', 'album_photo', 5934, 5934, '5934/5934_14569054491282144097.jpg', 'jpg', '5934_14569054491282144097.jpg', 'image/jpeg', 'image', 0, '', 0, '2016-03-02 02:57:29', '2016-03-02 02:57:29'),
(8, 6, 'product_thumb', 'album_photo', 5934, 5934, '5934/5934_1456905449356964216.jpg', 'jpg', '5934_1456905449356964216.jpg', 'image/jpeg', 'image', 0, '', 0, '2016-03-02 02:57:29', '2016-03-02 02:57:29'),
(9, 6, 'product_icon', 'album_photo', 5934, 5934, '5934/5934_1456905449566942271.jpg', 'jpg', '5934_1456905449566942271.jpg', 'image/jpeg', 'image', 0, '', 0, '2016-03-02 02:57:29', '2016-03-02 02:57:29'),
(10, NULL, NULL, 'album_photo', NULL, -1, '-1/14574381223056debdaa2c5df2_74461946.jpg', 'jpg', 'acidrain2.jpg', 'image/jpeg', '', 254360, '11d91eab81c4b8a9f69647dd292e8b0fe35a41b4', 0, '2016-03-08 06:55:22', '2016-03-08 06:55:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `store_delivery_addresses`
--
ALTER TABLE `store_delivery_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_delivery_addresses_order_id_foreign` (`order_id`);

--
-- Indexes for table `store_orders`
--
ALTER TABLE `store_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_order_delivery_info`
--
ALTER TABLE `store_order_delivery_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_order_items`
--
ALTER TABLE `store_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_order_items_product_id_foreign` (`product_id`),
  ADD KEY `store_order_items_order_id_foreign` (`order_id`);

--
-- Indexes for table `store_order_status_log`
--
ALTER TABLE `store_order_status_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_order_transactions`
--
ALTER TABLE `store_order_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_products`
--
ALTER TABLE `store_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_products_category_id_foreign` (`category_id`),
  ADD KEY `store_products_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `store_product_categories`
--
ALTER TABLE `store_product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_product_features`
--
ALTER TABLE `store_product_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_product_features_pr_id_foreign` (`pr_id`);

--
-- Indexes for table `store_product_reviews`
--
ALTER TABLE `store_product_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_product_shipping_cost`
--
ALTER TABLE `store_product_shipping_cost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_product_shipping_countries`
--
ALTER TABLE `store_product_shipping_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_product_shipping_regions`
--
ALTER TABLE `store_product_shipping_regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_product_statics`
--
ALTER TABLE `store_product_statics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_storage_files`
--
ALTER TABLE `store_storage_files`
  ADD PRIMARY KEY (`file_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `store_delivery_addresses`
--
ALTER TABLE `store_delivery_addresses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `store_orders`
--
ALTER TABLE `store_orders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `store_order_delivery_info`
--
ALTER TABLE `store_order_delivery_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `store_order_items`
--
ALTER TABLE `store_order_items`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `store_order_status_log`
--
ALTER TABLE `store_order_status_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=108;
--
-- AUTO_INCREMENT for table `store_order_transactions`
--
ALTER TABLE `store_order_transactions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_products`
--
ALTER TABLE `store_products`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `store_product_categories`
--
ALTER TABLE `store_product_categories`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `store_product_features`
--
ALTER TABLE `store_product_features`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `store_product_reviews`
--
ALTER TABLE `store_product_reviews`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `store_product_shipping_cost`
--
ALTER TABLE `store_product_shipping_cost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `store_product_shipping_countries`
--
ALTER TABLE `store_product_shipping_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `store_product_statics`
--
ALTER TABLE `store_product_statics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `store_storage_files`
--
ALTER TABLE `store_storage_files`
  MODIFY `file_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `store_delivery_addresses`
--
ALTER TABLE `store_delivery_addresses`
  ADD CONSTRAINT `store_delivery_addresses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `store_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_order_items`
--
ALTER TABLE `store_order_items`
  ADD CONSTRAINT `store_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `store_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_products`
--
ALTER TABLE `store_products`
  ADD CONSTRAINT `store_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `store_product_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_products_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `store_product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_product_features`
--
ALTER TABLE `store_product_features`
  ADD CONSTRAINT `store_product_features_pr_id_foreign` FOREIGN KEY (`pr_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
