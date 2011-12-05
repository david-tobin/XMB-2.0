<?php

/**
 * Forum Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class forumController extends Controller
{
	private $method;

	public function index($id = '')
	{
		// Not neccessary
	}

	public function display($id = '')
	{
		$this->method = 'index';

		$phrases = array('forum');
		$this->registry->loadphrases($phrases);

		if ($this->registry->fetch_hook('forum_display_start') != false)
			eval($this->registry->fetch_hook('forum_display_start'));

		$this->registry->loadmodel('forum', 'display', $id);
		$this->registry->modeltocontroller['navname'];

		$this->registry->construct_navbits(array('/forum/display/' . $id => $this->
			registry->modeltocontroller['navname']));

		$this->registry->view->loadview('display', 'forum', $this->registry->
			modeltocontroller['navname']); // PRINT PAGE
	}
}

?>