-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2022 at 08:37 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bimarket`
--
CREATE DATABASE IF NOT EXISTS `bimarket` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bimarket`;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `co_id` int(11) NOT NULL,
  `co_name` varchar(50) NOT NULL,
  `co_website` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `o_id` int(11) NOT NULL,
  `o_date` date NOT NULL,
  `bill_no` varchar(50) NOT NULL,
  `sender_supp_id` int(11) NOT NULL,
  `rec_supp_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `pay_date` date NOT NULL,
  `pay_mode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `o_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `qnt` float NOT NULL,
  `sell_price` float NOT NULL,
  `gst%` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_mode`
--

CREATE TABLE `payment_mode` (
  `pay_mode_id` int(11) NOT NULL,
  `pay_mode` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `prd_id` int(11) NOT NULL,
  `prd_name` varchar(200) NOT NULL,
  `prd_company` int(11) NOT NULL,
  `prd_desc` varchar(100) NOT NULL,
  `prd_img_link` varchar(100) NOT NULL,
  `gst%` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supp_id` int(11) NOT NULL,
  `supp_name` varchar(100) NOT NULL,
  `gstin` varchar(30) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phone_no` varchar(30) NOT NULL,
  `shop_bill_terms` varchar(200) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pincode_serving` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `supp_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `sell_price` float NOT NULL,
  `qnt` float NOT NULL,
  `s_p_desc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `usr_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `supp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`co_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `pay_mode` (`pay_mode`),
  ADD KEY `rec_supp_id` (`rec_supp_id`),
  ADD KEY `sender_supp_id` (`sender_supp_id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD KEY `o_id` (`o_id`),
  ADD KEY `prd_id` (`prd_id`);

--
-- Indexes for table `payment_mode`
--
ALTER TABLE `payment_mode`
  ADD PRIMARY KEY (`pay_mode_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prd_id`),
  ADD KEY `prd_company` (`prd_company`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supp_id`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD KEY `prd_id` (`prd_id`),
  ADD KEY `supp_id` (`supp_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`),
  ADD KEY `supp_id` (`supp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `co_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_mode`
--
ALTER TABLE `payment_mode`
  MODIFY `pay_mode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`pay_mode`) REFERENCES `payment_mode` (`pay_mode_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`rec_supp_id`) REFERENCES `suppliers` (`supp_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`sender_supp_id`) REFERENCES `suppliers` (`supp_id`);

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `orders` (`o_id`),
  ADD CONSTRAINT `order_products_ibfk_2` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`prd_company`) REFERENCES `company` (`co_id`);

--
-- Constraints for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD CONSTRAINT `supplier_products_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`),
  ADD CONSTRAINT `supplier_products_ibfk_2` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
