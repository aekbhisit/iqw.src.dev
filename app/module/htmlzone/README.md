Create Table

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `html_zone_data` (
  `zone_data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) DEFAULT NULL,
  `zone_input_id` int(11) DEFAULT NULL,
  `params` mediumtext,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`zone_data_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
  PRIMARY KEY (`zone_input_id`),
  KEY `FOREIGN` (`zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

Add htmlzone in table system_modules
