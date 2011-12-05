<?php

class usercpModel extends Model
{

	public function index($id)
	{
		$this->usercp();
	}

	public function usercp($id)
	{
		$this->registry->usercp_method = 'usercp_home';

		$content = $this->registry->view->loadtovar($this->registry->usercp_method, $this->
			registry->controller);

		$this->registry->view->setvar('content', $content);
	}

	public function site($id)
	{
		$this->registry->usercp_method = 'usercp_site_options';

		$content = $this->registry->view->loadtovar($this->registry->usercp_method, $this->
			registry->controller);

		$this->registry->view->setvar('content', $content);
	}
}

?>