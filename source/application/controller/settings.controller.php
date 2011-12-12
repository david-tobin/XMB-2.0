<?php

/**
 * UserCP Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class settingsController extends Controller
{
	/**
	 *
	 * @var unknown_type
	 */
	private $method = '';

	/**
	 *
	 *
	 */
	public function index($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'settings';

		if ($this->registry->fetch_hook('settings_start') != false)
			eval($this->registry->fetch_hook('settings_start'));

		$phrases = array('settings');
		$this->registry->loadphrases($phrases);

		$this->registry->no_guest();

		$this->registry->loadmodel('settings', 'settings', $id);

		$this->registry->construct_navbits(array('' => 'Settings'));

		$this->registry->view->loadview('settings', 'settings', 'Settings'); // PRINT PAGE
	}

	public function options($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'usercp';

		if ($this->registry->fetch_hook('usercp_options_start') != false)
			eval($this->registry->fetch_hook('usercp_options_start'));

		$phrases = array('usercp');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('usercp', 'options', $id);

		$this->registry->construct_navbits(array('/usercp/index/' =>
			'User Control Panel', '' => 'User Options'));

		$this->registry->view->loadview('options', 'usercp', 'User Options'); // PRINT PAGE
	}
}
