<?php

$skip_config_check = true;
require(dirname(__FILE__) . '/../config.php');
require(dirname(__FILE__) . '/../model/MP.php');


class MigrationConfig {
	public static function fix_database($username=null) {
		global $DB_SERVER, $user, $password, $DATABASE;

		if(is_null($username)) {
			return new MySQLi($DB_SERVER, $user, $password, $DATABASE);
		} else {
			return new MySQLi($DB_SERVER, $username, ask_for_password(), $DATABASE);
		}
	}
}
