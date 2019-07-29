CREATE TABLE IF NOT EXISTS `data` (
    `record_id` int(11) NOT NULL AUTO_INCREMENT,
    `device_serial_number` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
    -- `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `timestamp` int(5) NOT NULL,
    `user_id` int(11) NOT NULL,
    `heating` tinyint unsigned NOT NULL,
    `cooling` tinyint unsigned NOT NULL,
    `target` numeric(7,3) NOT NULL,
    `current` numeric(7,3) NOT NULL,
    `humidity` tinyint unsigned NOT NULL,
    `outside_temp` numeric(7,3) NOT NULL,
    `outside_humidity` numeric(7,3) NOT NULL,
    `battery_level` decimal(4,3) NOT NULL,
    PRIMARY KEY (`record_id`),
    KEY `data_user_id` (`user_id`),
    KEY `data_timestamp` (`timestamp`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  `user_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_location_lat` decimal(10,6) NULL,
`user_location_long` decimal(10,6) NULL,
  `nest_username` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'nest user name, unique',
  `nest_password` varchar(64)COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `scale` varchar(10),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';
CREATE TABLE IF NOT EXISTS `devices` (
  `user_id` int(11) DEFAULT NULL,
  `device_serial_number` varchar(20) NOT NULL DEFAULT '',
  `device_location` varchar(20) DEFAULT NULL,
  `device_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`device_serial_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;