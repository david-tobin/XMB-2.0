<?php

/**
 * Messages Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class messagesController extends Controller
{
	/**
	 *
	 * @var unknown_type
	 */
	private $method = '';

	/**
	 *
	 * @param $id
	 */
	public function index($id = '')
	{
		$this->inbox();
	}

	/**
	 *
	 * @param unknown_type $id
	 */
	public function inbox($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'inbox';

		if ($this->registry->fetch_hook('messages_start') != false)
			eval($this->registry->fetch_hook('messages_start'));

		$phrases = array('messages');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('messages', 'inbox', $id);

		$this->registry->construct_navbits(array('/messages/' => 'Private Messages', '' =>
			'Inbox'));

		$this->registry->view->setvar('pmtitle', 'Inbox');
		$this->registry->view->loadview('view', 'messages', 'Private Messages'); // PRINT PAGE
	}

	public function outbox($id = '')
	{
		define('NO_GUEST', true);

		$id = intval($id);

		$this->method = 'outbox';

		if ($this->registry->fetch_hook('messages_start') != false)
			eval($this->registry->fetch_hook('messages_start'));

		$phrases = array('messages');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('messages', 'outbox', $id);

		$this->registry->construct_navbits(array('/messages/' => 'Private Messages', '' =>
			'Outbox'));

		$this->registry->view->setvar('pmtitle', 'Outbox');
		$this->registry->view->loadview('view', 'messages', 'Private Messages'); // PRINT PAGE
	}

	public function view($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'messages';
		$id = intval($id);

		if ($this->registry->fetch_hook('messages_start') != false)
			eval($this->registry->fetch_hook('messages_start'));

		$phrases = array('messages');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('messages', 'view', $id);

		$this->registry->construct_navbits(array('/messages/' => 'Private Messages', '' =>
			'View Message'));

		$this->registry->view->setvar('pmtitle', 'View Message');
		$this->registry->view->loadview('view', 'messages', 'Private Messages'); // PRINT PAGE
	}

	public function send($id = '')
	{
		define('NO_GUEST', true);

		if (empty($id))	$id = '';
            
		$this->registry->view->setvar('recipients', $id);

		if ($this->registry->fetch_hook('messages_send_start') != false)
			eval($this->registry->fetch_hook('messages_start'));

		$phrases = array('messages');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('messages', 'send', $id);

		$this->registry->construct_navbits(array('/messages/' => 'Private Messages', '' =>
			'Send New Message'));

		$this->registry->view->setvar('pmtitle', 'Send New Message');
		$this->registry->view->loadview('view', 'messages', 'Private Messages'); // PRINT PAGE
	}

	public function create($id = '')
	{
		define('NO_GUEST', true);

		$this->registry->loadmodel('messages', 'create', $id);

		$this->registry->redirect('/messages/');
	}
}
