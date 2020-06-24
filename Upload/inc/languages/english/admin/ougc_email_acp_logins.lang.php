<?php

/***************************************************************************
 *
 *	OUGC Email ACP Logins plugin (/inc/languages/english/admin/ougc_email_acp_logins.lang.php)
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

$l = [
	'setting_group_ougc_email_acp_logins' => 'OUGC Email ACP Logins',
	'setting_group_ougc_email_acp_logins_desc' => 'Email administrators about ACP login attempts.',
	'setting_ougc_email_acp_logins_type' => 'Login Type',
	'setting_ougc_email_acp_logins_type_desc' => 'Select which login types to email.',
	'setting_ougc_email_acp_logins_type_success' => 'Success',
	'setting_ougc_email_acp_logins_type_fail' => 'Fail',
	'setting_ougc_email_acp_logins_email' => 'Email',
	'setting_ougc_email_acp_logins_email_desc' => 'Insert the email to send the message to. One email per line.',
	'ougc_email_acp_logins_subject' => 'ACP Login Attempt',
	'ougc_email_acp_logins_message' => 'Hi,

This is an automatic email to notify you that someone has recently attempted to login into the ACP at {1} and succeeded.

Username: {2}
IP Address: {3}
Time: {4} {5}

If this is not you we highly recommend you review this and inform a staff member immediately.

Regards,
{1}',
	'ougc_email_acp_logins_message_failed' => 'Hi,

This is an automatic email to notify you that someone has recently attempted to login into the ACP at {1} and failed.

Username: {2}
IP Address: {3}
Time: {4} {5}
Password: {6}

If this is not you we highly recommend you review this and inform a staff member immediately.

Regards,
{1}',
];