-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2015 at 06:03 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cms_deposit`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parent`, `name`, `slug`, `excerpt`, `created`, `changed`, `title`, `keyword`, `description`, `sort`, `status`, `taxonomy_id`) VALUES
(8, 0, 'root 1', 'abc1112www123123', NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 1),
(9, 0, 'sdfsdfdsf', 'sdfsdfds', '', 1444668047, 1444668047, '', '', '', 0, 1, 1),
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
(25, NULL, '5544', '444', '', 1445162610, 1445162610, '', '', '', 0, 0, 1),
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
(54, 0, 't11', '5111t', '', 1445177412, 1445177412, '', '', '', 0, 1, 1),
(55, 0, 'News', 'edit', '', 1445609017, 1445609017, '', '', '', 0, 0, 2),
(56, 0, 'news demo', 'news-demo', '', 1446131277, 1446131277, '', '', '', 0, 1, 2);

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
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `email` varchar(60) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `content` varchar(255) NOT NULL,
  `created` int(10) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `code` varchar(10) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `code`, `status`) VALUES
(1, 'Viet Nam', '084', 1),
(2, 'Japan', '081', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `address`, `phone`) VALUES
(1, 'abc', 'abc', '123');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `product_id`, `type`, `name`, `mine`, `size`, `timestamp`, `status`, `highlight`) VALUES
(2, 2, 'product', 'demo 2', NULL, NULL, NULL, 1, NULL),
(3, 1, 'product', '37106625-1445525515.png', NULL, NULL, 1445525515, 1, 1),
(4, 1, 'product', '37106625-1445525719.png', NULL, NULL, 1445525719, 1, 1),
(5, 1, 'product', '37106625-1445525732.png', NULL, NULL, 1445525732, 1, 1),
(6, 1, 'product', '37106625-1445525833.png', NULL, NULL, 1445525833, 1, 1),
(7, 1, 'product', '1439613478-1439611064-susu-3-1445525906.jpg', NULL, NULL, 1445525906, 1, 1),
(8, 1, 'product', '1439613478-1439611064-susu-3-1445526067.jpg', NULL, NULL, 1445526067, 1, 1),
(9, 1, 'product', '1439613478-1439611064-susu-3-1445526082.jpg', NULL, NULL, 1445526082, 1, 1),
(10, 1, 'product', 'GX5051-1445526366.jpg', NULL, NULL, 1445526366, 1, 1),
(11, 1, 'product', 'noithathoaphat-345-1445526430.jpg', NULL, NULL, 1445526430, 1, 1),
(12, 11, 'product', 'noithathoaphat-345-1445526699.jpg', NULL, NULL, 1445526699, 1, 1),
(13, 1, 'product', '37106625-1445696802.png', 'image/png', '128681', 1445696802, 1, 1),
(14, 1, 'product', 'noithathoaphat-345-1445526947.jpg', NULL, NULL, 1445526947, 1, 1),
(15, 1, 'product', '1439613478-1439611064-susu-3-1445527018.jpg', NULL, NULL, 1445527018, 1, 1),
(16, 1, 'product', '1439613478-1439611064-susu-3-1445527053.jpg', NULL, NULL, 1445527053, 1, 1),
(17, 1, 'product', '1439613478-1439611064-susu-3-1445527084.jpg', NULL, NULL, 1445527084, 1, 1),
(18, 1, 'product', '1439613478-1439611064-susu-3-1445527114.jpg', NULL, NULL, 1445527114, 1, 1),
(19, 1, 'product', '1439613478-1439611064-susu-3-1445527327.jpg', NULL, NULL, 1445527327, 1, 1),
(20, 1, 'product', '1439613478-1439611064-susu-3-1445527388.jpg', NULL, NULL, 1445527388, 1, 1),
(21, 1, 'product', '4_660x0-1445527388.jpg', NULL, NULL, 1445527388, 1, 0),
(22, 1, 'product', '37106625-1445527388.png', NULL, NULL, 1445527388, 1, 0),
(23, 1, 'product', '1439613478-1439611064-susu-3-1445527413.jpg', NULL, NULL, 1445527413, 1, 1),
(24, 1, 'product', '4_660x0-1445527413.jpg', NULL, NULL, 1445527413, 1, 0),
(25, 1, 'product', '37106625-1445527413.png', NULL, NULL, 1445527413, 1, 0),
(26, 12, 'product', '1439613478-1439611064-susu-3-1445527943.jpg', NULL, NULL, 1445527943, 1, 1),
(27, 12, 'product', '4_660x0-1445527944.jpg', NULL, NULL, 1445527944, 1, 0),
(28, 12, 'product', '37106625-1445527944.png', NULL, NULL, 1445527944, 1, 0),
(29, 13, 'product', '37106625-1445696976.png', 'image/png', '128681', 1445696976, 1, 1),
(32, 14, 'product', '1439613478-1439611064-susu-3-1445611157.jpg', NULL, NULL, 1445611157, 1, 1),
(36, 1, 'product', '4_660x0-1445677153.jpg', NULL, NULL, 1445677153, 1, 0),
(37, 1, 'product', '4_660x0-1445677778.jpg', 'image/jpeg', '301851', 1445677778, 1, 0),
(38, 1, 'product', '3_660x0-1445677850.jpg', 'image/jpeg', '323207', 1445677850, 1, 0),
(39, 1, 'product', '4_660x0-1445677850.jpg', 'image/jpeg', '301851', 1445677850, 1, 0),
(40, 1, 'product', '4_660x0-1445678238.jpg', 'image/jpeg', '301851', 1445678238, 1, 0),
(41, 1, 'product', '37106625-1445678547.png', 'image/png', '128681', 1445678547, 1, 0),
(42, 1, 'product', '37106625-1445678608.png', 'image/png', '128681', 1445678608, 1, 0),
(43, 1, 'product', '4_660x0-1445678746.jpg', 'image/jpeg', '301851', 1445678746, 1, 0),
(44, 1, 'product', '37106625-1445678771.png', 'image/png', '128681', 1445678771, 1, 0),
(45, 1, 'product', '4_660x0-1445678868.jpg', 'image/jpeg', '301851', 1445678868, 1, 0),
(46, 1, 'product', '3_660x0-1445678964.jpg', 'image/jpeg', '323207', 1445678964, 1, 0),
(47, 1, 'product', '4_660x0-1445678964.jpg', 'image/jpeg', '301851', 1445678964, 1, 0),
(48, 1, 'product', '4_660x0-1445679020.jpg', 'image/jpeg', '301851', 1445679020, 1, 0),
(49, 1, 'product', 'Austrian_identity_card_-_front_and_back-1445679068.png', 'image/png', '779962', 1445679068, 1, 0),
(50, 1, 'product', '4_660x0-1445679084.jpg', 'image/jpeg', '301851', 1445679084, 1, 0),
(51, 1, 'product', '1_660x0-1445679128.jpg', 'image/jpeg', '252192', 1445679128, 1, 0),
(52, 1, 'product', '1_660x0-1445679172.jpg', 'image/jpeg', '252192', 1445679172, 1, 0),
(53, 1, 'product', '4_660x0-1445679221.jpg', 'image/jpeg', '301851', 1445679221, 1, 0),
(54, 1, 'product', '4_660x0-1445679272.jpg', 'image/jpeg', '301851', 1445679272, 1, 0),
(55, 1, 'product', '4_660x0-1445679328.jpg', 'image/jpeg', '301851', 1445679328, 1, 0),
(56, 1, 'product', '3_660x0-1445679352.jpg', 'image/jpeg', '323207', 1445679352, 1, 0),
(57, 1, 'product', '4_660x0-1445679352.jpg', 'image/jpeg', '301851', 1445679352, 1, 0),
(58, 1, 'product', '37106625-1445679352.png', 'image/png', '128681', 1445679352, 1, 0),
(59, 1, 'product', 'MC20130-U11-1445679400.jpg', 'image/jpeg', '71238', 1445679400, 1, 0),
(60, 1, 'product', 'MC20331-MF1-1445679400.jpg', 'image/jpeg', '638759', 1445679400, 1, 0),
(61, 1, 'product', 'MC20401-F51-1445679401.jpg', 'image/jpeg', '67782', 1445679401, 1, 0),
(62, 1, 'product', '4_660x0-1445679529.jpg', 'image/jpeg', '301851', 1445679529, 1, 0),
(63, 1, 'product', '37106625-1445679529.png', 'image/png', '128681', 1445679529, 1, 0),
(64, 1, 'product', '3_660x0-1445679553.jpg', 'image/jpeg', '323207', 1445679553, 1, 0),
(65, 1, 'product', '4_660x0-1445679553.jpg', 'image/jpeg', '301851', 1445679553, 1, 0),
(66, 1, 'product', '37106625-1445679553.png', 'image/png', '128681', 1445679553, 1, 0),
(67, 1, 'product', '3_660x0-1445679840.jpg', 'image/jpeg', '323207', 1445679840, 1, 0),
(68, 1, 'product', '4_660x0-1445679841.jpg', 'image/jpeg', '301851', 1445679841, 1, 0),
(69, 1, 'product', '4_660x0-1445679944.jpg', 'image/jpeg', '301851', 1445679944, 1, 0),
(70, 1, 'product', '4_660x0-1445680643.jpg', 'image/jpeg', '301851', 1445680643, 1, 0),
(71, 1, 'product', '4_660x0-1445680759.jpg', 'image/jpeg', '301851', 1445680759, 1, 0),
(72, 1, 'product', '4_660x0-1445680781.jpg', 'image/jpeg', '301851', 1445680781, 1, 0),
(73, 1, 'product', '4_660x0-1445680810.jpg', 'image/jpeg', '301851', 1445680810, 1, 0),
(74, 1, 'product', 'GX5051-1445680834.jpg', 'image/jpeg', '36622', 1445680834, 1, 0),
(75, 1, 'product', '4_660x0-1445681088.jpg', 'image/jpeg', '301851', 1445681088, 1, 0),
(76, 1, 'product', '4_660x0-1445681159.jpg', 'image/jpeg', '301851', 1445681159, 1, 0),
(77, 1, 'product', '4_660x0-1445681189.jpg', 'image/jpeg', '301851', 1445681189, 1, 0),
(78, 1, 'product', '4_660x0-1445681242.jpg', 'image/jpeg', '301851', 1445681242, 1, 0),
(79, 1, 'product', '4_660x0-1445681275.jpg', 'image/jpeg', '301851', 1445681275, 1, 0),
(80, 1, 'product', '4_660x0-1445681299.jpg', 'image/jpeg', '301851', 1445681299, 1, 0),
(81, 1, 'product', '4_660x0-1445681720.jpg', 'image/jpeg', '301851', 1445681720, 1, 0),
(82, 1, 'product', '4_660x0-1445681784.jpg', 'image/jpeg', '301851', 1445681784, 1, 0),
(83, 1, 'product', '4_660x0-1445681989.jpg', 'image/jpeg', '301851', 1445681989, 1, 0),
(84, 1, 'product', '4_660x0-1445682043.jpg', 'image/jpeg', '301851', 1445682043, 1, 0),
(85, 1, 'product', '3_660x0-1445682058.jpg', 'image/jpeg', '323207', 1445682058, 1, 0),
(86, 1, 'product', '37106625-1445682065.png', 'image/png', '128681', 1445682065, 1, 0),
(87, 1, 'product', '1_660x0-1445682071.jpg', 'image/jpeg', '252192', 1445682071, 1, 0),
(88, 1, 'product', '4_660x0-1445682177.jpg', 'image/jpeg', '301851', 1445682177, 1, 0),
(89, 1, 'product', 'MC20130-U11-1445682196.jpg', 'image/jpeg', '71238', 1445682196, 1, 0),
(90, 1, 'product', 'MC20331-MF1-1445682197.jpg', 'image/jpeg', '638759', 1445682197, 1, 0),
(91, 1, 'product', 'MC20401-F51-1445682197.jpg', 'image/jpeg', '67782', 1445682197, 1, 0),
(92, 1, 'product', '3_660x0-1445682266.jpg', 'image/jpeg', '323207', 1445682266, 1, 0),
(93, 1, 'product', '4_660x0-1445682266.jpg', 'image/jpeg', '301851', 1445682266, 1, 0),
(94, 1, 'product', '37106625-1445682266.png', 'image/png', '128681', 1445682266, 1, 0),
(95, 1, 'product', '1439613478-1439611064-susu-3-1445682267.jpg', 'image/jpeg', '249929', 1445682267, 1, 0),
(96, 1, 'product', 'OC10212-3C1-1445682343.jpg', 'image/jpeg', '99589', 1445682343, 1, 0),
(97, 1, 'product', '4_660x0-1445682381.jpg', 'image/jpeg', '301851', 1445682381, 1, 0),
(98, 1, 'product', '4_660x0-1445682534.jpg', 'image/jpeg', '301851', 1445682534, 1, 0),
(99, 1, 'product', '1_660x0-1445682627.jpg', 'image/jpeg', '252192', 1445682627, 1, 0),
(100, 1, 'product', '1_660x0-1445682722.jpg', 'image/jpeg', '252192', 1445682722, 1, 0),
(101, 1, 'product', '1_660x0-1445682845.jpg', 'image/jpeg', '252192', 1445682845, 1, 0),
(102, 1, 'product', '1_660x0-1445682912.jpg', 'image/jpeg', '252192', 1445682912, 1, 0),
(103, 1, 'product', '1_660x0-1445682922.jpg', 'image/jpeg', '252192', 1445682922, 1, 0),
(104, 1, 'product', '1_660x0-1445682940.jpg', 'image/jpeg', '252192', 1445682940, 1, 0),
(105, 1, 'product', '1_660x0-1445682979.jpg', 'image/jpeg', '252192', 1445682979, 1, 0),
(106, 1, 'product', '2_660x0-1445682979.jpg', 'image/jpeg', '287243', 1445682979, 1, 0),
(107, 1, 'product', '3_660x0-1445682979.jpg', 'image/jpeg', '323207', 1445682979, 1, 0),
(108, 1, 'product', '4_660x0-1445682980.jpg', 'image/jpeg', '301851', 1445682980, 1, 0),
(109, 1, 'product', '37106625-1445682980.png', 'image/png', '128681', 1445682980, 1, 0),
(110, 1, 'product', '3_660x0-1445683227.jpg', 'image/jpeg', '323207', 1445683227, 1, 0),
(111, 1, 'product', '4_660x0-1445683227.jpg', 'image/jpeg', '301851', 1445683227, 1, 0),
(112, 1, 'product', '37106625-1445683227.png', 'image/png', '128681', 1445683227, 1, 0),
(113, 1, 'product', 'GX5051-1445683419.jpg', 'image/jpeg', '36622', 1445683419, 1, 0),
(114, 15, 'product', '1_660x0-1445762609.jpg', 'image/jpeg', '252192', 1445762609, 1, 1),
(115, 15, 'product', '37106625-1445762609.png', 'image/png', '128681', 1445762609, 1, 0),
(116, 15, 'product', '1439613478-1439611064-susu-3-1445762609.jpg', 'image/jpeg', '249929', 1445762609, 1, 0);

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
  `startday` int(10) DEFAULT NULL,
  `endday` int(10) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `payment`, `total`, `status`, `customer_id`, `payment_method_id`) VALUES
