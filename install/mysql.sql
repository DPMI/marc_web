-- MySQL dump 10.13  Distrib 5.1.56, for pc-linux-gnu (x86_64)
--
-- Host: localhost    Database: marc
-- ------------------------------------------------------
-- Server version	5.1.56-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `meta`;
CREATE TABLE `meta` (
       `key` varchar(16) PRIMARY KEY,
       `value` blob
);
INSERT INTO `meta` SET `key` = 'version', `value` = 7;

--
-- Table structure for table `access`
--

DROP TABLE IF EXISTS `access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `uname` varchar(64) NOT NULL UNIQUE,
  `passwd` varchar(512) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Name` text NOT NULL,
  `Email` text NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `access` SET
       `uname` = 'root',
       `passwd` = PASSWORD('0000'),
       `status` = 99,
       `comment` = 'default user (please remove after creating a new admin account)',
       `Name` = 'admin',
       `Email` = '';

--
-- Table structure for table `greeting`
--

DROP TABLE IF EXISTS `greeting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `greeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Welcome messages';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guiconfig`
--

DROP TABLE IF EXISTS `guiconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guiconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selected` int(11) NOT NULL DEFAULT '0',
  `pageStyle` text NOT NULL,
  `pageStyleBad` text NOT NULL,
  `projectName` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_3` (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_4` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='GUI config';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mainmenu`
--

DROP TABLE IF EXISTS `mainmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mainmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `string` text NOT NULL,
  `url` text NOT NULL,
  `accesslevel` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `filter_type`;
DROP TABLE IF EXISTS `mp_status`;
CREATE TABLE `filter_type` (`id` INT PRIMARY KEY, `name` VARCHAR(32)) ENGINE=InnoDB;
CREATE TABLE `mp_status` (`id` INT PRIMARY KEY, `name` VARCHAR(32)) ENGINE=InnoDB;
INSERT INTO `filter_type` (`id`, `name`) VALUES (0, 'file');
INSERT INTO `filter_type` (`id`, `name`) VALUES (1, 'ethernet');
INSERT INTO `filter_type` (`id`, `name`) VALUES (2, 'tcp');
INSERT INTO `filter_type` (`id`, `name`) VALUES (3, 'udp');
INSERT INTO `mp_status` (`id`, `name`) VALUES (0, 'unauthorized');
INSERT INTO `mp_status` (`id`, `name`) VALUES (1, 'idle');
INSERT INTO `mp_status` (`id`, `name`) VALUES (2, 'capturing');
INSERT INTO `mp_status` (`id`, `name`) VALUES (3, 'stopped');
INSERT INTO `mp_status` (`id`, `name`) VALUES (4, 'distress');
INSERT INTO `mp_status` (`id`, `name`) VALUES (5, 'terminated');
INSERT INTO `mp_status` (`id`, `name`) VALUES (6, 'timeout');

--
-- Table structure for table `measurementpoints`
--

DROP TABLE IF EXISTS `measurementpoints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurementpoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `port` int(11) NOT NULL DEFAULT '0',
  `mac` varchar(20) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MAMPid` varchar(16) NOT NULL DEFAULT '',
  `maxFilters` int(11) NOT NULL DEFAULT '0',
  `noCI` int(11) NOT NULL DEFAULT '0',

  -- 0.7 additions
  `status` int(11) NOT NULL DEFAULT '0',  -- MP status (e.g. distress)
  `drivers` int(11) NOT NULL,             -- bitmask of capture drivers
  `version` text NOT NULL,                -- semicolon separated list of version-numbers (for presentation)
  `CI_iface` text NOT NULL DEFAULT '',    -- semicolon separated list of CI ifaces (for presentation)

  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mp_status` FOREIGN KEY (`status`) REFERENCES `mp_status`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0 COMMENT='List of MPs within the MA';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `url` text NOT NULL,
  `accesslevel` int(11) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='List of pages on site';
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE IF NOT EXISTS `filter` (
  `filter_id` INT NOT NULL,
  `mp` INT NOT NULL,
  `mode` ENUM('AND', 'OR') NOT NULL DEFAULT 'AND',
  `index` INT NOT NULL DEFAULT 0,
  `CI` CHAR(8) NOT NULL DEFAULT '',
  `VLAN_TCI` INT NOT NULL DEFAULT 0,
  `VLAN_TCI_MASK` INT NOT NULL DEFAULT 0,
  `ETH_TYPE` INT NOT NULL DEFAULT 0,
  `ETH_TYPE_MASK` INT NOT NULL DEFAULT 0,
  `ETH_SRC` VARCHAR(17) NOT NULL DEFAULT '',
  `ETH_SRC_MASK` VARCHAR(17) NOT NULL DEFAULT '',
  `ETH_DST` VARCHAR(17) NOT NULL DEFAULT '',
  `ETH_DST_MASK` VARCHAR(17) NOT NULL DEFAULT '',
  `IP_PROTO` INT NOT NULL DEFAULT 0,
  `IP_SRC` VARCHAR(16) NOT NULL DEFAULT '',
  `IP_SRC_MASK` VARCHAR(16) NOT NULL DEFAULT '',
  `IP_DST` VARCHAR(16) NOT NULL DEFAULT '',
  `IP_DST_MASK` VARCHAR(16) NOT NULL DEFAULT '',
  `SRC_PORT` INT NOT NULL DEFAULT 0,
  `SRC_PORT_MASK` INT NOT NULL DEFAULT 0,
  `DST_PORT` INT NOT NULL DEFAULT 0,
  `DST_PORT_MASK` INT NOT NULL DEFAULT 0,
  `destaddr` VARCHAR(23) NOT NULL DEFAULT '',
  `type` INT NOT NULL DEFAULT 0,
  `caplen` INT NOT NULL DEFAULT 0,
  PRIMARY KEY `pk_filter` (`filter_id`, `mp`),
  CONSTRAINT `fk_filter_mp` FOREIGN KEY (`mp`) REFERENCES `measurementpoints`(`id`),
  CONSTRAINT `fk_filter_type` FOREIGN KEY (`type`) REFERENCES `filter_type`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `version` (`num` INT PRIMARY KEY NOT NULL DEFAULT 1);
INSERT INTO `version` (`num`) VALUES (4);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-05-11 14:45:58
