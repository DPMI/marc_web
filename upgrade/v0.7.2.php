<?php

$config_check = true;
require(dirname(__FILE__) . '/../config.php');
require(dirname(__FILE__) . '/../model/MP.php');

function innodb(){
	foreach(array('access', 'measurementpoints', 'greeting') as $table){
		mysql_query("ALTER TABLE `$table` ENGINE = INNODB") or die(mysql_error());
	}
}

function single_filter_table(){
	mysql_query("CREATE TABLE IF NOT EXISTS `filter` (\n".
	            "  `filter_id` INT NOT NULL,".
	            "  `mp` INT NOT NULL,".
	            "  `mode` ENUM('AND', 'OR') NOT NULL DEFAULT 'AND',".
	            "  `index` INT NOT NULL DEFAULT 0,".
	            "  `CI` CHAR(8) NOT NULL DEFAULT '',".
	            "  `VLAN_TCI` INT NOT NULL DEFAULT 0,".
	            "  `VLAN_TCI_MASK` INT NOT NULL DEFAULT 0,".
	            "  `ETH_TYPE` INT NOT NULL DEFAULT 0,".
	            "  `ETH_TYPE_MASK` INT NOT NULL DEFAULT 0,".
	            "  `ETH_SRC` VARCHAR(17) NOT NULL DEFAULT '',".
	            "  `ETH_SRC_MASK` VARCHAR(17) NOT NULL DEFAULT '',".
	            "  `ETH_DST` VARCHAR(17) NOT NULL DEFAULT '',".
	            "  `ETH_DST_MASK` VARCHAR(17) NOT NULL DEFAULT '',".
	            "  `IP_PROTO` INT NOT NULL DEFAULT 0,".
	            "  `IP_SRC` VARCHAR(16) NOT NULL DEFAULT '',".
	            "  `IP_SRC_MASK` VARCHAR(16) NOT NULL DEFAULT '',".
	            "  `IP_DST` VARCHAR(16) NOT NULL DEFAULT '',".
	            "  `IP_DST_MASK` VARCHAR(16) NOT NULL DEFAULT '',".
	            "  `SRC_PORT` INT NOT NULL DEFAULT 0,".
	            "  `SRC_PORT_MASK` INT NOT NULL DEFAULT 0,".
	            "  `DST_PORT` INT NOT NULL DEFAULT 0,".
	            "  `DST_PORT_MASK` INT NOT NULL DEFAULT 0,".
	            "  `destaddr` VARCHAR(23) NOT NULL DEFAULT '',".
	            "  `type` INT NOT NULL DEFAULT 0,".
	            "  `caplen` INT NOT NULL DEFAULT 0,".
	            "  PRIMARY KEY `filter_pk` (`filter_id`, `mp`),".
	            "  CONSTRAINT `filter_mp` FOREIGN KEY (`mp`) REFERENCES `measurementpoints`(`id`)".
	            ") ENGINE=InnoDB") or die(mysql_error());

	foreach ( MP::selection() as $mp ){
		$table = "{$mp->MAMPid}_filterlist";
		mysql_query("INSERT INTO `filter` SELECT `filter_id`, {$mp->id} AS `mp_id`, `mode`, `ind` AS `index`, `CI_ID` AS `CI`, `VLAN_TCI`, `VLAN_TCI_MASK`, `ETH_TYPE`, `ETH_TYPE_MASK`, `ETH_SRC`, `ETH_SRC_MASK`, `ETH_DST`, `ETH_DST_MASK`, `IP_PROTO`, `IP_SRC`, `IP_SRC_MASK`, `IP_DST`, `IP_DST_MASK`, `SRC_PORT`, `SRC_PORT_MASK`, `DST_PORT`, `DST_PORT_MASK`, `destaddr`, `type`, `caplen` FROM `$table`") or print(mysql_error()."\n");
		mysql_query("DROP TABLE `$table`") or print(mysql_error()."\n");
	}
}

$result = mysql_query("SELECT `num` FROM `version`") or die(mysql_error());
$row = mysql_fetch_array($result) or die("run v0.7.1.php first");
$version = $row[0];

switch ( $version ){
case 1:
	innodb();

case 2:
	single_filter_table();
};

mysql_query("UPDATE `version` SET `num` = 3");
