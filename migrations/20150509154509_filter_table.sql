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
  CONSTRAINT `fk_filter_mp` FOREIGN KEY (`mp`) REFERENCES `measurementpoints`(`id`)
) ENGINE=InnoDB;
