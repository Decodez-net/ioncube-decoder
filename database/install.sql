-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2022 at 04:51 AM
-- Server version: 10.3.36-MariaDB-cll-lve
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `decodezn_demo1`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_api_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_count` int(11) NOT NULL,
  `order_encoded` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_decoded` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `order_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
