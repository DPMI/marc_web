<?php

foreach(array('access', 'measurementpoints', 'greeting') as $table){
	$db->query("ALTER TABLE `$table` ENGINE = INNODB") or die($db->error());
}
