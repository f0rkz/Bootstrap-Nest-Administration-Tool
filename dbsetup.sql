# ------------------------------------------------------------

CREATE TABLE `data` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_serial_number` varchar(16) CHARACTER SET utf8mb4 DEFAULT NULL,
  `timestamp` int(5) NOT NULL,
  `timestamp_offset` int(5) NOT NULL,
  `user_id` int(11) NOT NULL,
  `heating` varchar(10) NOT NULL DEFAULT '0',
  `cooling` varchar(10) NOT NULL DEFAULT '0',
  `target` varchar(30) NOT NULL DEFAULT '',
  `current` decimal(7,3) NOT NULL,
  `humidity` tinyint(3) unsigned NOT NULL,
  `outside_temp` decimal(7,3) NOT NULL,
  `outside_humidity` decimal(7,3) NOT NULL,
  UNIQUE KEY `record_id` (`record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


# ------------------------------------------------------------

CREATE TABLE `devices` (
  `user_id` int(11) DEFAULT NULL,
  `device_serial_number` varchar(20) NOT NULL DEFAULT '',
  `device_location` varchar(20) DEFAULT NULL,
  `device_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`device_serial_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# ------------------------------------------------------------

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  `user_openweather_city` varchar(128) DEFAULT NULL,
  `user_zip` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_location` varchar(255) DEFAULT NULL,
  `user_location_lat` float(10,6) DEFAULT NULL,
  `user_location_long` float(10,6) DEFAULT NULL,
  `nest_username` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'nest user name, unique',
  `nest_password` varchar(64) NOT NULL DEFAULT '',
  `scale` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='user data';

