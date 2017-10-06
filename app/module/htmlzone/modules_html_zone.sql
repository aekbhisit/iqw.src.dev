# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 163.44.197.64 (MySQL 5.1.73)
# Database: admin_onetouch
# Generation Time: 2017-10-06 07:31:08 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table html_zone
# ------------------------------------------------------------

CREATE TABLE `html_zone` (
  `zone_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zone` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(700) DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table html_zone_data
# ------------------------------------------------------------

CREATE TABLE `html_zone_data` (
  `zone_data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) DEFAULT NULL,
  `zone_input_id` int(11) DEFAULT NULL,
  `params` mediumtext,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`zone_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table html_zone_data_translate
# ------------------------------------------------------------

CREATE TABLE `html_zone_data_translate` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zone_data_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `zone_input_id` int(11) DEFAULT NULL,
  `lang` varchar(3) DEFAULT NULL,
  `params` mediumtext,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table html_zone_input
# ------------------------------------------------------------

CREATE TABLE `html_zone_input` (
  `zone_input_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(700) DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`zone_input_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
