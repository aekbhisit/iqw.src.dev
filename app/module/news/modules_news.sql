# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 163.44.197.64 (MySQL 5.1.73)
# Database: admin_onetouch
# Generation Time: 2017-10-06 06:30:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table news
# ------------------------------------------------------------

CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(700) DEFAULT NULL,
  `slug` varchar(700) DEFAULT NULL,
  `description` varchar(700) DEFAULT NULL,
  `content` mediumtext,
  `image` varchar(500) DEFAULT NULL,
  `params` text,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `stat` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table news_categories
# ------------------------------------------------------------

CREATE TABLE `news_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `name` varchar(400) DEFAULT NULL,
  `slug` varchar(700) DEFAULT NULL,
  `description` text,
  `image` text,
  `params` text,
  `user_id` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table news_categories_translate
# ------------------------------------------------------------

CREATE TABLE `news_categories_translate` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(3) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `name` varchar(400) DEFAULT NULL,
  `slug` varchar(700) DEFAULT NULL,
  `description` text,
  `image` text,
  `params` text,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table news_translate
# ------------------------------------------------------------

CREATE TABLE `news_translate` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(3) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `name` varchar(700) DEFAULT NULL,
  `description` varchar(700) DEFAULT NULL,
  `content` mediumtext,
  `image` varchar(500) DEFAULT '',
  `params` text,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
