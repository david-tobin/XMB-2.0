<?php

class XMB_Registry
{
	protected $vars = array();

	/**
	 * Controls Debug Mode
	 * @var boolean
	 */
	public $debug = false;

	/**
	 * Holds Config Values
	 * @var array
	 */
	public $config = array();

	/**
	 * Variable To Check If APC
	 * Is Installed
	 * @var boolean
	 */
	public $apc = false;
	/**
	 * Holds user info
	 * @var array
	 */
	public $user = array();

	/**
	 * Holds site options
	 * @var array
	 */
	public $options = array();

	/**
	 * Holds site phrases
	 * @var array
	 */
	public $phrases = array();

	/**
	 * Holds hook plugin data
	 * @var array
	 */
	protected $hookdata = array();

	/**
	 * Holds current controller value
	 * @var string
	 */
	public $controller = '';

	/**
	 * Holds current action value
	 * @var unknown_type
	 */
	public $action = '';

	/**
	 * Holds current id value
	 * @var unknown_type
	 */
	public $id = '';

	/**
	 *
	 * @var unknown_type
	 */
	public $loginstatus = '';

	/**
	 * Holds information going from a model to it's controller
	 * @var array
	 */
	public $modeltocontroller = array();

	/**
	 * Holds URL query information (?var1=val1) etc
	 * Usage: $urlquery['var1'] returns val1
	 * @var array
	 */
	public $urlquery = array();

	/**
	 * Holds any notice to be displayed
	 * @var unknown_type
	 */
	public $notice = '';

	/**
	 * Holds last error (If one exists)
	 * @var array
	 */
	public $lasterror = array();

	/**
	 * Holds the current usercp template
	 * Default: usercp_home
	 * @var unknown_type
	 */
	public $usercp_method = 'usercp_home';

	/**
	 * Holds the current page navbits
	 * @var unknown_type
	 */
	public $navbits = '';

	/**
	 * Holds permissions
	 * 
	 * @var array
	 */
	public $perms = array();

	/**
	 * Holds the current online members
	 * 
	 * @var array
	 */
	public $whoisonline = array();

	/**
	 * Holds current session information
	 * 
	 * @var array
	 */
	protected $session = array();

	/**
	 * Value to see if we are in admin area
	 * 
	 * @var bool
	 */
	protected $in_admin = false;
	/**
	 * Holds PHP Errors
	 * 
	 * @var array
	 */
	public $errors = array();
	/**
	 * Holds available modules
	 * 
	 * @var array
	 */
	public $modules = array();
	
	public function __construct()
	{
		if (!defined('IN_CODE'))
		{
			exit('Not allowed to call this file directly');
		}

		ob_start();
	}
}

?>