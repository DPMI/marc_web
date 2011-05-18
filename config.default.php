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

/* Password hashing key. If this is changed then all current passwords are lost.
 * Change only when installing MArC. It is preferable to use a long key (but
 * repetition is bad). */
$site_key = str_repeat('default_marc_key', 25);

/* RRDtool config */
$prefix = '/var/local';
$rrdbase = "$prefix/lib/marc";
$usergroup = 'marc';

/* Path configuration */
$root = '/'; /* if installing into a subdirectory, set the path here. Must end in trailing slash */
$index = $root . 'index2.php';

?>