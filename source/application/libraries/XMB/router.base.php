<?php

class Router
{
	/**
	 * Registry
	 * @var unknown_type
	 */
	private $registry;

	/**
	 * Path to controller
	 * @var unknown_type
	 */
	private $path;

	/**
	 * Controller
	 * @var unknown_type
	 */
	public $controller = '';

	/**
	 * Action
	 * @var unknown_type
	 */
	public $action = '';

	public function __construct($registry)
	{
		if (!defined('IN_CODE')) {
			exit('Not allowed to call this file directly');
		}

		$this->registry = $registry;

	}

	/**
	 * Sets the path to the controllers
	 *
	 * @param $path Path to controller
	 */
	public function set_path($path)
	{
		if (!is_dir($path)) {
			throw new Exception("Invalid controller path defined: " . $path . "");
		}

		$this->path = $path;
	}

	/**
	 * Sets the controller and action.
	 */
	private function findController()
	{
		if (!isset($_REQUEST['control']))
			$_REQUEST['control'] = 'index';
		if (!isset($_REQUEST['action']))
			$_REQUEST['action'] = 'index';
		if (!isset($_REQUEST['id']))
			$_REQUEST['id'] = '';

		$this->registry->cleaner->clean('r', 'control', 'TYPE_STR');
		$this->registry->cleaner->clean('r', 'action', 'TYPE_STR');
		$this->registry->cleaner->clean('r', 'id', 'TYPE_STR');

		$this->controller = (!isset($this->registry->cleaner->cleaned['control']) ? '' :
			$this->registry->cleaner->cleaned['control']);
		$this->action = (!isset($this->registry->cleaner->cleaned['action']) ? '' : $this->
			registry->cleaner->cleaned['action']);
		$this->id = (!isset($this->registry->cleaner->cleaned['id']) ? '' : $this->
			registry->cleaner->cleaned['id']);

		$this->id = str_replace('-', ' ', $this->id);

		if (empty($this->controller)) {
			$this->controller = 'index';
		}

		if (empty($this->action)) {
			$this->action = 'index';
		}

		if ($this->controller != 'login' && $this->registry->options['site_online'] == 0 &&
			!in_array($this->registry->user['uid'], explode(',', $this->registry->config['superadmins']))) {
			$this->controller = 'misc';
			$this->action = 'offline';
		}
	}

	/**
	 * Loads the controller and calls the action.
	 */
	public function load()
	{
		$this->findController();

		$loadfile = $this->path . '/' . $this->controller . '.controller.php';

		if (!is_readable($loadfile)) {
			$loadfile = $this->path . '/index.controller.php';
			$this->controller = 'index';
		}

		// TODO: Implement
		/*
		if (in_array($this->controller, $this->registry['plugins']['controllers']) && in_array($this->action, $this->registry['plugins']['actions'])) {
		$loadfile = X_PATH . '/application/plugins/....';
		}
		*/

		require ($loadfile);

		$class = $this->controller . 'Controller';
		$controller = new $class($this->registry);

		if (!is_callable(array($controller, $this->action))) {
			$this->action = 'index';
		}

		$action = $this->action;

		$this->registry->controller = $this->controller;
		$this->registry->action = $this->action;
		$this->registry->id = $this->id;

		/** Load Hooks **/
		$this->registry->loadhooks();

		if (isset($this->id)) {
			$controller->$action($this->id);
		} else {
			$controller->$action();
		}
	}
}

?>