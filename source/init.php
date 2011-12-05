<?php

/**
 * XMB Initialization File
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */

/**
 * Load The Config
 */
include ('./config/config.php');

/** Definitions **/
define('X_PATH', $config['xpath']);
define('X_TIME', time());
define('X_PREFIX', $config['xprefix']);
define('X_START', microtime(true));
define('IN_CODE', true);

/**
 * Include The Framework
 */
require_once (X_PATH . '/application/libraries/XMB/controller.base.php');
require_once (X_PATH . '/application/libraries/XMB/registry.base.php');
require_once (X_PATH . '/application/libraries/XMB/router.base.php');
require_once (X_PATH . '/application/libraries/XMB/template.base.php');
require_once (X_PATH . '/application/libraries/XMB/model.base.php');

/**
 * Load Base Classes Used By All.
 */
require_once (X_PATH . '/application/classes/pdo.class.php');
require_once (X_PATH . '/application/classes/clean.class.php');
require_once (X_PATH . '/application/classes/permissions.class.php');
require_once (X_PATH . '/application/classes/functions.class.php');
require_once (X_PATH . '/application/classes/cache.class.php');
require_once (X_PATH . '/application/classes/cron.class.php');

/**
 * Initiate Main Datahandler Class
 */
require_once (X_PATH . '/application/datahandlers/main.datahandle.php');

/** Create Instance Of XMB **/
$xmb = new XMB_Core();

/** Set Configuration **/
$xmb->config = &$config;

/** Connect to the database **/
$database = array('server' => $config['db_server'], 'user' => $config['db_user'],
	'pass' => $config['db_pass'], 'database' => $config['db_database']);

/** Create Class Instances **/
try {
	$xmb->db = new PDO($config['db_driver'] . ':dbname=' . $config['db_database'] .
		';host=' . $config['db_server'], $config['db_user'], $config['db_pass'], array(PDO::
		ATTR_ERRMODE => true, PDO::ERRMODE_EXCEPTION => true));
}
catch (PDOException $e) {
	die('Connection Failed: ' . $e->getMessage());
}

$xmb->cleaner = new XMB_Cleaner($xmb);
$xmb->perm = new XMB_Permission_Manager($xmb);
$xmb->cache = new XMB_Cache($xmb);
$xmb->cron = new XMB_Cron($xmb);

$xmb->modules_init();

require_once (X_PATH . '/application/classes/module.class.php');
Module::set_registry($xmb);

/**

 * $xmb->secure = new XMB_Security($xmb);
 * $xmb->secure->sentence('_POST');
 * $xmb->secure->sentence('_GET');
 * $xmb->secure->sentence('_REQUEST');
 * $xmb->secure->sentence('_COOKIE');
 * $xmb->secure->sentence('_FILES');
 * $xmb->secure->sentence('_ENV');

 **/

/** Error Handling **/
function xmb_error_handler($errno, $errstr, $errfile, $errline)
{
	global $xmb;
	
	if ($errno != 8) {
		$error = '';
		$error .= '<tr style="border: 1px solid #D0D0D0;"><td class="alt1">Error [' . $errno .
			'] ' . $errstr . '</td>';
		$error .= '<td class="alt1">See line ' . $errline . ' in ' . $errfile .
			'</td></tr>';

		$xmb->errors[] = $error;
	}
	return true;
}

set_error_handler('xmb_error_handler');

/** Check For APC (Alternative PHP Cache) **/
if (function_exists('apc_fetch'))
	$xmb->apc = true;

/** Deactivate Magic Quotes **/
$xmb->remove_magic();

/** Get URL Queries **/
$xmb->geturlquery();

/** Set Version Information **/
$version = array('package' => 'XMB', 'version' => '2.0', 'beta' => '', 'alpha' =>
	'Pre-Alpha');

$xmb->version = $version;

/** Load Settings **/
if ($xmb->cache->get_cache('options') == false) {
	$options = $xmb->loadsettings();
	$xmb->options = $options;
	$xmb->cache->rebuild_cache('options', $options);
} else {
	$xmb->options = $xmb->cache->get_cache('options');
}

/** Check For Cookies **/
$xmb->cleaner->clean('c', 'xmblastvisit', 'TYPE_INT');
$xmb->cleaner->clean('c', 'xmbpasshash', 'TYPE_STR');
$xmb->cleaner->clean('c', 'xmbuserid', 'TYPE_INT');
$xmb->cleaner->clean('c', 'xmbsecuritytoken', 'TYPE_STR');

$lastvisit = (isset($xmb->cleaner->cleaned['xmblastvisit']) ? $xmb->cleaner->
	cleaned['xmblastvisit'] : '0');

/** Grab Userinfo **/
$passhash = (isset($xmb->cleaner->cleaned['xmbpasshash']) ? $xmb->cleaner->
	cleaned['xmbpasshash'] : false);
