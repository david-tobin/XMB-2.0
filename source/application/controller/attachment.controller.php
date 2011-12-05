<?php

/**
 * Attachment Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class attachmentController extends Controller
{
	private $method = '';

	public function index($id = '')
	{
		$this->myattachments();
	}

	public function upload($id = '')
	{
		define('NO_GUEST', true);

		if ($this->registry->fetch_hook('attachments_upload') != false)
			eval($this->registry->fetch_hook('attachments_upload'));

		$phrases = array();
		$this->registry->loadphrases($phrases);

		$this->registry->construct_urls(array('form' => 'attachment/doupload'));

		$this->registry->loadmodel('attachment', 'upload', $id);

		$this->registry->construct_navbits(array('/usercp/index/' =>
			'User Control Panel', '/attachment/myattachments/' => 'Attachment Manager', '' =>
			'New Attachment'));

		$this->registry->view->loadview('usercp', 'usercp', 'Upload Attachment'); // PRINT PAGE
	}

	public function doupload($id = '')
	{
		$this->registry->loadmodel('attachment', 'doupload', $id);
	}

	public function myattachments($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'myattachments';

		if ($this->registry->fetch_hook('myattachments_start') != false)
			eval($this->registry->fetch_hook('myattachments_start'));

		$phrases = array();
		$this->registry->loadphrases($phrases);

		$this->registry->loadmodel('attachment', 'myattachments', $id);

		$this->registry->construct_navbits(array('/usercp/index/' =>
			'User Control Panel', '' => 'Attachment Manager'));

		$this->registry->view->loadview('usercp', 'usercp', 'My Attachments'); // PRINT PAGE

	}

	public function image($id = '')
	{
		$this->method = 'image';

		$this->registry->loadmodel('attachment', 'image', $id);
	}

	public function download($id = '')
	{
		define('NO_GUEST', true);

		$this->method = 'download';

		$this->registry->loadmodel('attachment', 'download', $id);
	}
}

?>