<?php

/**
 * Index Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class indexController extends Controller
{
	/**
	 *
	 * @var unknown_type
	 */
	private $method = '';

	protected $forumcache = array();

	protected $subforumcache = array();

	protected $currentforums = array();
	/**
	 *
	 *
	 */
	public function index($id = '')
	{
		$this->home();
	}

	public function stream($id = '')
	{
		if ($this->registry->fetch_hook('index_start') != false)
			eval($this->registry->fetch_hook('index_start'));

		$phrases = array('index');
		$this->registry->loadphrases($phrases);

		$this->registry->construct_navbits(array());

		$this->registry->loadmodel('index', 'stream', $id);
        $this->registry->loadmodel('index', 'set_statistics', $id);
        
		$this->registry->view->loadview('stream', 'index', 'Live Stream'); // PRINT PAGE
	}

	public function home($id = '') {
		if ($this->registry->fetch_hook('index_start') != false)
			eval($this->registry->fetch_hook('index_start'));

		$phrases = array('index');
		$this->registry->loadphrases($phrases);

		$this->registry->construct_navbits(array('' => 'Home'));

		$this->registry->loadmodel('index', 'home', $id);
        $this->registry->loadmodel('index', 'set_statistics', $id);
        
		$this->registry->view->loadview('index', 'index', 'Index'); // PRINT PAGE
	}

	public function reset_sidebar($id = '')
	{
		$reset = $this->registry->db->prepare("
		UPDATE " . X_PREFIX . "members
		SET sidebar = 1,
		sidebar_specific = ''
		WHERE username = :username
		");

		$reset->execute(array(':username' => $this->registry->user['username']));

		echo "Success";
	}

	public function clear_cache($id = '')
	{
		$query = $this->registry->db->query("
		DELETE FROM " . X_PREFIX . "cache
		");

		$query2 = $this->registry->db->query("
		UPDATE " . X_PREFIX . "cron
		SET nextrun = 0
		WHERE cronscript = 'stats'
		");
	}
	
	public function install($id = '') {
		echo 'We are in';
	}
}

?>