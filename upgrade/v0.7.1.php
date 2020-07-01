<?php

$skip_config_check = true;
require(dirname(__FILE__) . '/../config.php');

echo "Running v0.7.1.php  \n";
echo "Database = $DATABASE \n";
echo "Host= " . mysqli_get_host_info($db) . "\n";


$result = mysqli_query($Connect,"SELECT 1 FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name`='version' LIMIT 1");
if ( mysqli_num_rows($result) == 1 ){
     echo "Exiting, SELECT 1 FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name`='version' LIMIT 1 \n";
	exit;
}

$result = mysqli_query($Connect, "SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name` LIKE '%_filterlist'");
while ($row = mysqli_fetch_assoc($result)) {
	$table = $row['table_name'];
	mysqli_query("ALTER TABLE $table ADD mode ENUM('AND', 'OR') NOT NULL DEFAULT 'AND'") or die(mysqli_error());
}
mysqli_query($db, "CREATE TABLE `version` (`num` INT PRIMARY KEY NOT NULL DEFAULT 1)") or die(mysqli_error());
mysqli_query($db, "INSERT INTO `version` (`num`) VALUES (1)") or die(mysqli_error());

echo "Completed v0.7.1.php  \n";