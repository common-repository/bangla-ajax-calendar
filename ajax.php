<?php

include (dirname (__FILE__).'/../../../wp-config.php');

if (!class_exists ('Bangla_Calendar'))
	include (ABSPATH.'wp-content/plugins/bangla-calendar/bangla-calendar.php');
	
include (dirname (__FILE__).'/models/calendar.php');

$calendar_plugin = Bangla_Calendar::get ();

$calendar = new Calendar ($calendar_plugin->url ());
echo $calendar->show (intval ($_GET['month']), intval ($_GET['year']), intval ($_GET['full']));

?>