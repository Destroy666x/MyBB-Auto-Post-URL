<?php

/*
Name: Auto Post URL
Author: Destroy666
Version: 1.0
Info: Plugin for MyBB forum software, coded for versions 1.8.x.
It automatically sets the URL of the currently viewed post and optionally highlights it.
5 template edits, 1 new settings
Released under GNU GPL v3, 29 June 2007. Read the LICENSE.md file for more information.
Support: official MyBB forum - http://community.mybb.com/mods.php?action=profile&uid=58253 (don't PM me, post on forums)
Bug reports: my github - https://github.com/Destroy666x

© 2016 - date("Y")
*/

if(!defined('IN_MYBB'))
{
	die('What are you doing?!');
}

function auto_post_url_info()
{
	global $db, $lang, $custom_settingsgroup_cache;

	$lang->load('auto_post_url_acp');

	// Configuration link
	if(empty($custom_settingsgroup_cache))
	{
		$q = $db->simple_select('settinggroups', 'gid, name', 'isdefault = 0');

		while($group = $db->fetch_array($q))
			$custom_settingsgroup_cache[$group['name']] = $group['gid'];
	}

	$gid = isset($custom_settingsgroup_cache['auto_post_url']) ? $custom_settingsgroup_cache['auto_post_url'] : 0;
	$auto_post_url_cfg = '<br />';

	if($gid)
	{
		global $mybb;

		$auto_post_url_cfg = '<a href="index.php?module=config&amp;action=change&amp;gid='.$gid.'">'.$lang->configuration.'</a>
<br /><a href="index.php?module=tools-system_health&amp;action=clean_PHP_files">'.$lang->auto_post_url_check.'</a>
<br />
<br />';
	}

	return array(
		'name'			=> $lang->auto_post_url,
		'description'	=> $lang->auto_post_url_info.'<br />
'.$auto_post_url_cfg.'
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ZRC6HPQ46HPVN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" style="border: 0;" name="submit" alt="Donate">
<img alt="" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" style="border: 0; width: 1px; height: 1px;">
</form>',
		'website'		=> 'https://github.com/Destroy666x',
		'author'		=> 'Destroy666',
		'authorsite'	=> 'https://github.com/Destroy666x',
		'version'		=> 1.0,
		'codename'		=> 'auto_post_url',
		'compatibility'	=> '18*'
	);
}


function auto_post_url_activate()
{
	global $db;

	// Modify templates
	require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets('postbit', '#'.preg_quote('id="pid{$post[\'pid\']}"').'#i', 'id="pid{$post[\'pid\']}" class="post_anchor" href="{$post[\'postlink\']}#pid{$post[\'pid\']}" title="{$post[\'subject_title\']}"');
	find_replace_templatesets('postbit_classic', '#'.preg_quote('id="pid{$post[\'pid\']}"').'#i', 'id="pid{$post[\'pid\']}" class="post_anchor" href="{$post[\'postlink\']}#pid{$post[\'pid\']}" title="{$post[\'subject_title\']}"');
	find_replace_templatesets('postbit', '#^(.*?)$#is', '$1
<a name="bottompid{$post[\'pid\']}" id="bottompid{$post[\'pid\']}"></a>');
	find_replace_templatesets('postbit_classic', '#^(.*?)$#is', '$1
<a name="bottompid{$post[\'pid\']}" id="bottompid{$post[\'pid\']}"></a>');
	find_replace_templatesets('showthread', '#(thread\.js(\?ver=[a-z0-9_]+)?'.preg_quote('"></script>').')#i', '$1
<script type="text/javascript">
<!--
	var autoposturl_newclass = \'{$sanitized_class}\';
	var autoposturl_bbname = \'{$sanitized_bbname}\';
//-->
</script>
<script type="text/javascript" src="{$mybb->asset_url}/jscripts/auto_post_url.js?ver=1"></script>');

	// Settings
	if(!$db->fetch_field($db->simple_select('settinggroups', 'COUNT(1) AS cnt', "name ='auto_post_url'"), 'cnt'))
	{
		global $lang;

		$lang->load('auto_post_url_acp');

		$auto_post_url_settinggroup = array(
			'name'			=> 'auto_post_url',
			'title'			=> $db->escape_string($lang->auto_post_url),
			'description'	=> $db->escape_string($lang->auto_post_url_settings),
			'disporder'		=> 666,
			'isdefault'		=> 0
		);

		$db->insert_query('settinggroups', $auto_post_url_settinggroup);

		$auto_post_url_setting = array(
			'name'			=> 'auto_post_url_class',
			'title'			=> $db->escape_string($lang->auto_post_url_class),
			'description'	=> $db->escape_string($lang->auto_post_url_class_desc),
			'optionscode'	=> 'text',
			'value'			=> '',
			'disporder'		=> 0,
			'gid'			=> $db->insert_id()
		);

		$db->insert_query('settings', $auto_post_url_setting);

		rebuild_settings();
	}
}

function auto_post_url_deactivate()
{
	global $db;

	require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets('postbit', '/\s*'.preg_quote('class="post_anchor" href="{$post[\'postlink\']}#pid{$post[\'pid\']}" title="{$post[\'subject_title\']}"').'/i', '');
	find_replace_templatesets('postbit_classic', '/\s*'.preg_quote('class="post_anchor" href="{$post[\'postlink\']}#pid{$post[\'pid\']}" title="{$post[\'subject_title\']}"').'/i', '');
	find_replace_templatesets('postbit', '#\s*'.preg_quote('<a name="bottompid{$post[\'pid\']}" id="bottompid{$post[\'pid\']}"></a>').'#i', '');
	find_replace_templatesets('postbit_classic', '#\s*'.preg_quote('<a name="bottompid{$post[\'pid\']}" id="bottompid{$post[\'pid\']}"></a>').'#i', '');
	find_replace_templatesets('showthread', '#\s*'.preg_quote('<script type="text/javascript">
<!--
	var autoposturl_newclass = \'{$sanitized_class}\';
	var autoposturl_bbname = \'{$sanitized_bbname}\';
//-->
</script>
<script type="text/javascript" src="{$mybb->asset_url}/jscripts/auto_post_url.js?ver=1"></script>').'#i', '');

	$db->delete_query('settings', "name = 'auto_post_url_class'");
	$db->delete_query('settinggroups', "name = 'auto_post_url'");

	rebuild_settings();
}

$plugins->add_hook('showthread_start', 'auto_post_url_showthread');

function auto_post_url_showthread()
{
	global $mybb;

	$GLOBALS['sanitized_bbname'] = str_replace("'", "\'", htmlspecialchars_uni($mybb->settings['bbname']));
	$GLOBALS['sanitized_class'] = str_replace("'", "\'", htmlspecialchars_uni($mybb->settings['auto_post_url_class']));
}