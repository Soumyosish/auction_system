-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 08:13 AM
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
-- Database: `auction_system`
--
CREATE DATABASE IF NOT EXISTS `auction_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `auction_system`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE `auctions` (
  `auction_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `starting_price` decimal(10,2) NOT NULL,
  `current_price` decimal(10,2) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('active','ended','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`auction_id`, `title`, `description`, `image_url`, `seller_id`, `category_id`, `starting_price`, `current_price`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Abstract Oil Painting', 'A stunning contemporary abstract painting featuring bold strokes of vibrant blues and reds, creating a dynamic composition on canvas.', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5', 2, 5, 2000.00, 3000.00, '2025-04-16 16:48:09', '2025-04-30 16:09:03', 'active', '2025-04-13 19:39:03', '2025-04-16 16:48:09'),
(2, 'Rare Comic Book', 'First edition superhero comic in mint condition', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSi9omD7eLDa9gqyqYiEE_6T04593Q7x48sTQ&amp;s', 3, 6, 750.00, 750.00, '2025-04-16 16:48:35', '2025-04-25 16:12:29', 'active', '2025-04-13 19:42:29', '2025-04-16 16:48:35'),
(3, 'Vintage Baseball Card', 'Rare baseball card featuring legendary player', 'https://wealthgang.com/wp-content/uploads/2024/10/baseball-hero-1200x710.jpg', 3, 6, 1200.00, 1200.00, '2025-04-16 16:52:43', '2025-04-30 16:13:31', 'active', '2025-04-13 19:43:31', '2025-04-16 16:52:43'),
(4, 'Ancient Coin Collection', 'Collection of authentic Roman coins', 'https://images.unsplash.com/photo-1605792657660-596af9009e82', 3, 6, 2500.00, 2600.00, '2025-04-16 16:50:06', '2025-04-30 16:14:36', 'active', '2025-04-13 19:44:36', '2025-04-16 16:50:06'),
(5, 'Movie Memorabilia', 'Original movie props and posters', 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf', 3, 6, 850.00, 850.00, '2025-04-16 16:50:19', '2025-04-25 16:15:25', 'active', '2025-04-13 19:45:25', '2025-04-16 16:50:19'),
(6, 'Vintage Action Figure', 'Rare action figure in original packaging', 'https://images.unsplash.com/photo-1558507334-57300f59f0bd', 3, 6, 4500.00, 4600.00, '2025-04-16 16:50:39', '2025-04-30 16:16:15', 'active', '2025-04-13 19:46:15', '2025-04-16 16:50:39'),
(7, 'Rare Stamp Collection', 'Collection of rare postal stamps', 'https://images.unsplash.com/photo-1584727638096-042c45049ebe', 3, 6, 1800.00, 1800.00, '2025-04-16 16:50:59', '2025-04-29 16:17:01', 'active', '2025-04-13 19:47:01', '2025-04-16 16:50:59'),
(8, 'Signed Album', 'Vinyl album with authentic artist signature', 'https://images.unsplash.com/photo-1571330735066-03aaa9429d89', 3, 6, 650.00, 650.00, '2025-04-16 16:53:36', '2025-04-25 16:17:49', 'active', '2025-04-13 19:47:49', '2025-04-16 16:53:36'),
(9, 'Vintage Camera', 'Classic mechanical camera in working condition', 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32', 3, 6, 9800.00, 9800.00, '2025-04-16 16:53:58', '2025-04-30 16:18:34', 'active', '2025-04-13 19:48:34', '2025-04-16 16:53:58'),
(10, 'Sports Memorabilia', 'Championship game-used equipment', 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211', 3, 6, 1100.00, 1100.00, '2025-04-16 16:54:11', '2025-04-29 16:19:35', 'active', '2025-04-13 19:49:35', '2025-04-16 16:54:11'),
(11, 'Limited Edition Print', 'Contemporary Numbered artist print with certificate', 'https://images.unsplash.com/photo-1579783483458-83d02161294e', 3, 6, 890.00, 1500.00, '2025-04-16 16:54:25', '2025-04-26 16:20:38', 'ended', '2025-04-13 19:50:38', '2025-04-16 16:54:25'),
(12, 'Modern Sculpture', 'An elegant stainless steel sculpture with flowing lines and negative space, exploring themes of movement and balance.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShWox6VjWb_mrvdyKdwwzQxTta3bMlb0SsAQ&amp;s', 2, 5, 4500.00, 4500.00, '2025-04-16 16:55:09', '2025-04-26 16:23:38', 'active', '2025-04-13 19:53:38', '2025-04-16 16:55:09'),
(13, 'Watercolor Landscape', 'A serene watercolor painting capturing a misty mountain landscape at dawn, with delicate color transitions and masterful technique.', 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9', 2, 5, 2800.00, 2800.02, '2025-04-16 16:55:28', '2025-04-26 16:24:31', 'active', '2025-04-13 19:54:31', '2025-04-16 16:55:28'),
(14, 'Contemporary Portrait', 'A striking modern portrait using mixed media, combining traditional oil painting with contemporary abstract elements.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTigcqrnbsofviMlb19nIEQ0tGKTkjstYgBiw&amp;s', 2, 5, 5600.00, 5600.00, '2025-04-16 16:57:43', '2025-04-30 16:25:23', 'active', '2025-04-13 19:55:23', '2025-04-16 16:57:43'),
(15, 'Bronze Statue', 'A classical bronze sculpture depicting human form in motion, showcasing exceptional craftsmanship and attention to detail.', 'https://images.unsplash.com/photo-1576020799627-aeac74d58064', 2, 5, 7800.00, 7800.00, '2025-04-16 16:58:03', '2025-04-26 16:26:14', 'active', '2025-04-13 19:56:14', '2025-04-16 16:58:03'),
(16, 'Latest Gaming Console', 'Next-gen gaming console with 4K capabilities', 'https://images.unsplash.com/photo-1486401899868-0e435ed85128', 2, 1, 4500.00, 4500.00, '2025-04-16 16:58:19', '2025-04-26 16:27:34', 'active', '2025-04-13 19:57:34', '2025-04-16 16:58:19'),
(17, '4K Smart TV', '65-inch QLED Smart TV with HDR', 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1', 2, 1, 89000.00, 89000.00, '2025-04-16 16:58:33', '2025-04-26 16:28:24', 'active', '2025-04-13 19:58:24', '2025-04-16 16:58:33'),
(18, 'Wireless Headphones', 'Noise cancelling wireless headphones', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e', 2, 1, 2500.00, 2500.00, '2025-04-16 16:58:54', '2025-04-30 16:29:15', 'active', '2025-04-13 19:59:15', '2025-04-16 16:58:54'),
(19, 'Professional Microphone', 'Studio quality USB microphone', 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc', 2, 1, 3500.00, 3500.00, '2025-04-16 16:59:09', '2025-04-26 16:30:18', 'active', '2025-04-13 20:00:18', '2025-04-16 16:59:09'),
(20, 'Graphic Card', 'High-end gaming graphics card', 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c', 2, 1, 7500.00, 7500.00, '2025-04-16 16:59:37', '2025-04-28 16:31:17', 'active', '2025-04-13 20:01:17', '2025-04-16 16:59:37'),
(21, 'Smart Watch', 'Latest smartwatch with health features', 'https://images.unsplash.com/photo-1544117519-31a4b719223d', 2, 1, 2800.00, 3000.00, '2025-04-16 16:59:52', '2025-04-26 16:32:58', 'active', '2025-04-13 20:02:58', '2025-04-16 16:59:52'),
(22, 'Smart Home Hub', 'Smart home control center', 'https://m.media-amazon.com/images/I/61tMoVGYglL._AC_UF1000,1000_QL80_.jpg', 2, 1, 18000.00, 18000.00, '2025-04-16 17:00:10', '2025-04-30 16:33:51', 'active', '2025-04-13 20:03:51', '2025-04-16 17:00:10'),
(23, 'Designer Handbag', 'Authentic leather designer handbag', 'https://images.unsplash.com/photo-1584917865442-de89df76afd3', 1, 2, 890.00, 890.00, '2025-04-16 17:41:20', '2025-04-26 11:08:46', 'active', '2025-04-13 14:38:46', '2025-04-16 17:41:20'),
(24, 'Luxury Sunglasses', 'Limited edition designer sunglasses', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083', 12, 2, 4500.00, 4500.00, '2025-04-16 17:41:34', '2025-04-30 11:09:33', 'active', '2025-04-13 14:39:33', '2025-04-16 17:41:34'),
(25, 'Designer Shoes', 'Iconic red-sole designer shoes', 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2', 2, 2, 6800.00, 7000.00, '2025-04-16 17:41:50', '2025-04-29 11:10:18', 'active', '2025-04-13 14:40:18', '2025-04-16 17:41:50'),
(26, 'Vintage Jacket', 'Classic vintage leather jacket', 'https://images.unsplash.com/photo-1551028719-00167b16eac5', 1, 2, 3400.00, 3400.00, '2025-04-16 17:42:03', '2025-04-26 11:11:01', 'active', '2025-04-13 14:41:01', '2025-04-16 17:42:03'),
(27, 'Designer Belt', 'Signature H buckle leather belt', 'https://dillibazar.co.in/wp-content/uploads/2015/10/hermes-orange-leather-belt.jpeg', 1, 2, 2900.00, 2900.00, '2025-04-16 17:42:15', '2025-04-30 11:11:46', 'active', '2025-04-13 14:41:46', '2025-04-16 17:42:15'),
(28, 'Limited Edition Watch', 'Limited edition luxury timepiece', 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3', 2, 2, 12000.00, 12000.00, '2025-04-16 17:42:35', '2025-04-30 11:12:54', 'active', '2025-04-13 14:42:54', '2025-04-16 17:42:35'),
(29, 'Luxury Wallet', 'Premium leather designer wallet', 'https://images.unsplash.com/photo-1627123424574-724758594e93', 10, 2, 4200.00, 4200.00, '2025-04-16 17:42:50', '2025-04-28 11:13:42', 'active', '2025-04-13 14:43:42', '2025-04-16 17:42:50'),
(30, 'Premium leather designer wallet', 'Beautifully crafted antique dining table', 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88', 1, 3, 12000.00, 12000.00, '2025-04-16 17:43:17', '2025-04-28 11:16:25', 'active', '2025-04-13 14:46:25', '2025-04-16 17:43:17'),
(31, 'Vintage Chandelier', 'Crystal chandelier with brass finish', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRS6TTCBH9igCQtaRTL07OOVn9fc4FO5nlzUw&amp;s', 12, 3, 8500.00, 8500.00, '2025-04-16 17:43:30', '2025-04-30 11:20:46', 'active', '2025-04-13 14:50:46', '2025-04-16 17:43:30'),
(32, 'Persian Carpet', 'Hand-knotted Persian carpet', 'https://images.unsplash.com/photo-1600166898405-da9535204843', 18, 3, 2200.00, 2200.00, '2025-04-16 17:44:02', '2025-04-28 11:21:47', 'active', '2025-04-13 14:51:47', '2025-04-16 17:44:02'),
(33, 'Antique Mirror', 'Ornate gold-framed mirror', 'https://images.unsplash.com/photo-1618220179428-22790b461013', 1, 3, 4800.00, 5000.00, '2025-04-16 17:44:17', '2025-04-30 11:22:51', 'active', '2025-04-13 14:52:51', '2025-04-16 17:44:17'),
(34, 'Vintage Armchair', 'Classic mid-century armchair', 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c', 3, 3, 9500.00, 9500.00, '2025-04-16 17:44:29', '2025-04-29 11:23:36', 'active', '2025-04-13 14:53:36', '2025-04-16 17:44:29'),
(35, 'Crystal Vase Set', 'Set of three crystal vases', 'https://images.unsplash.com/photo-1602028915047-37269d1a73f7', 2, 3, 4800.00, 4800.00, '2025-04-16 17:44:47', '2025-04-29 11:24:21', 'active', '2025-04-13 14:54:21', '2025-04-16 17:44:47'),
(36, 'Antique Clock', 'Grandfather clock', 'https://images.unsplash.com/photo-1415604934674-561df9abf539', 3, 3, 7500.00, 7500.00, '2025-04-16 17:45:30', '2025-04-30 11:25:14', 'active', '2025-04-13 14:55:14', '2025-04-16 17:45:30'),
(37, 'Art Deco Lamp', 'Art deco style table lamp', 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15', 4, 3, 580.00, 580.00, '2025-04-13 11:26:07', '2025-04-20 11:26:07', 'active', '2025-04-13 14:56:07', '2025-04-13 14:56:07'),
(38, 'Diamond Engagement Ring', 'Elegant solitaire engagement ring', 'https://images.unsplash.com/photo-1605100804763-247f67b3557e', 5, 8, 125000.00, 125000.00, '2025-04-16 17:00:22', '2025-04-26 16:58:20', 'active', '2025-04-13 20:28:20', '2025-04-16 17:00:22'),
(39, 'Pearl Necklace', 'Classic pearl strand necklace', 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338', 5, 8, 89000.00, 89000.00, '2025-04-16 17:00:37', '2025-04-30 16:59:03', 'active', '2025-04-13 20:29:03', '2025-04-16 17:00:37'),
(40, 'Sapphire Bracelet', 'Vintage sapphire tennis bracelet', 'https://images.unsplash.com/photo-1602751584552-8ba73aad10e1', 5, 8, 56000.00, 56000.00, '2025-04-16 17:00:59', '2025-04-28 16:59:46', 'active', '2025-04-13 20:29:46', '2025-04-16 17:00:59'),
(41, 'Gold Chain', 'Solid gold chain necklace', 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0', 5, 8, 78000.00, 78000.00, '2025-04-16 17:02:13', '2025-04-26 17:00:29', 'active', '2025-04-13 20:30:29', '2025-04-16 17:02:13'),
(42, 'Emerald Pendant', 'Colombian emerald pendant', 'https://www.justlilthings.in/cdn/shop/products/jltn0610.jpg?v=1736772814', 5, 8, 67000.00, 67000.00, '2025-04-13 17:01:20', '2025-04-27 17:01:20', 'active', '2025-04-13 20:31:20', '2025-04-13 20:31:20'),
(43, 'Diamond Tennis Bracelet', 'Diamond tennis bracelet', 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338', 5, 8, 158000.00, 158000.00, '2025-04-16 17:02:51', '2025-04-26 17:02:04', 'active', '2025-04-13 20:32:04', '2025-04-16 17:02:51'),
(44, 'Vintage Brooch', 'Art deco vintage brooch', 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e', 5, 8, 320000.00, 320000.00, '2025-04-13 17:02:52', '2025-04-27 17:02:52', 'active', '2025-04-13 20:32:52', '2025-04-13 20:32:52'),
(45, 'Pearl Earrings', 'South sea pearl earrings', 'https://everstylish.com/media/catalog/product/cache/fc1e90810f81d5d5f869fad087b9d639/j/e/jew1105163-2.jpg', 5, 8, 280000.00, 280000.00, '2025-04-13 17:03:38', '2025-04-27 17:03:38', 'active', '2025-04-13 20:33:38', '2025-04-13 20:33:38'),
(46, 'Indian Team Jersey', 'ADIDAS KIDS\\r\\nIndia Cricket ODI Jersey', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROyNi0EWRKFxg3hOOnD3GaAEQ8kZeiHLE6hw&amp;s', 5, 4, 4000.00, 4000.00, '2025-04-13 17:07:14', '2025-05-13 17:07:14', 'active', '2025-04-13 20:37:14', '2025-04-13 20:37:14'),
(47, 'Nike G.T. Hustle 3', 'The G.T. Hustle 3 can help you thrive in crunch time. With double-stacked Air Zoom cushioning providing bouncy horsepower', 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/ce383eba-f9dd-4a02-9617-709c22b9bc74/G.T.+HUSTLE+3.png', 5, 4, 15000.00, 15000.00, '2025-04-13 17:09:43', '2025-05-13 17:09:43', 'active', '2025-04-13 20:39:43', '2025-04-13 20:39:43'),
(48, 'Hockey stick', 'India&#039;s first hockey brand stick', 'https://m.media-amazon.com/images/I/31nYLa690RL._SR290,290_.jpg', 5, 4, 999.00, 999.00, '2025-04-13 17:11:07', '2025-05-13 17:11:07', 'active', '2025-04-13 20:41:07', '2025-04-13 20:41:07'),
(49, 'Mongoose bat', 'Heega V Shaped Mongoose Kashmir Willow Bat with Double Padded Classy Bat Cover', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcREVXN-xwlFoD4K23mgDwo8x8lkskfbD3FXkHOicQT44BJGlbUOrGf_fLsz-tNyslk_baJq1QBwSFEGNCezJICLqNqghyIpLvDNPaY3bgAMjMD5qw5EHBwwACg', 5, 4, 5999.00, 6001.00, '2025-04-14 07:03:51', '2025-05-13 17:12:10', 'active', '2025-04-13 20:42:10', '2025-04-14 07:03:51'),
(50, 'LUDO', 'India&#039;s Ludo king', 'https://play-lh.googleusercontent.com/u8XdLDFdd-VcbvuFNyDj90pjLvkANu6cJ0Oh41aqU7jCcBRH3WohKiKsY9TGBUEZpA', 5, 4, 600.00, 600.00, '2025-04-13 17:13:09', '2025-05-13 17:13:09', 'active', '2025-04-13 20:43:09', '2025-04-13 20:43:09'),
(51, 'Javelin', 'Buy is Wings Olympic Grade Javelin Throw Stick', 'https://m.media-amazon.com/images/I/31Rd02KQFWL._AC_UF894,1000_QL80_.jpg', 5, 4, 40000.00, 50000.00, '2025-04-14 15:53:07', '2025-04-27 17:14:46', 'active', '2025-04-13 20:44:46', '2025-04-14 15:53:07'),
(52, 'Cycles', 'Olympic Edition Bicycles', 'https://ep1.pinkbike.org/p5pb20996598/p5pb20996598.jpg', 5, 4, 39999.00, 39999.00, '2025-04-13 17:16:11', '2025-04-27 17:16:11', 'active', '2025-04-13 20:46:11', '2025-04-13 20:46:11'),
(53, 'Kawasaki ninja 300', 'Kawasaki first top model', 'https://cdn.bikedekho.com/processedimages/kawasaki/kawasaki-ninja/640X309/kawasaki-ninja647d72a380ec1.jpg', 5, 7, 375000.00, 375000.00, '2025-04-13 17:17:30', '2025-05-13 17:17:30', 'active', '2025-04-13 20:47:30', '2025-04-13 20:47:30'),
(54, 'TVs apache rr 310', 'The TVS Apache RR 310 is a sports motorcycle known for its performance and features. It boasts a 312.2 cc liquid-cooled engine producing 34 PS in Sport/Track mode and 25.8 PS in Urban/Rain mode.', 'https://cdn.bikedekho.com/processedimages/tvs/tvs-akula-310/source/tvs-akula-31066e8234da949d.jpg', 5, 7, 300000.00, 300000.00, '2025-04-13 17:19:01', '2025-05-13 17:19:01', 'active', '2025-04-13 20:49:01', '2025-04-13 20:49:01'),
(55, 'Toyota crysta', 'The Innova Crysta is a 7 seater 4 cylinder car and has length of 4735 mm, width of 1830 mm and a wheelbase of 2750 mm', 'https://imgd.aeplcdn.com/664x374/n/cw/ec/140809/innova-crysta-exterior-left-front-three-quarter.jpeg?isig=0&amp;q=80', 5, 7, 2000000.00, 2000000.00, '2025-04-13 17:20:16', '2025-05-13 17:20:16', 'active', '2025-04-13 20:50:16', '2025-04-13 20:50:16'),
(56, 'Maruti Alto K10', 'The Maruti Suzuki Alto K10 is a compact hatchback known for its fuel efficiency, affordability, and practical size', 'https://imgd.aeplcdn.com/310x174/n/cw/ec/127563/alto-k10-exterior-right-front-three-quarter-62.jpeg?isig=0&amp;q=80', 5, 7, 500000.00, 6000000.00, '2025-04-16 15:24:34', '2025-05-13 17:21:48', 'active', '2025-04-13 20:51:48', '2025-04-16 15:24:34'),
(57, 'Classic 350', 'The motorcycle&#039;s improved diameter brakes, upright riding position and wider seat ensure you stay comfortable, even after hours of riding.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkOlKSGjKBh4yn1o67qcYIjTDKnH8jfvxirA&amp;s', 5, 7, 1750000.00, 1750000.00, '2025-04-13 17:23:17', '2025-05-13 17:23:17', 'active', '2025-04-13 20:53:17', '2025-04-13 20:53:17'),
(59, 'A boat', 'a man on the boat', 'https://t3.ftcdn.net/jpg/01/40/47/42/240_F_140474254_8xWO8gem5DqbiKrGkOhghLI1MlDyoSRm.jpg', 12, 5, 700.00, 700.00, '2025-04-16 17:06:21', '2025-04-26 12:49:13', 'active', '2025-04-14 16:19:13', '2025-04-16 17:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bid_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `bidder_id` int(11) NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `bid_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bid_id`, `auction_id`, `bidder_id`, `bid_amount`, `bid_time`) VALUES
(1, 1, 3, 3000.00, '2025-04-13 19:40:26'),
(2, 11, 5, 900.00, '2025-04-13 20:34:15'),
(3, 4, 5, 2600.00, '2025-04-13 20:34:48'),
(4, 21, 5, 3000.00, '2025-04-13 20:35:08'),
(5, 11, 2, 1000.00, '2025-04-13 21:40:48'),
(10, 51, 12, 50000.00, '2025-04-14 15:53:07'),
(11, 11, 12, 1500.00, '2025-04-14 15:54:30'),
(13, 6, 2, 4600.00, '2025-04-16 15:21:21'),
(14, 56, 2, 6000000.00, '2025-04-16 15:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`) VALUES
(1, 'Electronics', 'Electronic devices and gadgets', '2025-04-13 19:27:55'),
(2, 'Fashion', 'Clothing, shoes, and accessories', '2025-04-13 19:27:55'),
(3, 'Home & Garden', 'Items for home improvement and gardening', '2025-04-13 19:27:55'),
(4, 'Sports', 'Sports equipment and memorabilia', '2025-04-13 19:27:55'),
(5, 'Art', 'Paintings, sculptures, and other art pieces', '2025-04-13 19:27:55'),
(6, 'Collectibles', 'Rare items and collectibles', '2025-04-13 19:27:55'),
(7, 'Vehicles', 'Cars, motorcycles, and other vehicles', '2025-04-13 19:27:55'),
(8, 'Jewelry', 'Fine jewelry and watches', '2025-04-13 19:27:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default_profile.jpg',
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `verification_token` varchar(64) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `profile_image`, `is_admin`, `created_at`, `updated_at`, `reset_token`, `reset_token_expires`, `verification_token`, `is_verified`) VALUES
(1, 'admin', 'admin@auction.com', '$2y$10$K.oUYWNRSYl1JFJ1Kqr6h.3IG1zNhC3NCyHRAgwpBbtUB.iJ0JDdi', 'Admin', 'User', 'default_profile.jpg', 1, '2025-04-13 19:27:55', '2025-04-13 19:27:55', NULL, NULL, NULL, 0),
(2, 'soumyosish', 'soumyosishpal.108@gmail.com', '$2y$10$BHZFcu5/7t2rivSUIP.39ufyHgA7XXbQ27uqUFlSBdz.jAFPBlb4S', 'Soumyosish', 'Pal', 'default_profile.jpg', 0, '2025-04-13 19:31:25', '2025-04-15 20:37:03', NULL, NULL, NULL, 0),
(3, 'Dip', 'dipanddiyapal@gmail.com', '$2y$10$1UvhpkMrCeXJ5tyzuYgE8euDrBngVcF2SDjdFENshEIDU5vgyNdP6', 'Dip', 'Pal', 'default_profile.jpg', 0, '2025-04-13 19:40:01', '2025-04-15 20:43:25', NULL, NULL, NULL, 0),
(5, 'seller1', 'seller123@gmail.com', '$2y$10$HvRFdINQt92BTyy/nv3Hr.ty3B9agW/kW2Ft0IzG6Yhu/KOt3a5NS', 'seller', 'seller', 'default_profile.jpg', 0, '2025-04-13 20:27:26', '2025-04-13 20:27:26', NULL, NULL, NULL, 0),
(10, 'admin2', 'admin@auctionn.com', '$2y$10$K.oUYWNRSYl1JFJ1Kqr6h.3IG1zNhC3NCyHRAgwpBbtUB.iJ0JDdi', 'Admin', 'User', 'default_profile.jpg', 1, '2025-04-13 13:57:55', '2025-04-13 13:57:55', NULL, NULL, NULL, 0),
(12, 'Shreya26', 'shreyatpathi2006@gmail.com', '$2y$10$oaJ4QjE.7HmmJmwkdsS2YObj6HAdTh5PFE7CEuqMeTkTEx6l3D4RW', 'Shreya', 'Tripathi', 'default_profile.jpg', 0, '2025-04-14 15:36:32', '2025-04-14 15:36:32', NULL, NULL, NULL, 0),
(13, 'Yashika', 'yashiika16@gmail.com', '$2y$10$cdMXBZtVWu.J.5WhowcZLOoW1r/QrQuIC/QqyvlKAyFYch7gcZNCK', 'Yashika', 'Mondal', 'default_profile.jpg', 0, '2025-04-15 12:29:00', '2025-04-15 12:29:00', NULL, NULL, NULL, 0),
(14, 'Shreya6006', 'khushitack88@gmail.com', '$2y$10$BC6UEeM2w/e/aECoZZlUuuxJL.WC9LxVVagu1va3cgXwpPaBc9U8.', 'Shreya', 'Tripathi', 'default_profile.jpg', 0, '2025-04-15 12:38:02', '2025-04-15 12:38:02', NULL, NULL, NULL, 0),
(18, 'Shreya12345', 'shreyatripathi036@gmail.com', '$2y$10$tpQdjtMp46.W9DSahwJ4eOR3zQpHaInriYX6Uk9ULup/fnY6BY8N.', 'Shreya', 'Tripathi', 'default_profile.jpg', 0, '2025-04-15 13:07:33', '2025-04-15 14:56:05', '0450d26e26dd17408f6840c811dfecdd02b31ccbb0afaa4dfde75c12d91a78cb', '2025-04-15 17:56:05', NULL, 0),
(19, 'mraj773929', 'mraj773929@gmail.com', '$2y$10$tLh4sjJ7zcBnV7mROQoyJekgHkFw9JARRHw3xrwzQocd8Ct7E/G2m', 'Manish', 'Raj', 'default_profile.jpg', 0, '2025-04-15 15:03:25', '2025-04-15 15:03:25', NULL, NULL, NULL, 0),
(23, 'Shreya', 'shreyatpathi2005@gmail.com', '$2y$10$Dc5cZuFoIGZW.uLqEaQBq.osW2hyG/kQXqhuB8IMu/cemFf0/m5ie', 'Shreya', 'Tripathi', 'default_profile.jpg', 0, '2025-04-15 15:25:55', '2025-04-15 15:26:22', NULL, NULL, NULL, 1),
(25, 'NewUser', 'debosmitaandsoumyosishpal@gmail.com', '$2y$10$oHWM5UOy88A1R54Q/zH2ueapSndtHtj8C.JyIKSiky05uXgd0R6k2', 'New', 'User', 'default_profile.jpg', 0, '2025-04-15 20:46:41', '2025-04-15 20:48:32', NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auction_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `auction_id` (`auction_id`),
  ADD KEY `bidder_id` (`bidder_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auctions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`auction_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`bidder_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
