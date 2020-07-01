<?php

$skip_config_check = true;
require(dirname(__FILE__) . '/../config.php');
require(dirname(__FILE__) . '/../model/MP.php');

foreach( $config_error as $e ){
	echo "$0: error: {$e['message']}\n";
}
if ( count($config_error) > 0 ){
	exit;
}

function innodb($Connect){
	foreach(array('access', 'measurementpoints', 'greeting') as $table){
		mysqli_query($Connect, "ALTER TABLE `$table` ENGINE = INNODB") or die(mysqli_error());
	}
}

function single_filter_table($Connect){
	mysqli_query($Connect, "CREATE TABLE IF NOT EXISTS `filter` (\n".
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
	            ") ENGINE=InnoDB") or die(mysqli_error($Connect));

	foreach ( MP::selection() as $mp ){
		$table = "{$mp->MAMPid}_filterlist";
		mysqli_query($Connect, "INSERT INTO `filter` SELECT `filter_id`, {$mp->id} AS `mp_id`, `mode`, `ind` AS `index`, `CI_ID` AS `CI`, `VLAN_TCI`, `VLAN_TCI_MASK`, `ETH_TYPE`, `ETH_TYPE_MASK`, `ETH_SRC`, `ETH_SRC_MASK`, `ETH_DST`, `ETH_DST_MASK`, `IP_PROTO`, `IP_SRC`, `IP_SRC_MASK`, `IP_DST`, `IP_DST_MASK`, `SRC_PORT`, `SRC_PORT_MASK`, `DST_PORT`, `DST_PORT_MASK`, `destaddr`, `type`, `caplen` FROM `$table`") or print(mysqli_error($Connect)."\n");
		mysqli_query($Connect, "DROP TABLE `$table`") or print(mysqli_error($Connect)."\n");
	}
}

function enum_tables($Connect){
	mysqli_query($Connect, "CREATE TABLE IF NOT EXISTS `filter_type` (`id` INT PRIMARY KEY, `name` VARCHAR(32)) ENGINE=InnoDB") or die(mysqli_error($Connect));
	mysqli_query($Connect, "CREATE TABLE IF NOT EXISTS `mp_status` (`id` INT PRIMARY KEY, `name` VARCHAR(32)) ENGINE=InnoDB") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `filter_type` (`id`, `name`) VALUES (0, 'file')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `filter_type` (`id`, `name`) VALUES (1, 'ethernet')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `filter_type` (`id`, `name`) VALUES (2, 'tcp')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `filter_type` (`id`, `name`) VALUES (3, 'udp')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `mp_status` (`id`, `name`) VALUES (0, 'unauthorized')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `mp_status` (`id`, `name`) VALUES (1, 'idle')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `mp_status` (`id`, `name`) VALUES (2, 'capturing')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `mp_status` (`id`, `name`) VALUES (3, 'stopped')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `mp_status` (`id`, `name`) VALUES (4, 'distress')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `mp_status` (`id`, `name`) VALUES (5, 'terminated')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "INSERT INTO `mp_status` (`id`, `name`) VALUES (6, 'timeout')") or die(mysqli_error($Connect));
	mysqli_query($Connect, "ALTER TABLE `filter` ADD CONSTRAINT `fk_filter_type` FOREIGN KEY (`type`) REFERENCES `filter_type`(`id`)") or die(mysqli_error($Connect));
	mysqli_query($Connect, "ALTER TABLE `measurementpoints` ADD CONSTRAINT `fk_mp_status` FOREIGN KEY (`status`) REFERENCES `mp_status`(`id`)") or die(mysqli_error($Connect));
}

function add_mtu($Connect){
	mysqli_query($Connect, "ALTER TABLE `measurementpoints` ADD `mtu` INT NOT NULL DEFAULT -1") or die(mysqli_error($Connect));
}

$result = mysqli_query($Connect, "SELECT `num` FROM `version`") or die(mysqli_error($Connect));
$row = mysqli_fetch_array($result) or die("run v0.7.1.php first");
$version = $row[0];

switch ( $version ){
case 1:
	innodb($Connect);

case 2:
	single_filter_table($Connect);

case 3:
	enum_tables($Connect);

case 4:
	add_mtu($Connect);
};

mysqli_query($Connect, "UPDATE `version` SET `num` = 5");
