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
$plugins->add_hook('admin_config_settings_change', ['countpngs', 'admin_config_settings_change']);

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

	global $mybb, $db;

    // settings
	$settingGroupId = $db->insert_query('settinggroups', [
		'name'        => 'countpngs',
		'title'       => 'CountPNGs',
		'description' => 'Settings for CountPNGs.',
	]);
	
	$settings = [
		// start numbers
		[
			'name'        => 'png_zero',
			'title'       => '0.png/gif',
			'description' => 'Path to the image for number 0. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/0.png',
		],
		[
			'name'        => 'png_one',
			'title'       => '1.png/gif',
			'description' => 'Path to the image for number 1. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/1.png',
		],
		[
			'name'        => 'png_two',
			'title'       => '2.png/gif',
			'description' => 'Path to the image for number 2. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/2.png',
		],
		[
			'name'        => 'png_three',
			'title'       => '3.png/gif',
			'description' => 'Path to the image for number 3. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/3.png',
		],
		[
			'name'        => 'png_four',
			'title'       => '4.png/gif',
			'description' => 'Path to the image for number 4. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/4.png',
		],
		[
			'name'        => 'png_five',
			'title'       => '5.png/gif',
			'description' => 'Path to the image for number 5. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/5.png',
		],
		[
			'name'        => 'png_six',
			'title'       => '6.png/gif',
			'description' => 'Path to the image for number 6. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/6.png',
		],
		[
			'name'        => 'png_seven',
			'title'       => '7.png/gif',
			'description' => 'Path to the image for number 7. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/7.png',
		],
		[
			'name'        => 'png_eight',
			'title'       => '8.png/gif',
			'description' => 'Path to the image for number 8. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/8.png',
		],
		[
			'name'        => 'png_nine',
			'title'       => '9.png/gif',
			'description' => 'Path to the image for number 9. Must be at images/counter.',
			'optionscode' => 'text',
			'value'       => 'minecraft/j/9.png',
		],
        // end numbers
		// options: yesno, numeric, text, select

		$i = 1;
	
		foreach ($settings as &$row) {
			$row['gid']         = $settingGroupId;
			$row['title']       = $db->escape_string($row['title']);
			$row['description'] = $db->escape_string($row['description']);
			$row['disporder']   = $i++;
		}
	
		$db->insert_query_multiple('settings', $settings);
	
		rebuild_settings();
	
    // templates
    $templates = [
        'countpngs_main' => '<div class="panel">
<label for="countercat">Category:</label>
<select name="countercat" id="countercat">
    <optgroup label="====== Total ======">
		<option value="numposts">Posts</option>
		<option value="numthreads">Threads</option>
		<option value="numusers">Users</option>
	</optgroup>
	<optgroup label="====== Per Day ======">
		<option value="postsperday">Posts</option>
		<option value="threadsperday">Threads</option>
		<option value="membersperday">Users</option>
	</optgroup>
	<optgroup label="====== Average ======">
		<option value="postspermember">Posts per user</option>
		<option value="threadspermember">Threads per member</option>
		<option value="repliesperthread">Replies per thread</option>
	</optgroup>
	<optgroup label="==================="></optgroup>
</select>'
	];

}

$db->insert_query_multiple('templates', $data);
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
