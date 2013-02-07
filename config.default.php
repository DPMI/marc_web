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

/* Path configuration */
$prefix = '/';                              /* Path prefix (--prefix to configure). */
$sysconfdir = "{PREFIX}etc";
$localstatedir = "{PREFIX}var";
$rrdbase = "{LOCALSTATEDIR}marc";
$rrdtool = '/usr/bin/rrdtool';
$cachedir = realpath('cache');                 /* cache directory (e.g. graphs ) */

/* Permissions */
$usergroup = 'marc';

/* URL configuration */
$root = '/';                                /* If installing into a subdirectory, set the path here. Must end in trailing slash.
                                             * E.g. if URL is http://example.net/marc set root to 'marc/' */
/* Graph settings */
$graph_width = 345;                         /* default width of graphs */
$graph_aspect = 1.7;                        /* graph aspect ratio */
$graph_max_age = 5*60;                      /* time in seconds the cached graphs is valid */
$graph_packet_span = array('24h', '6w');    /* default timespan for packet graphs */
$graph_bu_span = array('24h');              /* default timestan for buffer utilization */

/* misc */
$use_ping = true;                           /* show ping time for MPs */
$mp_timeout = 60*60;                        /* after how many seconds an MP should be considered non-responsive */

/* General */
$title = 'Network Performance Labs';        /* title prepended in<title> */
/* $subtitle = 'Research'; */               /* title in menu (if unset nothing is shown) */