(2, 1, 1, 0, 1, 1);

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
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `slug` varchar(45) NOT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created` int(10) DEFAULT NULL,
  `changed` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `name`, `status`) VALUES
(1, 'abc', 1);

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
  `highlight` int(11) DEFAULT '0',
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `sort` int(10) DEFAULT '0',
  `cost` varchar(60) NOT NULL,
  `price` varchar(60) NOT NULL DEFAULT '0',
  `tax` int(11) DEFAULT '0',
  `view` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `startday` int(10) NOT NULL DEFAULT '0',
  `endday` int(10) DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`trademark_id`,`supplier_id`,`shop_id`,`category_id`,`user_id`),
  KEY `fk_product_trademark1_idx` (`trademark_id`),
  KEY `fk_product_supplier1_idx` (`supplier_id`),
  KEY `fk_product_shop1_idx` (`shop_id`),
  KEY `fk_product_category1_idx` (`category_id`),
  KEY `fk_product_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `name`, `address`, `phone`, `fax`, `status`) VALUES
(1, 'shop 1', '', NULL, NULL, 1),
(2, 'asdasda', '', '', '', 1),
(3, 'demo', '', '', '', 1),
(4, 'test', '', '', '', 1),
(5, 'shop edit', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `link` varchar(45) DEFAULT NULL,
  `image` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `startday` int(10) NOT NULL,
  `endday` int(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

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
(26, 'demo demo', '', '', '', '', '', '', '', 1),
(27, 'supplier edit', '', '', '', '', '', '', '', 1);

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
(1, 'Sản Phẩm', 'products', 0),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `trademark`
--

INSERT INTO `trademark` (`id`, `name`, `slug`, `title`, `keyword`, `description`, `status`) VALUES
(1, 'trademark', '', NULL, NULL, NULL, 1),
(2, 'abc', 'abc', '', '', '', 1),
(3, 'trademark edit', 'trademark-edit', '', '', '', 1);

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
  `birthday` int(10) DEFAULT NULL,
  `sex` int(1) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '1: active',
  `created` int(10) NOT NULL,
  `changed` int(10) NOT NULL,
  `avartar` varchar(45) DEFAULT NULL,
  `token` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `username` varchar(45) NOT NULL,
  `country_id` int(11) NOT NULL,
  `skype` varchar(60) NOT NULL,
  `sponsor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `salt`, `fullname`, `birthday`, `sex`, `address`, `active`, `created`, `changed`, `avartar`, `token`, `status`, `username`, `country_id`, `skype`, `sponsor_id`) VALUES
(1, 'vanlong200880@gmail.com', '123456', 'sdfsfsrrwerwewr', 'Pham Long', NULL, NULL, NULL, 1, 0, 0, NULL, '', 0, 'phamlong', 1, '', 0),
(12, '111@gmail.com', '8926cc32e0f4a4756c44668ec3d9cfa13b7f9956', '{tBA>=#NHzsq!2DT<qP.!<egB@!z&Anq@/-$w,FZ!''Z.@A6h*m', '1123123', NULL, NULL, NULL, 1, 1448783237, 1448783237, NULL, '8c37e96aa929c77cc995b46a15d3ef77', 1, '12312312', 2, '1234567', 1),
(13, 'an@gmail.com', 'd68cd801ed33a26b570628c24b80caefbab26e9a', '"TKx-vy,})Atk{Zn:p5!\\Y.$YUp"FW<Rg~{`q]m%Js>eeSJ@J|', 'an', NULL, NULL, NULL, 1, 1448806903, 1448806903, NULL, 'a74a5c244b4c9b9ca37862f14d8ce329', 1, 'an', 2, 'anky', 1);

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
