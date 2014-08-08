CREATE TABLE `data` (
    `timestamp` int(5) NOT NULL,
    `heating` tinyint unsigned NOT NULL,
    `cooling` tinyint unsigned NOT NULL,
    `target` numeric(7,3) NOT NULL,
    `current` numeric(7,3) NOT NULL,
    `humidity` tinyint unsigned NOT NULL,
    `outside_temp` numeric(7,3) NOT NULL,
    `outside_humidity` numeric(7,3) NOT NULL,
    UNIQUE KEY `timestamp` (`timestamp`)
)
ENGINE=MyIASM DEFAULT CHARSET=latin1;