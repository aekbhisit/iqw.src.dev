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

example response 
[home_facebook_block] => Array
        (
            [zone_id] => 1
            [zone] => 
            [name] => Home Block Facebook
            [description] => Home Block Facebook
            [mdate] => 2017-09-14 09:23:28
            [cdate] => 2017-09-14 09:23:12
            [sequence] => 0
            [status] => 1
            [html_data] => Array
                (
                    [0] => Array
                        (
                            [zone_input_id] => 1
                            [zone_id] => 1
                            [type] => 1
                            [name] => Home Block Facebook Head
                            [description] => Home Block Facebook Head
                            [mdate] => 2017-09-14 09:23:50
                            [cdate] => 2017-09-14 09:23:50
                            [sequence] => 0
                            [status] => 1
                            [zone_data_id] => 1
                            [params] => Facebook
                        )

                    [1] => Array
                        (
                            [zone_input_id] => 2
                            [zone_id] => 1
                            [type] => 2
                            [name] => Home Block Facebook Iframe
                            [description] => Home Block Facebook Iframe
                            [mdate] => 2017-09-14 13:34:11
                            [cdate] => 2017-09-14 09:24:12
                            [sequence] => 0
                            [status] => 1
                            [zone_data_id] => 2
                            [params] => <p><iframe style="border: none; overflow: hidden;" src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fonetouchthailand%2F&amp;tabs=timeline&amp;width=500&amp;height=436&amp;small_header=true&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=false&amp;appId" width="500" height="436" frameborder="0" scrolling="no"></iframe></p>
                        )

                )

        )

)
