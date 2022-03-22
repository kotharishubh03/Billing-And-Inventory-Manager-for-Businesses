-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2022 at 08:32 PM
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
-- Database: `billmanager`
--
CREATE DATABASE IF NOT EXISTS `billmanager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `billmanager`;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cus_id` int(11) NOT NULL,
  `cus_name` varchar(30) NOT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `gstin` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `financialyear`
--

CREATE TABLE `financialyear` (
  `fy_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `short_form` varchar(10) NOT NULL
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
-- Table structure for table `pre_bal_product`
--

CREATE TABLE `pre_bal_product` (
  `id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `fy_id` int(11) NOT NULL,
  `qnt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `prd_id` int(11) NOT NULL,
  `prd_name` varchar(50) NOT NULL,
  `unit` int(11) DEFAULT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `mrp` float DEFAULT NULL,
  `selling_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products_supplier`
--

CREATE TABLE `products_supplier` (
  `id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `cost_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `pur_id` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `pur_date` date NOT NULL,
  `bill_no` varchar(20) NOT NULL,
  `total` int(11) NOT NULL,
  `pay_date` date DEFAULT NULL,
  `pay_mode_id` int(11) DEFAULT 7,
  `tax5` float NOT NULL,
  `gst5` float NOT NULL,
  `tax12` float NOT NULL,
  `gst12` float NOT NULL,
  `tax18` float NOT NULL,
  `gst18` float NOT NULL,
  `tax28` float NOT NULL,
  `gst28` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product`
--

CREATE TABLE `purchase_product` (
  `pur_prd_id` int(11) NOT NULL,
  `pur_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `qnt` int(11) NOT NULL,
  `cost_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `bill_no` varchar(20) NOT NULL,
  `sale_date` date NOT NULL,
  `total` float NOT NULL,
  `discount` float NOT NULL,
  `pay_type` int(11) NOT NULL DEFAULT 7,
  `pay_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sales_product`
--

CREATE TABLE `sales_product` (
  `sale_prd_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `qnt` int(11) NOT NULL,
  `sell_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `shop_info`
--

CREATE TABLE `shop_info` (
  `s_key` varchar(30) NOT NULL,
  `value` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supp_id` int(11) NOT NULL,
  `supp_name` varchar(50) NOT NULL,
  `gstno` varchar(25) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `supp_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_payment`
--

CREATE TABLE `suppliers_payment` (
  `payment_id` int(11) NOT NULL,
  `pay_type` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date NOT NULL,
  `chq_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `supp_pay_purchase`
--

CREATE TABLE `supp_pay_purchase` (
  `pur_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `supp_pre_bal`
--

CREATE TABLE `supp_pre_bal` (
  `id` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `fy_id` int(11) NOT NULL,
  `amt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--
INSERT INTO `financialyear` (`fy_id`, `start_date`, `end_date`, `short_form`) VALUES
(1, '2010-04-01', '2011-03-31', '2010-11'),
(2, '2011-04-01', '2012-03-31', '2011-12'),
(3, '2012-04-01', '2013-03-31', '2012-13'),
(4, '2013-04-01', '2014-03-31', '2013-14'),
(5, '2014-04-01', '2015-03-31', '2014-15'),
(6, '2015-04-01', '2016-03-31', '2015-16'),
(7, '2016-04-01', '2017-03-31', '2016-17'),
(8, '2017-04-01', '2018-03-31', '2017-18'),
(9, '2018-04-01', '2019-03-31', '2018-19'),
(10, '2019-04-01', '2020-03-31', '2019-20'),
(11, '2020-04-01', '2021-03-31', '2020-21'),
(12, '2021-04-01', '2022-03-31', '2021-22'),
(13, '2022-04-01', '2023-03-31', '2022-23'),
(14, '2023-04-01', '2024-03-31', '2023-24'),
(15, '2024-04-01', '2025-03-31', '2024-25'),
(16, '2025-04-01', '2026-03-31', '2025-26'),
(17, '2026-04-01', '2027-03-31', '2026-27'),
(18, '2027-04-01', '2028-03-31', '2027-28'),
(19, '2028-04-01', '2029-03-31', '2028-29'),
(20, '2029-04-01', '2030-03-31', '2029-30');

INSERT INTO `payment_mode` (`pay_mode_id`, `pay_mode`) VALUES
(1, 'Bank'),
(2, 'Cash'),
(3, 'Bharat Pay'),
(4, 'Paytm'),
(5, 'NEFT / IMPS / RTGS'),
(6, 'Discount'),
(7, 'Not Paid');

INSERT INTO `shop_info` (`s_key`, `value`) VALUES
('shop_address', ' '),
('shop_bill_terms', ' '),
('shop_email', ' '),
('shop_gstin', ' '),
('shop_name', ' '),
('shop_telephone', ' ');
--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cus_id`),
  ADD UNIQUE KEY `cus_name` (`cus_name`);

--
-- Indexes for table `financialyear`
--
ALTER TABLE `financialyear`
  ADD PRIMARY KEY (`fy_id`);

--
-- Indexes for table `payment_mode`
--
ALTER TABLE `payment_mode`
  ADD PRIMARY KEY (`pay_mode_id`);

--
-- Indexes for table `pre_bal_product`
--
ALTER TABLE `pre_bal_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prd_id` (`prd_id`),
  ADD KEY `fy_id` (`fy_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prd_id`);

--
-- Indexes for table `products_supplier`
--
ALTER TABLE `products_supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prd_id` (`prd_id`),
  ADD KEY `supp_id` (`supp_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`pur_id`),
  ADD KEY `supp_id` (`supp_id`),
  ADD KEY `pay_mode_id` (`pay_mode_id`);

--
-- Indexes for table `purchase_product`
--
ALTER TABLE `purchase_product`
  ADD PRIMARY KEY (`pur_prd_id`),
  ADD KEY `prd_id` (`prd_id`),
  ADD KEY `pur_id` (`pur_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `pay_type` (`pay_type`),
  ADD KEY `cus_id` (`cus_id`);

--
-- Indexes for table `sales_product`
--
ALTER TABLE `sales_product`
  ADD PRIMARY KEY (`sale_prd_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `prd_id` (`prd_id`);

--
-- Indexes for table `shop_info`
--
ALTER TABLE `shop_info`
  ADD PRIMARY KEY (`s_key`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supp_id`);

--
-- Indexes for table `suppliers_payment`
--
ALTER TABLE `suppliers_payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `supp_pay_purchase`
--
ALTER TABLE `supp_pay_purchase`
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `pur_id` (`pur_id`);

--
-- Indexes for table `supp_pre_bal`
--
ALTER TABLE `supp_pre_bal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fy_id` (`fy_id`),
  ADD KEY `supp_id` (`supp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cus_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financialyear`
--
ALTER TABLE `financialyear`
  MODIFY `fy_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_mode`
--
ALTER TABLE `payment_mode`
  MODIFY `pay_mode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_bal_product`
--
ALTER TABLE `pre_bal_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_supplier`
--
ALTER TABLE `products_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `pur_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_product`
--
ALTER TABLE `purchase_product`
  MODIFY `pur_prd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_product`
--
ALTER TABLE `sales_product`
  MODIFY `sale_prd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers_payment`
--
ALTER TABLE `suppliers_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supp_pre_bal`
--
ALTER TABLE `supp_pre_bal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pre_bal_product`
--
ALTER TABLE `pre_bal_product`
  ADD CONSTRAINT `pre_bal_product_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`),
  ADD CONSTRAINT `pre_bal_product_ibfk_2` FOREIGN KEY (`fy_id`) REFERENCES `financialyear` (`fy_id`);

--
-- Constraints for table `products_supplier`
--
ALTER TABLE `products_supplier`
  ADD CONSTRAINT `products_supplier_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`),
  ADD CONSTRAINT `products_supplier_ibfk_2` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`),
  ADD CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`pay_mode_id`) REFERENCES `payment_mode` (`pay_mode_id`);

--
-- Constraints for table `purchase_product`
--
ALTER TABLE `purchase_product`
  ADD CONSTRAINT `purchase_product_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`),
  ADD CONSTRAINT `purchase_product_ibfk_2` FOREIGN KEY (`pur_id`) REFERENCES `purchase` (`pur_id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`pay_type`) REFERENCES `payment_mode` (`pay_mode_id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`cus_id`) REFERENCES `customers` (`cus_id`);

--
-- Constraints for table `sales_product`
--
ALTER TABLE `sales_product`
  ADD CONSTRAINT `sales_product_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`),
  ADD CONSTRAINT `sales_product_ibfk_2` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`);

--
-- Constraints for table `supp_pay_purchase`
--
ALTER TABLE `supp_pay_purchase`
  ADD CONSTRAINT `supp_pay_purchase_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `suppliers_payment` (`payment_id`),
  ADD CONSTRAINT `supp_pay_purchase_ibfk_2` FOREIGN KEY (`pur_id`) REFERENCES `purchase` (`pur_id`);

--
-- Constraints for table `supp_pre_bal`
--
ALTER TABLE `supp_pre_bal`
  ADD CONSTRAINT `supp_pre_bal_ibfk_1` FOREIGN KEY (`fy_id`) REFERENCES `financialyear` (`fy_id`),
  ADD CONSTRAINT `supp_pre_bal_ibfk_2` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
