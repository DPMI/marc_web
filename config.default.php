<?php

/**
 * User configuration.
 *
 * This file contains all default values, which is always evaulated first. If
 * present `config.local.php` is then evaluated and overwrites any configuration
 * set here.
 */

/* Database configuration */
$DB_SERVER="localhost";
$DATABASE='marc';
$user='marc';
$password='konko';
$path='uploadedfiles/';

/* RRDtool config */
$prefix = '/var/local';
$rrdbase = "$prefix/lib/marc";
$usergroup = 'marc';

/* Path configuration */
$root = '/'; /* if installing into a subdirectory, set the path here. Must end in trailing slash */
$index = $root . 'index2.php';

?>