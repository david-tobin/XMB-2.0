<?php

if (!defined('IN_CODE'))
{
	die('You cannot run this file directly');
}
/**
 * Base Datahandler Class
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Datahandler
{
	protected $registry;

	/**
	 * XMB_Datahandler::__construct()
	 * 
	 * @param mixed $registry
	 * @return
	 */
	public function __construct($registry)
	{
		$this->registry = &$registry;
	}
}
