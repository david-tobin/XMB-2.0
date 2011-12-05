<?php

/**
 * Thread Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class threadController extends Controller
{
	private $method;

	public function index($id = '')
	{
		$this->view($id);
	}

	public function view($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'view';

		if ($this->registry->fetch_hook('thread_view_start') != false)
			eval($this->registry->fetch_hook('thread_view_start'));

		$phrases = array('thread');
		$this->registry->loadphrases($phrases);

		//$this->registry->controller = 'Forum';

		$this->registry->loadmodel('thread', 'view', $id);

		$this->registry->construct_navbits(array('/forum/display/' . $this->registry->
			modeltocontroller['fid'] => $this->registry->modeltocontroller['fname'], '' => $this->
			registry->modeltocontroller['title']));

		$this->registry->view->loadview('view', 'thread', $this->registry->
			modeltocontroller['title']);
	}

	public function newthread($id = '')
	{
		if ($this->registry->perm->can_newthread == 0)
			define('NO_ACCESS', true);

		$this->method = 'newthread';

		if ($this->registry->fetch_hook('thread_new_start') != false)
			eval($this->registry->fetch_hook('thread_new_start'));

		$phrases = array('thread');
		$this->registry->loadphrases($phrases);

		$this->registry->construct_navbits(array('' => 'New Thread'));

		$this->registry->loadmodel('thread', 'newthread', $id);
		$this->registry->view->loadview('newthread', 'thread', 'New Thread');
	}

	public function postthread($id = '')
	{
		if ($this->registry->perm->can_newthread == 0)
			define('NO_ACCESS', true);

		$this->method = 'postthread';

		$this->registry->csrf_protection();

		if ($this->registry->fetch_hook('thread_post_start') != false)
			eval($this->registry->fetch_hook('thread_post_start'));

		$this->registry->loadmodel('thread', 'postthread', $id);

	}

	public function reply($id = '')
	{
		if ($this->registry->perm->can_newthread == 0)
			define('NO_ACCESS', true);

		$id = intval($id);

		$this->method = 'newthread';

		if ($this->registry->fetch_hook('thread_reply_start') != false)
			eval($this->registry->fetch_hook('thread_new_start'));

		$phrases = array('thread');
		$this->registry->loadphrases($phrases);

		$this->registry->construct_navbits(array('' => 'Reply'));

		$this->registry->loadmodel('thread', 'reply', $id);
		$this->registry->view->loadview('thread_reply', 'thread', 'New Reply');
	}

	public function doreply($id = '')
	{
		if ($this->registry->perm->can_newthread == 0)
			define('NO_ACCESS', true);

		$this->method = 'doreply';

		$phrases = array('thread');
		$this->registry->loadphrases($phrases);

		$this->registry->csrf_protection();

		if ($this->registry->fetch_hook('thread_doreply_start') != false)
			eval($this->registry->fetch_hook('thread_doreply_start'));

		$this->registry->loadmodel('thread', 'doreply', $id);
	}
}
