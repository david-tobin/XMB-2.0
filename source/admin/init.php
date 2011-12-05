<?php
/**
 * XMB Initialization File
 * 
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2009 XMB Group
 * @Licence GPL 3.0
 * @Version $Id:$ 
 */

/**
 * Load The Config 
 */
include ('../config/config.php');

/** Definitions **/
define('X_PATH', $config['xpath']);
define('X_TIME', time());
define('X_PREFIX', $config['xprefix']);
define('X_START', microtime(true));

/**
 * Include The Framework
 */
require_once ('./classes/registry.class.php');

/**
 * Load Base Classes Used By All.
 */
require_once ('../application/classes/pdo.class.php');
require_once ('../application/classes/clean.class.php');
require_once ('../application/classes/functions.class.php');
require_once ('../application/classes/cache.class.php');
require_once ('./functions/layout.functions.php');
/**
 * Initiate Main Datahandler Class
 */
require_once ('../application/datahandlers/main.datahandle.php');

/** Create Instance Of XMB **/
$admin = new XMB_Core();

/** Set Configuration **/
$admin->config =& $config;

/** Connect to the database **/
$database = array (
	'server' => $config['db_server'], 'user' => $config['db_user'], 'pass' => $config['db_pass'], 'database' => $config['db_database']
);

/** Create Class Instances **/
try {
	$admin->db = new PDO($config['db_driver'].':dbname='.$config['db_database'].';host='.$config['db_server'], $config['db_user'], $config['db_pass'], array(PDO::ATTR_ERRMODE => true, PDO::ERRMODE_EXCEPTION => true));
} catch (PDOException $e) {
	die('Connection Failed: ' . $e->getMessage());
}


$admin->cleaner = new XMB_Cleaner($admin);
$admin->cache = new XMB_Cache($admin);

/** Set Debug Mode **/
$admin->debug = true;

/** Deactivate Magic Quotes **/
$admin->remove_magic();

/** Get URL Queries **/
$admin->geturlquery();

/** Set Version Information **/
$version = array (
	'package' => 'XMB', 'version' => '2.0', 'beta' => '', 'alpha' => 'Pre-Alpha'
);

$admin->version = $version;

$activetime = X_TIME-(60*60);

$admin->db->query("
	DELETE FROM ".X_PREFIX."session
	WHERE lastactive < $activetime  AND sesstype = 'admin'
");
?>