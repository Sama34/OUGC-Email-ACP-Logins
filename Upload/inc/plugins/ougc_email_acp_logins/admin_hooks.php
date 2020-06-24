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

function admin_login_success()
{
	global $mybb;

	if(my_strpos($mybb->settings['ougc_email_acp_logins_type'], 'success') === false)
	{
		return;
	}

	\OUGCEmailACPLogin\Core\send_email();
}

function admin_login_fail()
{
	global $mybb, $lang;

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