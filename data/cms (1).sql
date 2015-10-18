-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2015 at 06:43 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id Primary key',
  `parent` int(11) DEFAULT NULL COMMENT 'Date update',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `created` int(10) NOT NULL,
  `changed` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL,
  `taxonomy_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`taxonomy_id`),
  UNIQUE KEY `alias_UNIQUE` (`slug`),
  KEY `fk_category_taxonomy1_idx` (`taxonomy_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parent`, `name`, `slug`, `excerpt`, `created`, `changed`, `title`, `keyword`, `description`, `sort`, `status`, `taxonomy_id`) VALUES
(1, 0, 'root', 'abc', NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 1),
(2, 1, 'child level 1', 'abc11', NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 1),
(6, 1, 'child level 2', 'abc1112', '', 0, 1444840613, '', '', '', 0, 1, 1),
(7, 6, 'child level 3', 'abc1112www', NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 1),
(8, 0, 'root 1', 'abc1112www123123', NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 1),
(9, 0, 'sdfsdfdsf', 'sdfsdfds', '', 1444668047, 1444668047, '', '', '', 0, 1, 1),
(10, 1, 'demo', 'demo', '', 1444668229, 1444668229, '', '', '', 0, 0, 2),
(11, 6, 'demo a11', 'demo-a11', '', 1444749388, 1444753963, 'abc11', 'abc11', 'abc11', 11, 0, 1),
(12, NULL, 'ajax', 'ajax', '', 1445161823, 1445161823, '', '', '', 0, 1, 1),
(13, NULL, 'test ajax', 'test-ajax', '', 1445162008, 1445162008, '', '', '', 0, 1, 1),
(14, NULL, 'hhhhh', 'hhhhh', '', 1445162099, 1445162099, '', '', '', 0, 1, 1),
(15, NULL, '24234', '234234234', '', 1445162178, 1445162178, '', '', '', 0, 1, 1),
(16, NULL, 'sd', 'asd', '', 1445162271, 1445162271, '', '', '', 0, 1, 1),
(17, NULL, 'asd', 'asdasdsad', '', 1445162303, 1445162303, '', '', '', 0, 1, 1),
(18, NULL, 'asd', 'asdasdasd', '', 1445162359, 1445162359, '', '', '', 0, 1, 1),
(19, NULL, 'sd', '21321312', '', 1445162418, 1445162418, '', '', '', 0, 1, 1),
(20, NULL, 'sdjjj', 'jjjj', '', 1445162430, 1445162430, '', '', '', 0, 1, 1),
(21, NULL, '23423423434324234', '234234234234234234', '', 1445162475, 1445162475, '', '', '', 0, 1, 1),
(22, NULL, '23423423434324234', '2342342342342342341111', '', 1445162527, 1445162527, '', '', '', 0, 1, 1),
(23, NULL, '555', '5555', '', 1445162558, 1445162558, '', '', '', 0, 1, 1),
(24, NULL, '555', '555555', '', 1445162563, 1445162563, '', '', '', 0, 1, 1),
(25, NULL, '5544', '444', '', 1445162610, 1445162610, '', '', '', 0, 1, 1),
(26, NULL, '5544', '4444', '', 1445162615, 1445162615, '', '', '', 0, 1, 1),
(27, NULL, '1111', '111', '', 1445162672, 1445162672, '', '', '', 0, 1, 1),
(28, NULL, '443434', '34343', '', 1445162744, 1445162744, '', '', '', 0, 1, 1),
(29, NULL, 'asdf', 'a324e232', '', 1445162780, 1445162780, '', '', '', 0, 1, 1),
(30, NULL, 'asdf', 'a324e2322', '', 1445162789, 1445162789, '', '', '', 0, 1, 1),
(31, NULL, 'asdf', 'a324e23221', '', 1445162798, 1445162798, '', '', '', 0, 1, 1),
(32, NULL, 'ahgfd', 'sdfsdfsdfsf', '', 1445162881, 1445162881, '', '', '', 0, 1, 1),
(33, NULL, 'absdfwe', 're23423', '', 1445162949, 1445162949, '', '', '', 0, 1, 1),
(34, NULL, '34234234', '23423432', '', 1445163123, 1445163123, '', '', '', 0, 1, 1),
(35, NULL, 'jfjfj', 'fjjfg', '', 1445163292, 1445163292, '', '', '', 0, 1, 1),
(36, NULL, 'sdf', '5656', '', 1445163362, 1445163362, '', '', '', 0, 1, 1),
(37, NULL, 'sdf', '565676', '', 1445163368, 1445163368, '', '', '', 0, 1, 1),
(38, 0, '123123213', '123v123 1 312', '', 1445163560, 1445163560, '', '', '', 0, 1, 1),
(39, 0, '87643', '09876', '', 1445163580, 1445163580, '', '', '', 0, 1, 1),
(40, 0, '09876', '098761234', '', 1445163619, 1445163619, '', '', '', 0, 1, 1),
(41, 0, '09876', '0987612341234', '', 1445163626, 1445163626, '', '', '', 0, 1, 1),
(42, 0, '2423 23 b4b', 'b23v242342v4', '', 1445163740, 1445163740, '', '', '', 0, 1, 1),
(43, 0, '342', '234234234sdfsd', '', 1445163817, 1445163817, '', '', '', 0, 1, 1),
(44, 0, '342', '234234234sdfsd111', '', 1445163823, 1445163823, '', '', '', 0, 1, 1),
(45, 0, 'wer', 'werwerwe rw rwer ', '', 1445163842, 1445163842, '', '', '', 0, 1, 1),
(46, 0, '23423 234 ', ' 243 3weqwe', '', 1445163885, 1445163885, '', '', '', 0, 1, 1),
(47, 0, 'fsf s sf ', '12qewe', '', 1445163956, 1445163956, '', '', '', 0, 1, 1),
(48, 0, '23 24 ', '244243 24343', '', 1445163997, 1445163997, '', '', '', 0, 1, 1),
(49, 0, '124123', '12312312312', '', 1445164159, 1445164159, '', '', '', 0, 1, 1),
(50, 0, 'abc', 'abcaf', '', 1445164220, 1445164220, '', '', '', 0, 1, 1),
(51, 0, 'demo_123', 'demo_134', '', 1445164640, 1445164640, '', '', '', 0, 1, 1),
(52, 0, 'h test', 'htest', '', 1445165212, 1445165212, '', '', '', 0, 1, 1),
(53, 0, 'demo group', '123-group', '', 1445165399, 1445165399, '', '', '', 0, 1, 1),
(54, 0, 't11', '5111t', '', 1445177412, 1445177412, '', '', '', 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `author` varchar(255) NOT NULL COMMENT 'Author name',
  `created` int(10) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `language_code` varchar(2) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`product_id`,`user_id`),
  KEY `fk_comment_product1_idx` (`product_id`),
  KEY `fk_comment_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `name` varchar(60) NOT NULL,
  `mine` varchar(45) DEFAULT NULL,
  `size` varchar(45) DEFAULT NULL,
  `timestamp` int(10) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `highlight` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `product_id`, `type`, `name`, `mine`, `size`, `timestamp`, `status`, `highlight`) VALUES
