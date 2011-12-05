<?php

class Admin_Registry
{
	public $registry;

	private $login = 0;

	public function __construct($registry)
	{
		$this->registry = $registry;

		$timeout = X_TIME - (60 * 15);

		$session = $this->registry->db->query("
			SELECT * FROM " . X_PREFIX . "session WHERE sesstype = 'admin'
			&& userid = '" . $this->registry->user['uid'] . "'
			&& lastactive > $timeout
		");

		if ($this->registry->db->num_rows($session) > 0)
		{
			// continue
		} else
		{
			$this->login = 1;
		}
	}

	public function require_login()
	{
		return $this->login;
	}
}

?>