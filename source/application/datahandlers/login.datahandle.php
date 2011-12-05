<?php

if (!defined('IN_CODE'))
{
	die('You cannot run this file directly');
}
/**
 * Handles Login Data Management
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Datahandler_Login extends XMB_Datahandler
{
	private $username = '';
	private $password = '';
	private $remember = '';
	private $type = '';

	private $logininfo = array();

	private $errors = array();

	/**
	 * XMB_Datahandler_Login::set_info()
	 * 
	 * @param mixed $info
	 * @param mixed $value
	 * @return
	 */
	public function set_info($info, $value)
	{
		switch ($info)
		{
			case 'username':
				$this->username = strtolower($value);
				break;

			case 'password':
				$this->password = $value;
				break;

			case 'remember':
				$this->remember = $value;
				break;

			case 'type':
				$this->type = $value;
		}
	}

	/**
	 * XMB_Datahandler_Login::build()
	 * 
	 * @return
	 */
	public function build()
	{
		$this->perform_checks();

		if (empty($this->errors))
		{

			if ($this->remember == 'on' && $this->type == 'user')
			{
				$this->registry->setcookie('xmbuserid', $this->logininfo['uid'], X_TIME + (60 *
					60 * 24 * 30));
				$this->registry->setcookie('xmbpasshash', $this->logininfo['password'], X_TIME +
					(60 * 60 * 24 * 30));
			} else
				if ($this->type == 'user' && $this->remember == 'off')
				{
					$this->registry->setcookie('xmbuserid', $this->logininfo['uid'], X_TIME + (60 *
						60));
					$this->registry->setcookie('xmbpasshash', $this->logininfo['password'], X_TIME +
						(60 * 60));
				} else
					if ($this->type == 'admin')
					{
						$this->registry->setcookie('xmbadminuserid', $this->logininfo['uid'], X_TIME + (60 *
							60));
					}

			$this->registry->createsession($this->logininfo, $this->type);

			return 1;
		} else
		{
			if (!empty($this->errors))
			{
				if ($this->type == 'user')
				{
					$this->registry->redirect('/login/1');
				} else
				{
					$this->registry->redirect('/admin/login/');
				}
			}
		}
	}

	/**
	 * XMB_Datahandler_Login::perform_checks()
	 * 
	 * @return
	 */
	public function perform_checks()
	{
		if (empty($this->username) || empty($this->password))
		{
			$this->errors[] = "do_error";
		}

		$login = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "members
			WHERE LOWER(username) = :username
			|| email = :email
		");
		$login->execute(array(':username' => $this->username, ':email' => $this->
			username));
		$login = $login->fetch();

		$this->logininfo = $login;
		$this->logininfo['type'] = $this->type;

		if (empty($login['username']))
		{
			$this->errors[] = "user_no_exist";
		}

		if (isset($login['password']) && md5($this->password . $login['salt']) != $login['password'])
		{
			$this->errors[] = "incorrect_password";
		}

		if ($login['usergroupid'] != 1 && $this->type == 'admin')
		{
			$this->error[] = 'user_no_admin';
		}
	}
}

?>