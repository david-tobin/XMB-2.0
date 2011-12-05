<?php

/**
 * Login Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class loginController extends Controller
{
	private $method = '';

	public function index($id = '')
	{
		$this->registry->cleaner->clean('p', 'xmbusername', 'TYPE_STR');
		$this->registry->cleaner->clean('p', 'xmbpassword', 'TYPE_STR');
		$this->registry->cleaner->clean('p', 'rememberme', 'TYPE_STR');

		$this->registry->csrf_protection();

		$username = (isset($this->registry->cleaner->cleaned['xmbusername']) ? $this->
			registry->cleaner->cleaned['xmbusername'] : false);
		$password = (isset($this->registry->cleaner->cleaned['xmbpassword']) ? $this->
			registry->cleaner->cleaned['xmbpassword'] : false);
		$rememberme = (isset($this->registry->cleaner->cleaned['rememberme']) ? $this->
			registry->cleaner->cleaned['rememberme'] : false);

		if ($username != false && $password != false)
		{
			$this->processlogin($username, $password, $rememberme);
		} else
		{
			$this->login();
		}
	}

	public function login($id = '', $fail = false)
	{
		$this->method = 'login';
		$id = intval($id);

		if ($this->registry->fetch_hook('login_start') != false)
			eval($this->registry->fetch_hook('login_start'));

		$phrases = array('login');
		$this->registry->loadphrases($phrases);

		$this->registry->construct_navbits(array('' => 'Login'));

		if ($id == 1)
		{
			$failmessage = 'Incorrect username or password. Please try again.';
		} else
		{
			$failmessage = '';
		}

		$this->registry->view->setvar('fail', $failmessage);

		$this->registry->view->loadview('login', 'login', 'Login'); // PRINT PAGE
	}

	public function logout($id = '')
	{
		$this->method = 'logout';

		$this->registry->setcookie('xmbuserid', '0', X_TIME - 1);
		$this->registry->setcookie('xmbpasshash', '0', X_TIME - 1);

		$this->registry->db->query("
		DELETE FROM " . X_PREFIX . "session WHERE username = '" . $this->registry->
			user['username'] . "'
		");

		$this->registry->redirect('/');
	}

	private function processlogin($username, $password, $remember)
	{
		$tomodel = $username . "|" . $password . "|" . $remember;

		$this->registry->loadmodel('login', 'processlogin', $tomodel);
	}
}

?>