<?php

if (!defined('IN_CODE'))
{
	die('You cannot run this file directly');
}

/**
 * XMB_Datahandler_User
 * 
 * @package XMB 2.0  
 * @author David "DavidT" Tobin
 * @copyright 2010 The XMB Group
 * @access public
 */
class XMB_Datahandler_User extends XMB_Datahandler
{
	private $username = '';
	private $password = '';
	private $email = '';
	private $usergroupid = 2; // DEFAULT -> Member

	private $errors = array();

	/**
	 * XMB_Datahandler_User::set_info()
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
				$this->username = $value;
				break;

			case 'password':
				$this->password = $value;
				break;

			case 'email':
				$this->email = $value;
				break;
		}
	}

	/**
	 * XMB_Datahandler_User::build()
	 * 
	 * @return
	 */
	public function build()
	{
		// Do checks
		$this->perform_checks();

		// Check for errors. If none then insert user.
		if (empty($this->errors))
		{
			$this->insert_user();
		} else
		{
			foreach ($this->errors as $key => $value)
			{
				$this->registry->xmberror($value);
				exit;
			}
		}

		return 1;
	}

	/**
	 * XMB_Datahandler_User::perform_checks()
	 * 
	 * @return
	 */
	public function perform_checks()
	{
		// Check for conflicting users
		$check = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "members
			WHERE username = :username
			|| email = :email
		");
		$check->execute(array(':username' => $this->username, ':email' => $this->email));
		$check = $check->fetch();

		if ($this->username == $check['username'])
		{
			$this->errors[] = "Username Already Taken!";
		}

		if ($this->email == $check['email'])
		{
			$this->errors[] = "Email Found In Database...";
		}

		if (strlen($this->username) < 3 || strlen($this->username) > 25)
		{
			$this->errors[] = "Invalid Username Length.";
		}
	}

	/**
	 * XMB_Datahandler_User::insert_user()
	 * 
	 * @return
	 */
	public function insert_user()
	{
		// Generate Salt
		$symbols = array('!', '$', '%', '^', '&', '*', '(', ')', '_', '-', '+', '=', '[',
			'{', '}', ']', '@', ';', '?', '>', '<');
		$letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
			'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$salt = array($symbols[rand(0, 20)], rand(0, 8), $letters[rand(0, 25)], $symbols[rand
			(0, 20)]);
		$salt = shuffle($salt);
		$salt = $salt[0] . $salt[1] . $salt[2] . $salt[3];

		// Insert Member
		$insertuser = $this->registry->db->prepare("
			INSERT INTO " . X_PREFIX .
			"members (uid, securitytoken, bio, sig, username, password, salt, email, regdate, usergroupid)
			VALUES (1, 'abcd', 'abc', 'sig', :username, :password, :salt, :email, '" . X_TIME . "', :usergroupid)
		");

		$insertuser->execute(array(':username' => $this->username, ':password' => md5($this->
			password . $salt), ':salt' => $salt, ':email' => $this->email, ':usergroupid' =>
			$this->usergroupid));
		return true;
	}
}

?>