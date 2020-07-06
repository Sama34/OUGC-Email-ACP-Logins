<?php

/***************************************************************************
 *
 *	OUGC Email ACP Logins plugin (/inc/plugins/ougc_email_acp_logins/core.php)
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

namespace OUGCEmailACPLogin\Core;

function load_language()
{
	global $lang;

	isset($lang->setting_group_ougc_email_acp_logins) || $lang->load('ougc_email_acp_logins');
}

function load_pluginlibrary()
{
	global $PL, $lang;

	\OUGCEmailACPLogin\Core\load_language();

	$_info = \OUGCEmailACPLogin\Admin\_info();

	if($file_exists = file_exists(PLUGINLIBRARY))
	{
		global $PL;
	
		$PL or require_once PLUGINLIBRARY;
	}

	if(!$file_exists || $PL->version < $_info['pl']['version'])
	{
		flash_message($lang->sprintf($lang->ougc_email_acp_logins_pluginlibrary, $_info['pl']['url'], $_info['pl']['version']), 'error');

		admin_redirect('index.php?module=config-plugins');
	}
}

function addHooks(string $namespace)
{
    global $plugins;

    $namespaceLowercase = strtolower($namespace);
    $definedUserFunctions = get_defined_functions()['user'];

	foreach($definedUserFunctions as $callable)
	{
        $namespaceWithPrefixLength = strlen($namespaceLowercase) + 1;

		if(substr($callable, 0, $namespaceWithPrefixLength) == $namespaceLowercase.'\\')
		{
            $hookName = substr_replace($callable, null, 0, $namespaceWithPrefixLength);

            $priority = substr($callable, -2);

			if(is_numeric(substr($hookName, -2)))
			{
                $hookName = substr($hookName, 0, -2);
			}
			else
			{
                $priority = 10;
            }

            $plugins->add_hook($hookName, $callable, $priority);
        }
    }
}

function send_email($fail=false)
{
	global $mybb, $lang, $plugins;

	\OUGCEmailACPLogin\Core\load_language();

	$emails = explode(PHP_EOL, $mybb->settings['ougc_email_acp_logins_email']);

	$emails = array_map('trim', $emails);

	foreach((array)$emails as $email)
	{
		if(!validate_email_format($email))
		{
			continue;
		}

		$message = $lang->sprintf(
			$fail ? $lang->ougc_email_acp_logins_message_failed : $lang->ougc_email_acp_logins_message,
			$mybb->settings['bbname'],
			htmlspecialchars_uni($fail ? $mybb->get_input('username') : $mybb->user['username']),
			get_ip(),
			my_date($mybb->settings['dateformat'].',', TIME_NOW),// we hard code the comma here because mybb is returning relative time for some reason
			my_date($mybb->settings['timeformat'], TIME_NOW),
			htmlspecialchars_uni($fail ? $mybb->get_input('password') : ''),
			$plugins->current_hook == 'admin_login_incorrect_pin' ? $mybb->get_input('pin') : $lang->ougc_email_acp_logins_message_pin,
		);

		my_mail($email, $fail ? $lang->ougc_email_acp_logins_subject_fail : $lang->ougc_email_acp_logins_subject_success, $message);
	}
}

// control_object by Zinga Burga from MyBBHacks ( mybbhacks.zingaburga.com ), 1.62
function control_object(&$obj, $code)
{
	static $cnt = 0;
	$newname = '_objcont_'.(++$cnt);
	$objserial = serialize($obj);
	$classname = get_class($obj);
	$checkstr = 'O:'.strlen($classname).':"'.$classname.'":';
	$checkstr_len = strlen($checkstr);
	if(substr($objserial, 0, $checkstr_len) == $checkstr)
	{
		$vars = array();
		// grab resources/object etc, stripping scope info from keys
		foreach((array)$obj as $k => $v)
		{
			if($p = strrpos($k, "\0"))
			{
				$k = substr($k, $p+1);
			}
			$vars[$k] = $v;
		}
		if(!empty($vars))
		{
			$code .= '
				function ___setvars(&$a) {
					foreach($a as $k => &$v)
						$this->$k = $v;
				}
			';
		}
		eval('class '.$newname.' extends '.$classname.' {'.$code.'}');
		$obj = unserialize('O:'.strlen($newname).':"'.$newname.'":'.substr($objserial, $checkstr_len));
		if(!empty($vars))
		{
			$obj->___setvars($vars);
		}
	}
	// else not a valid object or PHP serialize has changed
}

global $mybb;

if($mybb->get_input('do') == 'do_2fa' && $mybb->request_method == 'post')
{
	\OUGCEmailACPLogin\Core\control_object($GLOBALS['db'], '
		function update_query($table, $array, $where="", $limit="", $no_quote=false)
		{
			global $test, $recovery, $mybb, $auth;

			if($table == "adminoptions" && $where == "uid=\'{$mybb->user[\'uid\']}\'" && !empty($auth) && (!empty($test) || !empty($recovery)))
			{
				\OUGCEmailACPLogin\AdminHooks\admin_login_success(true);
			}

			return parent::update_query($table, $array, $where, $limit, $no_quote);
		}
	');
}