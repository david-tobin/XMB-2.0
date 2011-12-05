<?php

/**
 * Misc Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class miscController extends Controller
{
	private $method;

	public function index($id = '')
	{
		// Do Nothing
	}

	public function offline($id = '')
	{
		$this->method = 'offline';

		$phrases = array('');
		$this->registry->loadphrases($phrases);

		$this->registry->view->loadheader('Offline');
		$this->registry->loadmodel('misc', 'offline', $id);
		$this->registry->view->loadfooter();

		$this->registry->view->loadview('offline', 'misc'); // PRINT PAGE
	}

	public function error($id = '')
	{
		$this->method = 'error';

		$phrases = array();
		$this->registry->loadphrases();

		$this->registry->loadmodel('misc', 'error', $id);

		$this->registry->construct_navbits(array('' => 'Error'));

		$this->registry->view->loadview('error', 'misc', 'Error'); // PRINT PAGE
	}


}

?>