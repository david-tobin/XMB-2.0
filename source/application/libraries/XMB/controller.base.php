<?php

abstract class Controller
{
	protected $registry;

	public function __construct($registry)
	{
		if (!defined('IN_CODE'))
		{
			exit('Not allowed to call this file directly');
		}

		$this->registry = &$registry;
	}

	abstract function index($id = '');
}

?>