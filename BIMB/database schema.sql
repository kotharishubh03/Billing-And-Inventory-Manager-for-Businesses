-- MariaDB dump 10.17  Distrib 10.4.10-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: billmanager
-- ------------------------------------------------------
-- Server version	10.4.10-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `cus_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_name` varchar(30) NOT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `gstin` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cus_id`),
  UNIQUE KEY `cus_name` (`cus_name`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `financialyear`
--

DROP TABLE IF EXISTS `financialyear`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `financialyear` (
  `fy_id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `short_form` varchar(10) NOT NULL,
  PRIMARY KEY (`fy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_mode`
--

DROP TABLE IF EXISTS `payment_mode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_mode` (
  `pay_mode_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_mode` varchar(30) NOT NULL,
  PRIMARY KEY (`pay_mode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pre_bal_product`
--

DROP TABLE IF EXISTS `pre_bal_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_bal_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prd_id` int(11) NOT NULL,
  `fy_id` int(11) NOT NULL,
  `qnt` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prd_id` (`prd_id`),
  KEY `fy_id` (`fy_id`),
  CONSTRAINT `pre_bal_product_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`),
  CONSTRAINT `pre_bal_product_ibfk_2` FOREIGN KEY (`fy_id`) REFERENCES `financialyear` (`fy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `prd_id` int(11) NOT NULL AUTO_INCREMENT,
  `prd_name` varchar(50) NOT NULL,
  `unit` int(11) DEFAULT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `mrp` float DEFAULT NULL,
  `selling_price` float DEFAULT NULL,
  PRIMARY KEY (`prd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products_supplier`
--

DROP TABLE IF EXISTS `products_supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prd_id` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `cost_price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prd_id` (`prd_id`),
  KEY `supp_id` (`supp_id`),
  CONSTRAINT `products_supplier_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`),
  CONSTRAINT `products_supplier_ibfk_2` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=437 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase` (
  `pur_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `gst28` float NOT NULL,
  PRIMARY KEY (`pur_id`),
  KEY `supp_id` (`supp_id`),
  KEY `pay_mode_id` (`pay_mode_id`),
  CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`),
  CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`pay_mode_id`) REFERENCES `payment_mode` (`pay_mode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_product`
--

DROP TABLE IF EXISTS `purchase_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_product` (
  `pur_prd_id` int(11) NOT NULL AUTO_INCREMENT,
  `pur_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `qnt` int(11) NOT NULL,
  `cost_price` float NOT NULL,
  PRIMARY KEY (`pur_prd_id`),
  KEY `prd_id` (`prd_id`),
  KEY `pur_id` (`pur_id`),
  CONSTRAINT `purchase_product_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`),
  CONSTRAINT `purchase_product_ibfk_2` FOREIGN KEY (`pur_id`) REFERENCES `purchase` (`pur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=426 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_id` int(11) NOT NULL,
  `bill_no` varchar(20) NOT NULL,
  `sale_date` date NOT NULL,
  `total` float NOT NULL,
  `discount` float NOT NULL,
  `pay_type` int(11) NOT NULL DEFAULT 7,
  `pay_date` date NOT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `pay_type` (`pay_type`),
  KEY `cus_id` (`cus_id`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`pay_type`) REFERENCES `payment_mode` (`pay_mode_id`),
  CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`cus_id`) REFERENCES `customers` (`cus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3076 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_product`
--

DROP TABLE IF EXISTS `sales_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_product` (
  `sale_prd_id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `qnt` int(11) NOT NULL,
  `sell_price` float NOT NULL,
  PRIMARY KEY (`sale_prd_id`),
  KEY `sale_id` (`sale_id`),
  KEY `prd_id` (`prd_id`),
  CONSTRAINT `sales_product_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`),
  CONSTRAINT `sales_product_ibfk_2` FOREIGN KEY (`prd_id`) REFERENCES `products` (`prd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shop_info`
--

DROP TABLE IF EXISTS `shop_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_info` (
  `s_key` varchar(30) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`s_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `supp_pay_purchase`
--

DROP TABLE IF EXISTS `supp_pay_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supp_pay_purchase` (
  `pur_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  KEY `payment_id` (`payment_id`),
  KEY `pur_id` (`pur_id`),
  CONSTRAINT `supp_pay_purchase_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `suppliers_payment` (`payment_id`),
  CONSTRAINT `supp_pay_purchase_ibfk_2` FOREIGN KEY (`pur_id`) REFERENCES `purchase` (`pur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `supp_pre_bal`
--

DROP TABLE IF EXISTS `supp_pre_bal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supp_pre_bal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_id` int(11) NOT NULL,
  `fy_id` int(11) NOT NULL,
  `amt` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fy_id` (`fy_id`),
  KEY `supp_id` (`supp_id`),
  CONSTRAINT `supp_pre_bal_ibfk_1` FOREIGN KEY (`fy_id`) REFERENCES `financialyear` (`fy_id`),
  CONSTRAINT `supp_pre_bal_ibfk_2` FOREIGN KEY (`supp_id`) REFERENCES `suppliers` (`supp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `supp_id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(50) NOT NULL,
  `gstno` varchar(25) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `supp_active` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`supp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `suppliers_payment`
--

DROP TABLE IF EXISTS `suppliers_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers_payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_type` int(11) NOT NULL,
  `supp_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date NOT NULL,
  `chq_no` varchar(50) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-10 23:15:11
