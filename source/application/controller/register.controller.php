<?php

/**
 * XMB Registration Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class registerController extends Controller
{
	private $method;

	public function index($id = '')
	{
		$this->register();
	}

	public function register($id = '')
	{
		$this->method = 'register';

		if ($this->registry->fetch_hook('register_start') != false)
			eval($this->registry->fetch_hook('register_start'));

		$phrases = array('register');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('register', 'register', $id);

		$this->registry->construct_navbits(array('' => 'Register'));

		$this->registry->view->loadview('register', 'register', 'Register'); // PRINT PAGE
	}

	public function doregister($id = '')
	{
		$this->registry->loadmodel('register', 'doregister', $id);
	}

	public function complete($id = '')
	{
		if ($this->registry->fetch_hook('register_complete') != false)
			eval($this->registry->fetch_hook('register_complete'));

		$phrases = array('register');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('register', 'complete', $id);

		$this->registry->construct_navbits(array('' => 'Registration Complete'));

		$this->registry->view->loadview('register_complete', 'register',
			'Registration Complete'); // PRINT PAGE
	}

}

?>