(1, 2, 'product', 'demo', NULL, NULL, NULL, 1, NULL),
(2, 2, 'product', 'demo 2', NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `content` longtext,
  `image` varchar(60) NOT NULL,
  `created` int(10) NOT NULL,
  `modified` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `sort` int(10) DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `category_id` int(11) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`category_id`,`user_id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `alias_UNIQUE` (`slug`),
  KEY `fk_new_category1_idx` (`category_id`),
  KEY `fk_new_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment` int(11) NOT NULL,
  `total` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `customer_id` int(10) unsigned NOT NULL,
  `payment_method_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`customer_id`,`payment_method_id`),
  KEY `fk_order_customer1_idx` (`customer_id`),
  KEY `fk_order_payment_method1_idx` (`payment_method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quantity` int(11) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`order_id`,`product_id`),
  KEY `fk_order_detail_order1_idx` (`order_id`),
  KEY `fk_order_detail_product1_idx` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE IF NOT EXISTS `payment_method` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`resource_id`),
  KEY `fk_permission_resource1_idx` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `trademark_id` int(10) unsigned NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `content` longtext,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL,
  `modified` int(10) DEFAULT NULL,
  `weight` int(10) DEFAULT NULL,
  `sale` int(2) DEFAULT '0',
  `hot` int(1) DEFAULT '0',
  `sticky` int(1) DEFAULT '0',
  `promote` int(1) DEFAULT '0',
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `sort` int(10) DEFAULT '0',
  `cost` varchar(60) NOT NULL,
  `price` varchar(60) NOT NULL DEFAULT '0',
  `view` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`trademark_id`,`supplier_id`,`shop_id`,`category_id`,`user_id`),
  KEY `fk_product_trademark1_idx` (`trademark_id`),
  KEY `fk_product_supplier1_idx` (`supplier_id`),
  KEY `fk_product_shop1_idx` (`shop_id`),
  KEY `fk_product_category1_idx` (`category_id`),
  KEY `fk_product_user1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `code`, `trademark_id`, `supplier_id`, `shop_id`, `category_id`, `name`, `slug`, `excerpt`, `content`, `status`, `created`, `modified`, `weight`, `sale`, `hot`, `sticky`, `promote`, `color`, `size`, `sort`, `cost`, `price`, `view`, `quantity`, `user_id`) VALUES
(2, 'abc', 1, 1, 1, 1, 'asd asd', 'ad', NULL, NULL, 0, 0, NULL, NULL, 0, 0, 0, 0, NULL, NULL, 100, '175847', '500000', NULL, 1, 1),
(3, '3', 1, 1, 1, 1, '324234', '234', NULL, NULL, 0, 0, NULL, NULL, 0, 0, 0, 0, NULL, NULL, 20, '1457824', '15000000', NULL, 1, 1),
(4, '', 1, 1, 1, 2, '324234wer', '234werw', NULL, NULL, 0, 0, NULL, NULL, 0, 0, 0, 0, NULL, NULL, 10, '1457824', '15000000', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(45) NOT NULL,
  `controller` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '1:Active - 0: inactive',
  `weight` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`permission_id`,`role_id`),
  KEY `fk_role_permission_permission1_idx` (`permission_id`),
  KEY `fk_role_permission_role1_idx` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `fax` varchar(12) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `name`, `address`, `phone`, `fax`, `status`) VALUES
(1, 'shop 1', '', NULL, NULL, 1),
(2, 'asdasda', '', '', '', 1),
(3, 'demo', '', '', '', 1),
(4, 'test', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `companyname` varchar(255) DEFAULT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `note` text,
  `account` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `phone`, `address`, `email`, `companyname`, `tax`, `note`, `account`, `status`) VALUES
(1, 'supplier', '', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 'supplier 1', '', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, 'sdf', '', '', '', '', '', '', '', 1),
(5, 'ahdsdf', '', '', '', '', '', '', '', 1),
(8, 'sdfdsf', '', '', '', '', '', '', '', 1),
(18, 'ssdf', '', '', '', '', '', '', '', 1),
(19, 'ssdf1212', '', '', '', '', '', '', '', 1),
(20, '345345345', '', '', '', '', '', '', '', 1),
(21, '23423', '', '', '', '', '', '', '', 1),
(22, 'werwe', '', '', '', '', '', '', '', 1),
(23, 'werwerwer', '', '', '', '', '', '', '', 1),
(24, 'ewsdfsdf', '', '', '', '', '', '', '', 1),
(25, 'abcs', '', '', '', '', '', '', '', 1),
(26, 'demo demo', '', '', '', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `taxonomy`
--

CREATE TABLE IF NOT EXISTS `taxonomy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `slug` varchar(45) NOT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias_UNIQUE` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `taxonomy`
--

INSERT INTO `taxonomy` (`id`, `name`, `slug`, `status`) VALUES
(1, 'Sản Phẩm', 'product', 0),
(2, 'Tin tức', 'news', 1),
(3, 'sdfsdfsdf1', 'ssss1', 1),
(4, '123', '12312313', 1);

-- --------------------------------------------------------

--
-- Table structure for table `trademark`
--

CREATE TABLE IF NOT EXISTS `trademark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `trademark`
--

