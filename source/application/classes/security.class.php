<?php

/**
 * XMB Core Functions
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Security
{
	private $registry;

	private $jail = array();

	/**
	 * 
	 * @param unknown_type $registry
	 */
	public function __construct($registry)
	{
		$this->registry = &$registry;
	}

	public function sentence($type)
	{
		switch ($type)
		{
			case '_POST':
				$this->jail[$type] = $_POST;
				$GLOBALS['HTTP_POST_VARS'] = null;
				unset($_POST);
				break;

			case '_GET':
				$this->jail[$type] = $_GET;
				$GLOBALS['HTTP_GET_VARS'] = null;
				unset($_GET);
				break;

			case '_REQUEST':
				$this->jail[$type] = $_REQUEST;
				$GLOBALS['HTTP_GET_VARS'] = null;
				unset($_REQUEST);
				break;

			case '_COOKIE':
				$this->jail[$type] = $_COOKIE;
				$GLOBALS['HTTP_COOKIE_VARS'] = null;
				unset($_COOKIE);
				break;

			case '_FILES':
				$this->jail[$type] = $_FILES;
				$GLOBALS['HTTP_POST_FILES'] = null;
				unset($_FILES);
				break;

			case '_ENV':
				$this->jail[$type] = $_ENV;
				$GLOBALS['HTTP_ENV_VARS'] = null;
				unset($_ENV);
		}
	}

	public function get_int($type, $varname)
	{
		if (!isset($this->jail[$type][$varname]))
		{
			return false;
		} else
		{
			return intval($this->jail[$type][$varname]);
		}
	}

	public function get_str($type, $varname)
	{
		if (!isset($this->jail[$type][$varname]))
		{
			return false;
		} else
		{
			return strval($this->jail[$type][$varname]);
		}
	}
}

?>