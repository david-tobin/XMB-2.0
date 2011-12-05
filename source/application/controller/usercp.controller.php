<?php

/**
 * UserCP Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class usercpController extends Controller
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

		$this->method = 'usercp';

		if ($this->registry->fetch_hook('usercp_start') != false)
			eval($this->registry->fetch_hook('usercp_start'));

		$phrases = array('usercp');
		$this->registry->loadphrases($phrases);

		$this->registry->no_guest();

		$this->registry->loadmodel('usercp', 'usercp', $id);

		$this->registry->construct_navbits(array('' => 'User Control Panel'));

		$this->registry->view->loadview('usercp', 'usercp', 'User Control Panel'); // PRINT PAGE
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