INSERT INTO `trademark` (`id`, `name`, `slug`, `title`, `keyword`, `description`, `status`) VALUES
(1, 'trademark', '', NULL, NULL, NULL, 1),
(2, 'abc', 'abc', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `fullname` varchar(45) NOT NULL,
  `alias` varchar(45) DEFAULT NULL,
  `birthday` int(10) DEFAULT NULL,
  `sex` int(1) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '1: active',
  `created` int(10) NOT NULL,
  `changed` int(10) NOT NULL,
  `avartar` varchar(45) DEFAULT NULL,
  `token` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `social` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `salt`, `fullname`, `alias`, `birthday`, `sex`, `address`, `active`, `created`, `changed`, `avartar`, `token`, `status`, `social`) VALUES
(1, 'vanlong200880@gmail.com', '123456', '', 'Administrator', '', NULL, NULL, NULL, 1, 1405616005, 0, '', '', 1, 0),
(11, 'saa@gmail.com', 'c40cf9f0c8d7a20eca067aa83b5a102bbf905fae', 'tq~X(>k/Z2]2?H1DUPK{/E(^LU._>}8e''+O&VxKB.v7r^!I=Ib', '111111', '', 0, 0, '11111111', 1, 1441442752, 1443018955, '1_660x0-1443018955.jpg', '9dafc95190ed7074dc382c9385b8a216', 1, 0),
(19, '', 'ace83cacc7026b2331912bee79e801f1d8f558fd', '26i"&cupNJ''dktCqq"F|~=7<''dOSGd9wr;,/jr1L~L"WrQfb5!', 'ádsa', '', 0, 1, '', 1, 1443024788, 1443024788, '', '5a37535eb2863129fe502947e8ce0102', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_rid` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`role_rid`,`user_id`),
  KEY `fk_user_role_role1_idx` (`role_rid`),
  KEY `fk_user_role_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_taxonomy1` FOREIGN KEY (`taxonomy_id`) REFERENCES `taxonomy` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_new_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_new_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_order_payment_method1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_order_detail_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_order_detail_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `fk_permission_resource1` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_shop1` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_supplier1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_trademark1` FOREIGN KEY (`trademark_id`) REFERENCES `trademark` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `fk_role_permission_permission1` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_role_permission_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_user_role_role1` FOREIGN KEY (`role_rid`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_role_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
