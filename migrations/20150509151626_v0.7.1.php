<?php

global $DATABASE;

/* usually not needed but this is for compatiblity with the old upgrade scripts which was (supposed to be) idempotent */
$result = $db->query("SELECT 1 FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name`='version' LIMIT 1");
if ( $result->num_rows == 1 ){
	return;
}

$result = $db->query("SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name` LIKE '%_filterlist'");
while ($row = $result->fetch_assoc()) {
	$table = $row['table_name'];
	$db->query("ALTER TABLE $table ADD mode ENUM('AND', 'OR') NOT NULL DEFAULT 'AND'") or die($db->error());
}
$db->query("CREATE TABLE `version` (`num` INT PRIMARY KEY NOT NULL DEFAULT 1)") or die($db->error());
$db->query("INSERT INTO `version` (`num`) VALUES (1)") or die($db->error());
