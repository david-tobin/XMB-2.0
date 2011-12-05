<?php

/**
 * Statistics Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class StatsController extends Controller
{

	function index()
	{
		$this->show();
	}

	function show()
	{
		$this->method = 'show';

		$this->registry->view->setvar('myvar', 'Variable Loaded Successfully!');

		$this->registry->view->loadview($this->method);
	}
}

?>