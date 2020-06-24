<?php

/***************************************************************************
 *
 *	OUGC Email ACP Logins plugin (/inc/plugins/ougc_email_acp_logins.php)
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
 
// Die if IN_MYBB is not defined, for security reasons.
if(!defined('IN_MYBB'))
{
	die('This file cannot be accessed directly.');
}

define('OUGC_EMAIL_ACP_LOGINS_ROOT', MYBB_ROOT . 'inc/plugins/ougc_email_acp_logins');

require_once OUGC_EMAIL_ACP_LOGINS_ROOT.'/core.php';

// Add our hooks
if(defined('IN_ADMINCP'))
{
	require_once OUGC_EMAIL_ACP_LOGINS_ROOT.'/admin.php';

	require_once OUGC_EMAIL_ACP_LOGINS_ROOT.'/admin_hooks.php';

	\OUGCEmailACPLogin\Core\addHooks('OUGCEmailACPLogin\AdminHooks');
}

// Plugin API
function ougc_email_acp_logins_info()
{
	return \OUGCEmailACPLogin\Admin\_info();
}

// Activate the plugin.
function ougc_email_acp_logins_activate()
{
	\OUGCEmailACPLogin\Admin\_activate();
}

// Check if installed.
function ougc_email_acp_logins_is_installed()
{
	return \OUGCEmailACPLogin\Admin\_is_installed();
}

// Unnstall the plugin.
function ougc_email_acp_logins_uninstall()
{
	\OUGCEmailACPLogin\Admin\_uninstall();
}