<?php

/**
 * CSS Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class cssController extends Controller
{
	private $method = '';

	public function index($id = '')
	{

	}

	public function load($id = '')
	{
		$id = intval($id);

		$this->registry->loadmodel('css', 'load', $id);
	}
}

?>