$userid = (isset($xmb->cleaner->cleaned['xmbuserid']) ? $xmb->cleaner->cleaned['xmbuserid'] : false);

/** Set Default Userinfo */
$userinfo = array('username' => 'Guest', 'uid' => '0', 'lastvisit' => '0',
	'posts' => '0', 'lastvisit' => '0', 'securitytoken' => 'na', 'regdate' => '0',
	'usergroupid' => '3', 'unreadpm' => '0', 'status' => '', 'pmtotal' => 0,
	'sidebar' => 1, 'sidebar_specific' => serialize(array('all')));

if (isset($userid) && isset($passhash) && $userid != false && $passhash != false) {
	$userinfo = $xmb->db->prepare("SELECT * FROM " . X_PREFIX .
		"members WHERE uid = :uid ");
	$userinfo->execute(array(':uid' => $userid));

	$userinfo = $userinfo->fetch();
}

$userinfo['type'] = 'user';

if (empty($userinfo['sidebar_specific']))
	$userinfo['sidebar_specific'] = serialize(array());
	
/** Check if the forum is online **/
if ($xmb->options['site_online'] == 0) {
	$xmb->notice = $xmb->options['site_offline_message'];

	if (in_array($userinfo['uid'], explode(',', $config['superadmins']))) {
		$xmb->createsession($userinfo);
		$xmb->notice = 'The forum is currently offline.';
	}
} else {
	/** Create New Guest/User Session **/
	$xmb->createsession($userinfo);
}
/** Convert Times **/
$xmb->options['timeformat'] = 'D M d Y @ h:i a'; // DEBUGGING
$userinfo['lastvisit'] = $xmb->xmbtime($xmb->options['timeformat'], $userinfo['lastvisit'],
	1);
$userinfo['regdate'] = $xmb->xmbtime('d M Y', $userinfo['regdate']);

$userinfo['moderator'] = 1; // DEBUGGING
$userinfo['usernamelink'] = str_replace(' ', '-', $userinfo['username']);

/** Set Global Userinfo **/
$xmb->user = $userinfo;

/** Set Online Status **/
if ($xmb->user['uid'] > 0) {
	$xmb->user['online'] = 'online';
} else {
	$xmb->user['online'] = 'offline';
}

/** Set New Last Visit Time **/
$xmb->setcookie('xmblastvisit', X_TIME, X_TIME + (60 * 60 * 24 * 30));

/** Set Current Time **/
$thetime = $xmb->xmbtime('h:ia', X_TIME, 0);
$xmb->options['thetime'] = $thetime;

/** Update Userinfo **/

$securitytoken = $xmb->generate_securitytoken();

if ($securitytoken != false) {
	if ($xmb->user['uid'] > 0) {
		$xmb->db->query("
		UPDATE " . X_PREFIX . "members SET 
		securitytoken = '" . $securitytoken . "',
		lastvisit = '" . X_TIME . "' WHERE uid = '" . $xmb->user['uid'] . "'
		");
	}

	$xmb->setcookie('xmbsecuritytoken', $securitytoken, X_TIME + (60 * 60 * 24 * 30));

	$xmb->user['securitytoken'] = $securitytoken;
}

$xmb->cron->check_cron();

/** Load Permissions **/
$xmb->perm->load_permissions();

$perms = array( // DEBUGGING
	'is_admin' => 0, 'is_mod' => 0, 'can_lock' => 0, 'can_softdelete' => 0,
	'can_harddelete' => 0, 'can_editown' => 0, 'can_editall' => 0, 'can_deleteown' =>
	0, 'can_rename' => 0, 'can_newthread' => 0, 'can_reply' => 0, 'can_pm' => 0,
	'can_links' => 0);


/** WHO'S ONLINE **/
$who = $xmb->db->query("
			SELECT * FROM " . X_PREFIX . "session 
			WHERE sesstype = 'user' 
			ORDER BY username ASC
		");

$onlinelist = array();
$onlineusers = array();
$onlinecount = 0;
$guestcount = 0;

foreach ($who as $online) {
	if ($online['username'] == 'Guest') {
		$guestcount++;
	} else
		if (!in_array($online['username'], $onlineusers)) {
			$onlinecount++;
			array_push($onlineusers, $online['username']);
			$onlinelist[] = '<a href="' . $xmb->options['site_url'] . '/members/profile/' .
				str_replace(' ', '-', $online['username']) . '/">' . $online['username'] .
				'</a>';
		}
}

$usersonline = implode(', ', $onlinelist);

$xmb->whoisonline = $onlinelist;

/** LATEST POSTS **/
$latest = $xmb->db->query("
			SELECT * FROM " . X_PREFIX . "posts
			ORDER BY dateline DESC
			LIMIT 5
		");

// @TODO This has bad interference with the ajax controller. A fix will be needed.
// if ($xmb->fetch_hook('global_start') != false) eval($xmb->fetch_hook('global_start'));

?>