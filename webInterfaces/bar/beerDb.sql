-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

CREATE DATABASE `beer` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `beer`;

DROP TABLE IF EXISTS `beers`;
CREATE TABLE `beers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `highValue` int(11) NOT NULL DEFAULT '0',
  `currentValue` int(11) NOT NULL DEFAULT '0',
  `notifyValue` int(11) NOT NULL DEFAULT '0',
  `location` varchar(155) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `umail` varchar(255) NOT NULL,
  `upass` varchar(255) NOT NULL,
  `fullName` varchar(155) DEFAULT NULL,
  `role` int(1) NOT NULL DEFAULT '1' COMMENT '1=purchaseManager, 2=administrator',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2017-11-23 23:03:45
