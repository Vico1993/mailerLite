# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.21)
# Database: mailerlite
# Generation Time: 2018-04-15 01:12:17 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table field
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field`;

CREATE TABLE `field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_subscriber` int(11) unsigned NOT NULL,
  `id_type` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table field_data
# ------------------------------------------------------------

DROP VIEW IF EXISTS `field_data`;

CREATE TABLE `field_data` (
   `id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
   `title` VARCHAR(255) NOT NULL DEFAULT '',
   `id_type` INT(11) UNSIGNED NOT NULL,
   `type` VARCHAR(255) NULL DEFAULT '',
   `id_subscriber` INT(11) UNSIGNED NOT NULL,
   `subscriber` VARCHAR(255) NULL DEFAULT ''
) ENGINE=MyISAM;



# Dump of table field_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_type`;

CREATE TABLE `field_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `field_type` WRITE;
/*!40000 ALTER TABLE `field_type` DISABLE KEYS */;

INSERT INTO `field_type` (`id`, `value`)
VALUES
	(1,'date'),
	(2,'number'),
	(3,'string'),
	(4,'boolean');

/*!40000 ALTER TABLE `field_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table subs
# ------------------------------------------------------------

DROP VIEW IF EXISTS `subs`;

CREATE TABLE `subs` (
   `id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
   `name` VARCHAR(255) NOT NULL DEFAULT '',
   `email` VARCHAR(255) NOT NULL DEFAULT '',
   `state_value` VARCHAR(255) NULL DEFAULT ''
) ENGINE=MyISAM;



# Dump of table subscriber
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subscriber`;

CREATE TABLE `subscriber` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_state` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `subs_state` (`id_state`),
  CONSTRAINT `subs_state` FOREIGN KEY (`id_state`) REFERENCES `subscriber_state` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table subscriber_state
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subscriber_state`;

CREATE TABLE `subscriber_state` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `subscriber_state` WRITE;
/*!40000 ALTER TABLE `subscriber_state` DISABLE KEYS */;

INSERT INTO `subscriber_state` (`id`, `value`)
VALUES
	(1,'active'),
	(2,'unsubscribed'),
	(3,'junk'),
	(4,'bounced'),
	(5,'unconfirmed');

/*!40000 ALTER TABLE `subscriber_state` ENABLE KEYS */;
UNLOCK TABLES;




# Replace placeholder table for field_data with correct view syntax
# ------------------------------------------------------------

DROP TABLE `field_data`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mailerlite`@`%` SQL SECURITY DEFINER VIEW `field_data`
AS SELECT
   `f`.`id` AS `id`,
   `f`.`title` AS `title`,
   `f`.`id_type` AS `id_type`,
   `ft`.`value` AS `type`,
   `f`.`id_subscriber` AS `id_subscriber`,
   `sub`.`name` AS `subscriber`
FROM ((`field` `f` left join `subscriber` `sub` on((`sub`.`id` = `f`.`id_subscriber`))) left join `field_type` `ft` on((`ft`.`id` = `f`.`id_type`)));


# Replace placeholder table for subs with correct view syntax
# ------------------------------------------------------------

DROP TABLE `subs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mailerlite`@`%` SQL SECURITY DEFINER VIEW `subs`
AS SELECT
   `sub`.`id` AS `id`,
   `sub`.`name` AS `name`,
   `sub`.`email` AS `email`,
   `stat`.`value` AS `state_value`
FROM (`subscriber` `sub` left join `subscriber_state` `stat` on((`sub`.`id_state` = `stat`.`id`)));

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
