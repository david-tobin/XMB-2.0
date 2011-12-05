<?php

class loginModel extends Model
{

	public function index($id)
	{
		// Do Nothing
	}

	public function login($id = '')
	{
		$this->registry->redirect('/login/login/1/');
	}

	public function processlogin($logininfos = '')
	{
		$loginfo = explode('|', $logininfos);

		$username = strtolower($loginfo['0']);
		$password = $loginfo['1'];
		$remember = $loginfo['2'];

		include (X_PATH . '/application/datahandlers/login.datahandle.php');

		$login = new XMB_Datahandler_Login($this->registry);

		$login->set_info('username', $username);
		$login->set_info('password', $password);
		$login->set_info('remember', $remember);
		$login->set_info('type', 'user');

		if ($login->build() == 1)
		{
			$this->registry->redirect('/');
		} else
		{
			$this->login();
		}
	}
}

?>