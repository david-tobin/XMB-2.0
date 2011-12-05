<?php

/**
 * Members Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class membersController extends Controller
{
	private $method;

	public function index($id = '')
	{
		$this->all($id);
	}

	public function all($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'all';

		if ($this->registry->fetch_hook('members_all_start') != false)
			eval($this->registry->fetch_hook('members_all_start'));

		$phrases = array('members');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('members', 'all', $id);

		$this->registry->construct_navbits(array('' => 'Member List'));

		$this->registry->view->loadview('all', 'members', "Member List"); // PRINT PAGE
	}

	public function profile($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'profile';

		if ($this->registry->fetch_hook('members_profile_start') != false)
			eval($this->registry->fetch_hook('members_profile_start'));

		$phrases = array('members');
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('members', 'profile', $id);

		$this->registry->construct_navbits(array('/members/list' => 'Profiles', '' => $id));

		$this->registry->view->loadview('members', 'members', "$id's Profile"); // PRINT PAGE
	}
}

?>