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
}

if(!defined("PROSTATS")){
    define("PROSTATS", MYBB_ROOT."inc/plugins/prostats.php");
}
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

function countpngs_activate()
{
	global $db, $lang;
}


function countpngs_deactivate()
{
	global $db, $mybb;
}

/*************************************************************************************/
// ADMIN PART
/*************************************************************************************/

function countpngs_admin_tools_menu(&$sub_menu)
{
	global $lang;

	$lang->load('countpngs');
	$sub_menu[] = array('id' => 'countpngs', 'title' => $lang->countpngs_index, 'link' => 'index.php?module=tools-countpngs');
}

function countpngs_admin_tools_action_handler(&$actions)
{
	$actions['countpngs'] = array('active' => 'countpngs', 'file' => 'countpngs');
}

function countpngs_admin_permissions(&$admin_permissions)
{
  	global $db, $mybb, $lang;

	$lang->load("countpngs", false, true);
	$admin_permissions['countpngs'] = $lang->countpngs_canmanage;

}

function countpngs_admin()
{
	global $db, $lang, $mybb, $page, $run_module, $action_file, $mybbadmin, $plugins;

	$lang->load("countpngs", false, true);

	if($run_module == 'tools' && $action_file == 'countpngs')
	{
		$page->add_breadcrumb_item($lang->countpngs, 'index.php?module=tools-countpngs');
		$page->output_header($lang->countpngs);

		$sub_tabs['countpngs_count'] = array(
			'title'			=> $lang->countpngs_count,
			'link'			=> 'index.php?module=tools-countpngs',
			'description'	=> $lang->countpngs_count_desc
		);

		$page->output_nav_tabs($sub_tabs, 'countpngs_count');

		if (!$mybb->input['action'])
		{
			$form = new Form("index.php?module=tools-countpngs&amp;action=count", "post", "countpngs");

			$form_container = new FormContainer($lang->countpngs_count);
			$form_container->output_row($lang->countpngs_user, $lang->countpngs_user_desc, $form->generate_text_box('user', '', array('id' => 'user')), 'user');
			$form_container->output_row($lang->countpngs_exemptforums, $lang->countpngs_exemptforums_desc, $form->generate_text_box('exempt', '', array('id' => 'exempt')), 'exempt');
			$form_container->output_row($lang->countpngs_date, $lang->countpngs_date_desc, $form->generate_text_box('date', '', array('id' => 'date')), 'date');
			$form_container->output_row($lang->countpngs_threads, $lang->countpngs_threads_desc, $form->generate_yes_no_radio('countthreads', 0, true, "", ""), 'countthreads');

			$form_container->end();

			$buttons = "";
			$buttons[] = $form->generate_submit_button($lang->countpngs_submit);
			$buttons[] = $form->generate_reset_button($lang->countpngs_reset);
			$form->output_submit_wrapper($buttons);
			$form->end();
		}
		elseif ($mybb->input['action'] == 'count')
		{
			$posts = $threads = 0;

			$user = trim($mybb->input['user']);
			if (!$user || !$db->fetch_field($db->simple_select('users', 'uid', 'username=\''.$db->escape_string($user).'\''), 'uid'))
			{
				flash_message($lang->countpngs_invalid_user, 'error');
				admin_redirect("index.php?module=tools-countpngs");
			}

			$forums = $mybb->input['exempt'];
			if ($forums != '')
			{
				$forums = explode(',', $forums);
			}
			else
				$forums = array();

			$date = $mybb->input['date'];
			if (!$mybb->input['date'])
			{
				flash_message($lang->countpngs_invalid_date, 'error');
				admin_redirect("index.php?module=tools-countpngs");
			}
			$date = strtotime($date);

			$countthreads = intval($mybb->input['countthreads']);

			$query = $db->simple_select("posts", "pid", "username='".$db->escape_string($user)."' AND dateline > ".$date." AND fid NOT IN ('".implode('\',\'', $forums)."')");
			while ($post = $db->fetch_array($query))
			{
				$posts++;
			}

			if ($countthreads == 1)
			{
				$query = $db->simple_select("threads", "tid", "username='".$db->escape_string($user)."' AND dateline > ".$date." AND fid NOT IN ('".implode('\',\'', $forums)."')");
				while ($thread = $db->fetch_array($query))
				{
					$threads++;
				}

				flash_message($lang->sprintf($lang->countpngs_total_count_both, $posts, $threads), 'success');
				admin_redirect("index.php?module=tools-countpngs");
			}
			else {
				flash_message($lang->sprintf($lang->countpngs_total_count, $posts, $threads), 'success');
				admin_redirect("index.php?module=tools-countpngs");
			}
		}

		$page->output_footer();
		exit;
	}
}

?>
