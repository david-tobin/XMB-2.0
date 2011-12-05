<?php

/**
 * FAQ Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class faqController extends Controller
{
	private $method;

	public function index($id = '')
	{
		$phrases = array('faq');
		$this->registry->loadphrases($phrases);

		if ($this->registry->fetch_hook('faq_index_start') != false)
			eval($this->registry->fetch_hook('faq_index_start'));

		$this->registry->loadmodel('faq', 'index', $id);

		$this->registry->construct_navbits(array('/faq/' => ''));

		$this->registry->view->loadview('home', 'faq', 'FAQ\'s'); // PRINT PAGE
	}

	public function view($id = '')
	{

	}
}

?>