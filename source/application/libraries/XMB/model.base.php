<?php

abstract class Model
{
	protected $registry;

	/**
	 * Model::__construct()
	 *
	 * @param mixed $registry
	 * @return
	 */
	public function __construct($registry)
	{
		if (!defined('IN_CODE'))
		{
			exit('Not allowed to call this file directly');
		}

		$this->registry = &$registry;
	}

	/**
	 * Model::index()
	 *
	 * @param mixed $id
	 * @return
	 */
	abstract function index($id);
}

?>