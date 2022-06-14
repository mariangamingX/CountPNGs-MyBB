<?php
/***************************************************************************
 *
 *  CountPNGs plugin (/inc/plugins/countpngs.php)
 *  Author: mgX #2755 on discord
 *
 *  License: license.txt
 *
 *  Fully customizable counter based on images for MyBB 1.8.
 *
 ***************************************************************************/

/****************************************************************************
                DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                        Version 2, December 2004
   
     Copyright (C) 2022 mgX <marian.gaming@yandex.com>

     Everyone is permitted to copy and distribute verbatim or modified
     copies of this license document, and changing it is allowed as long
     as the name is changed.
 
                DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
       TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

      0. You just DO WHAT THE FUCK YOU WANT TO.
****************************************************************************/

if(!defined("IN_MYBB")){
	die("This file cannot be accessed directly.");

// add hooks
$plugins->add_hook('admin_load', 'countpngs_admin');
$plugins->add_hook('admin_tools_menu', 'countpngs_admin_tools_menu');
$plugins->add_hook('admin_tools_action_handler', 'countpngs_admin_tools_action_handler');
$plugins->add_hook('admin_tools_permissions', 'countpngs_admin_permissions');

function countpngs_info()
{
	return array(
		"name"			=> "CountPNGs",
		"description"	=> "Fully customizable counter based on images for MyBB-based websites.",
		"website"		=> "",
		"author"		=> "mgX#2755 on dsc",
		"authorsite"	=> "https://enigma-t.ml/member.php?action=profile&uid=1",
		"version"		=> "1.0",
		"guid" 			=> "",
		"codename"		=> "countpngs",
		"compatibility" => "18*"
	);
}


function countpngs_install()
{

	$template = '<strong>{$counterpngs}</strong>';

	$insert_array = array(
		'title' => 'counterpngs_template',
		'template' => $db->escape_string($template),
		'sid' => '-1',
		'version' => '',
		'dateline' => time()
	);
	
	$db->insert_query('templates', $insert_array);
}

function countpngs_is_installed()
{

}

function countpngs_uninstall()
{
	$db->delete_query("templates", "title = 'counterpngs_template'");
}

function countpngs_activate()
{

}

function countpngs_deactivate()
{

}

?>
