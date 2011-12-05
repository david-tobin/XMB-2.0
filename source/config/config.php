<?php

/**
 * XMB Configuration
 * 
 * @Copyright (c) 2001-2009 The XMB Group
 */

/** ####################### **/
/** XMB Configuration Begin **/
/** ####################### **/

/** ###################### **/
/** Database Configuration **/
/** ###################### **/

/**
 * *********************************************
 * Please enter the following information below:
 * Fields marked with an (*) are required.
 * *********************************************
 * Database Driver: Database Software to use. (mysql, pgsql, mssql, oci)
 * Database Server: Usually 127.0.0.1  *
 * Database User: User assigned to your database. *
 * Database Password: Password given to your user. *
 * Database Name: The name of your database. *
 * *********************************************
 * Table Prefix: Prefix given to your XMB tables. *
 * *********************************************
 */
$config['db_driver'] = 'mysql';
$config['db_server'] = '127.0.0.1';
$config['db_port'] = 3306;
$config['db_user'] = 'root';
$config['db_pass'] = '';
$config['db_database'] = 'xmb';
$config['xprefix'] = 'xmb_';

/**	 ######################	**/
/**	   Path Configuration 	**/
/**	 ###################### **/

/**
 * *********************************************
 * Please enter the following information below:
 * Fields marked with an (*) are required.
 * *********************************************
 * Path To XMB: XMB will attempt to guess this for you.
 * 				If the guess fails you will have to 
 * 				manually enter this.
 * *********************************************
 */
$config['xpath'] = getcwd();

/**	 ######################	**/
/**	     Administrators		**/
/**	 ###################### **/

/**
 * *********************************************
 * Please enter the following information below:
 * Fields marked with an (*) are required.
 * *********************************************
 * Super Administrators: Enter the uid's of the
 * 						 user's you would like 
 * 						 as Super Administrators.
 * 						 Separate multiple with a
 * 						 comma (,).
 * 
 * Default: 1
 * *********************************************
 */
$config['superadmins'] = '1';

/**	 ######################	**/
/**	    	 Misc			**/
/**	 ###################### **/

/**
 * *********************************************
 * Please enter the following information below:
 * Fields marked with an (*) are required.
 * *********************************************
 * Debug Mode: 		This options will set whether
 * 					debug mode will be enabled
 * 					on the site. If you do not
 * 					understand debug mode, set this
 * 					option to 0. Debug mode will
 * 					allow you to view such things
 * 					as querys made, admin error
 * 					messages etc. 
 * 
 * 					NOTE: This is only seen
 * 					by administrators!
 * 
 * Default: 0
 * *********************************************
 */
$config['debug'] = 0;

?>