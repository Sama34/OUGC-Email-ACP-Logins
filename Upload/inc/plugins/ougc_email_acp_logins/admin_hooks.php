<?php

/***************************************************************************
 *
 *	OUGC Email ACP Logins plugin (/inc/plugins/ougc_ougc_email_acp_logins/admin_hooks.php)
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

namespace OUGCEmailACPLogin\AdminHooks;

function admin_login_success($quick=false)
{
	global $mybb;

	if(!$quick)
	{
		global $db;
	
		$uid = (int)$mybb->user['uid'];
	
		$query = $db->simple_select('adminoptions', 'authsecret', "uid='{$uid}'");
	
		$admin_options = $db->fetch_array($query);
	
		// Skip if 2FA
		if(!empty($admin_options['authsecret']))
		{
			return;
		}
	}

	if(my_strpos($mybb->settings['ougc_email_acp_logins_type'], 'success') === false)
	{
		return;
	}

	\OUGCEmailACPLogin\Core\send_email();
}

function admin_login_fail()
{
	global $mybb;

	if(my_strpos($mybb->settings['ougc_email_acp_logins_type'], 'fail') === false)
	{
		return;
	}

	\OUGCEmailACPLogin\Core\send_email(true);
}

function admin_login_incorrect_pin()
{
	admin_login_fail();
}

function admin_page_show_login_start(&$page)
{
	global $mybb;

	if(/*$page['message'] == $lang->my2fa_failed && */$page['class'] == 'error' && $mybb->get_input('do') == 'do_2fa')
	{
		admin_login_fail();
	}
}