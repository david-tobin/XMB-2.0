<?php
/**
 * XMB Administration File
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2009 XMB Group
 * @Licence GPL 3.0
 * @Version $Id:$
 */
require_once('./init.php');

$admin->cleaner->clean('r', 'do', 'TYPE_STR');

$admin->page = (empty($admin->page)) ? $admin->cleaner->do : $admin->page;

$admin->cleaner->clean('c', 'xmbadminuserid', 'TYPE_STR');

die($admin->cleaner->xmbadminuserid);

$userid = (isset($admin->cleaner->cleaned['xmbadminuserid']) ? $admin->cleaner->cleaned['xmbadminuserid'] : false);

$activetime = X_TIME-(60*15);
/** Check if we need to login **/
$logged = $admin->db->query("
	SELECT * FROM ".X_PREFIX."session
	WHERE sesstype = 'admin' && userid = '".$userid."'
");

if ($logged->rowCount() > 0) {
	$userinfo = $admin->db->query_one("
		SELECT * FROM ".X_PREFIX."members
		WHERE uid = '".$userid."'
	");
	$userinfo = $userinfo->fetch();

	if ($userinfo['uid'] > 0 && $userinfo['usergroupid'] == 1) {
		$admin->createsession($userinfo);
		
		$admin->db->query("
			UPDATE ".X_PREFIX."session
			SET lastactive = '".X_TIME."'
			WHERE userid = '".$userinfo['uid']."'
		");
	}
} else if ($admin->page != 'processlogin' || $admin->page != 'login') {
	$admin->login();
}

$admin->user = $userinfo;

switch($admin->page) {
	case 'main' :
		$include = './pages/main.php';
		break;

	case 'login' :
		$include = './pages/login.php';
		break;

	case 'processlogin' :
		$include = './pages/processlogin.php';
		break;

	case 'lookfeel' :
		$include = './pages/lookfeel.php';
		break;

	case 'settings' :
		$include = './pages/settings.php';
		break;

	case 'forums' :
		$include = './pages/forums.php';
		break;

	case 'members' :
		$include = './pages/members.php';
		break;

	case 'usergroups' :
		$include = './pages/usergroups.php';
		break;

	case 'plugins' :
		$include = './pages/plugin.php';
		break;

	case 'database' :
		$include = './pages/database.php';
		break;

	case 'logs' :
		$include = './pages/logs.php';
		break;

	case 'misc' :
		$include = './pages/misc.php';
		break;
			
	default:
		$include = './pages/main.php';
		break;
}

ob_start();
include('./pages/header.php');
ob_start();
include($include);
ob_start();
include('./pages/footer.php');

ob_flush();

ob_end_clean();
?>