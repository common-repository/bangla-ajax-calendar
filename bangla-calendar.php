<?php
/*
Plugin Name: Bangla Calendar
Plugin URI: http://urbangiraffe.com/plugins/ajax-calendar/
Description: A version of the WordPress calendar that uses AJAX to allow the user to step through the months without updating the page.  Additionally, a click on the 'expand' link shows all the posts within that month, inside the calendar.  Caching of content can be enabled to increase speed.
Version: 1.1
Author: Sajid Muhaimin Choudhury
Author URI: http://www.sajidmc.net
Based on the AJAX Calendar plugin

*/

include_once (dirname (__FILE__).'/plugin.php');
include_once (dirname (__FILE__).'/models/widget.php');

if (!class_exists ('Bangla_Calendar'))
{
class Bangla_Calendar extends Bangla_Calendar_Plugin
{
	function Bangla_Calendar ()
	{
		$this->register_plugin ('ajax-calendar', __FILE__);

		if (is_admin ())
		{
		  $this->add_action ('publish_post', 'cache_remove');
		  $this->add_action ('save_post',    'cache_remove');
		  $this->add_action ('delete_post',  'cache_remove');
		}
		else
		  $this->add_action ('wp_head');

		$this->widget = new Bangla_Calendar_Widget ('Bangla Calendar');
	}

	function show ($categories = '')
	{
		include_once (dirname (__FILE__).'/models/calendar.php');

		$this->render ('calendar', array ('calendar' => new Calendar ($this->url (), $categories)));
	}

	function wp_head ()
	{
		$this->render ('head');
	}

	function cache_remove ($id)
	{
	  $post = wp_get_single_post ($id);

	  $postmonth = substr ($post->post_date, 5, 2);
	  $postyear  = substr ($post->post_date, 0, 4);
	  $modmonth  = substr ($post->post_modified, 5, 2);
	  $modyear   = substr ($post->post_modified, 0, 4);

	  // Delete the cache files for the two dates, just in case something has changed
	  for ($x = 0; $x < 2; $x++)
	  {
			wp_cache_delete ($postyear."_".$postmonth."_".$x);
			wp_cache_delete ($modyear."_".$modmonth."_".$x);
	  }
	}

	function &get ()
	{
    static $instance;

    if (!isset ($instance))
		{
			$c = __CLASS__;
			$instance = new $c;
    }

    return $instance;
	}
}


function bangla_calendar ($categories = '')
{
	$calendar = Bangla_Calendar::get ();
	$calendar->show ($categories);
}
}


$bangla_calendar_plugin = Bangla_Calendar::get ();

?>