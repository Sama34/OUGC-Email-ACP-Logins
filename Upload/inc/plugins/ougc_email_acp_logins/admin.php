<?php

/***************************************************************************
 *
 *	OUGC Email ACP Logins plugin (/inc/plugins/ougc_email_acp_logins/admin.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2020 Omar Gonzalez
 *
 *	Website: https://ougc.network
 *
 *	Email administrators about ACP login attempts.
 *
 ***************************************************************************
 
****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

namespace OUGCEmailACPLogin\Admin;

function _info()
{
	global $lang;

	\OUGCEmailACPLogin\Core\load_language();

	return [
		'name'			=> 'OUGC Email ACP Logins',
		'description'	=> $lang->setting_group_ougc_email_acp_logins_desc,
		'website'		=> 'https://ougc.network',
		'author'		=> 'Omar G.',
		'authorsite'	=> 'https://ougc.network',
		'version'		=> '1.8.0',
		'versioncode'	=> 1800,
		'compatibility'	=> '18*',
		'codename'		=> 'ougc_email_acp_logins',
		'pl'			=> [
			'version'	=> 13,
			'url'		=> 'https://community.mybb.com/mods.php?action=view&pid=573'
		]
	];
}

function _activate()
{
	global $PL, $lang, $cache;

	\OUGCEmailACPLogin\Core\load_pluginlibrary();

	$PL->settings('ougc_email_acp_logins', $lang->setting_group_ougc_email_acp_logins, $lang->setting_group_ougc_email_acp_logins_desc, [
		'type' => [
			'title' => $lang->setting_ougc_email_acp_logins_type,
			'description' => $lang->setting_ougc_email_acp_logins_type_desc,
			'optionscode' => "checkbox
success={$lang->setting_ougc_email_acp_logins_type_success}
fail={$lang->setting_ougc_email_acp_logins_type_fail}",
			'value' =>	'success,fail',
		],
		'email' => [
			'title' => $lang->setting_ougc_email_acp_logins_email,
			'description' => $lang->setting_ougc_email_acp_logins_email_desc,
			'optionscode' => 'textarea',
			'value' =>	'',
		],
	]);

	// Insert/update version into cache
	$plugins = $cache->read('ougc_plugins');

	if(!$plugins)
	{
		$plugins = [];
	}

	$_info = \OUGCEmailACPLogin\Admin\_info();

	if(!isset($plugins['emailacplogins']))
	{
		$plugins['emailacplogins'] = $_info['versioncode'];
	}

	/*~*~* RUN UPDATES START *~*~*/

	/*~*~* RUN UPDATES END *~*~*/

	$plugins['emailacplogins'] = $_info['versioncode'];

	$cache->update('ougc_plugins', $plugins);
}

function _is_installed()
{
	global $cache;

	$plugins = (array)$cache->read('ougc_plugins');

	return isset($plugins['emailacplogins']);
}

function _uninstall()
{
	global $db, $PL, $cache;

	\OUGCEmailACPLogin\Core\load_pluginlibrary();

	$PL->settings_delete('ougc_email_acp_logins');

	// Delete version from cache
	$plugins = (array)$cache->read('ougc_plugins');

	if(isset($plugins['emailacplogins']))
	{
		unset($plugins['emailacplogins']);
	}

	if(!empty($plugins))
	{
		$cache->update('ougc_plugins', $plugins);
	}
	else
	{
		$cache->delete('ougc_plugins');
	}
}