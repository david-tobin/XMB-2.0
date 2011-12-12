<?php

class adminController extends Controller
{
	private $login = true;

	public function __construct($registry)
	{
		parent::__construct($registry);
		define('IN_ADMIN', true);

		$loggedin = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "session
			WHERE sesstype = 'admin' && username = :username
		");

		$loggedin->execute(array(':username' => $this->registry->user['username']));
		$loggedin = $loggedin->fetch();
		
		if (!empty($loggedin['userid']))
		{
			$this->login = false;
		}
	}

	public function index($id = '')
	{
		if ($this->login == true)
		{
			if ($id) {
				$this->$id();
			} else {
				$this->home();
			}
		} else
		{
			$this->login();
		}
	}

	public function login($id = '')
	{
		$phrases = array();
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('admin', 'login', $id);

		$this->registry->view->loadview('login', 'admin', 'Login'); // PRINT PAGE
	}

	public function processlogin($id = '')
	{
		$this->registry->loadmodel('admin', 'processlogin');
	}

	public function home($id = '')
	{
		$phrases = array();
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('admin', 'home', $id);

		$leftbox = $this->registry->view->loadtovar('leftbox_system', 'admin');
		$this->registry->view->setvar('leftbox', $leftbox);
		$this->registry->view->loadview('home', 'admin', 'Home'); // PRINT PAGE
	}

	public function credits($id = '')
	{
		$phrases = array();
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('admin', 'credits', $id);

		$leftbox = $this->registry->view->loadtovar('leftbox_system', 'admin');
		$this->registry->view->setvar('leftbox', $leftbox);
		$this->registry->view->loadview('credits', 'admin', 'Credits'); // PRINT PAGE
	}

	public function about($id = '')
	{
		$phrases = array();
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('admin', 'about', $id);

		$leftbox = $this->registry->view->loadtovar('leftbox_system', 'admin');
		$this->registry->view->setvar('leftbox', $leftbox);
		$this->registry->view->loadview('about', 'admin', 'About XMB'); // PRINT PAGE
	}

	public function settings($id = '')
	{
		switch ($id)
		{
			case 'home':
				$template = 'settings_landing';
				$model = 'settings_landing';
				break;

			case 'view':
				$template = 'settings_view';
				$model = 'settings_view';
				break;

			case 'update':
				$template = 'settings_view';
				$model = 'settings_update';
				break;

			default:
				$template = 'settings_landing';
				$model = 'settings_landing';
				break;
		}

		$phrases = array();
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('admin', $model, $id);

		$leftbox = $this->registry->view->loadtovar('leftbox_system', 'admin');
		$this->registry->view->setvar('leftbox', $leftbox);
		$this->registry->view->loadview($template, 'admin', 'Settings'); // PRINT PAGE
	}

	public function cache($id = '')
	{
		switch ($id)
		{
			case 'clear':
				$template = 'cache_clear';
				$model = 'cache_clear';
				break;

			case 'view':
				$template = 'cache_view';
				$model = 'cache_view';
				break;

			case 'rebuild':
				$template = 'cache_rebuild';
				$model = 'cache_rebuild';
				break;

			case 'inspect':
				$template = 'cache_inspect';
				$model = 'cache_inspect';
				break;

			default:
				$template = 'cache_view';
				$model = 'cache_view';
				break;
		}

		$this->registry->loadmodel('admin', $model, $id);

		$leftbox = $this->registry->view->loadtovar('leftbox_system', 'admin');
		$this->registry->view->setvar('leftbox', $leftbox);

		$this->registry->view->loadview($template, 'admin', 'Cache'); // PRINT PAGE
	}
}

?>