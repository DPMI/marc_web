<?php

$config_check = true;
require(dirname(__FILE__) . '/../config.php');

$result = mysql_query("SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name` LIKE '%_filterlist'");
while ($row = mysql_fetch_assoc($result)) {
	$table = $row['table_name'];
	mysql_query("ALTER TABLE $table ADD mode ENUM('AND', 'OR') NOT NULL DEFAULT 'AND'") or die(mysql_error());
}
mysql_query("CREATE TABLE `version` (`num` INT PRIMARY KEY NOT NULL DEFAULT 1)") or die(mysql_error());
mysql_query("INSERT INTO `version` (`num`) VALUES (1)") or die(mysql_error());
