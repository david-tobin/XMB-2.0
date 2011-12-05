<?php

/**
 * XMB Search Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class searchController extends Controller
{
	private $method = '';

	public function index($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'index';

		if ($this->registry->fetch_hook('search_index_start') != false)
			eval($this->registry->fetch_hook('search_index_start'));

		$phrases = array('search');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('search', 'index', $id);

		$this->registry->construct_navbits(array('' => 'Search'));

		$this->registry->view->loadview('search', 'search', 'Search'); // PRINT PAGE
	}

	public function today($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'today';

		if ($this->registry->fetch_hook('search_today_start') != false)
			eval($this->registry->fetch_hook('search_today_start'));

		$phrases = array('search');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('search', 'today', $id);

		$this->registry->construct_navbits(array('/search/index' => 'Search', '' =>
			'Today\'s Posts'));

		$this->registry->view->loadview('search_today', 'search', 'Today\'s Posts'); // PRINT PAGE
	}

	public function dosearch($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'dosearch';

		if ($this->registry->fetch_hook('search_do_start') != false)
			eval($this->registry->fetch_hook('search_do_start'));

		$phrases = array('search');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('search', 'dosearch', $id);

		$this->registry->construct_navbits(array('/search/index' => 'Search', '' =>
			'Results'));

		$this->registry->view->loadview('search_results', 'search', 'Search Results'); // PRINT PAGE
	}
}

?>