<?php

/**
 * XMB Permissions Class
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Permission_Manager
{
	/**
	 *
	 * @var unknown_type
	 */
	private $registry;

	/**
	 * Holds permissions
	 * @var array
	 */
	public $perms = array();

	/**
	 * Load Registry
	 * @param $registry
	 * @return none
	 */
	public function __construct($registry)
	{
		$this->registry = $registry;
	}

	public function __get($perm)
	{
		if (isset($this->perms[$perm]))
		{
			$this->check($perm);
		} else
		{
			return false;
		}
	}

	/**
	 * Loads User/Guest Permissions
	 * @return none
	 */
	public function load_permissions()
	{
		if ($this->registry->cache->get_cache('usergroups') == false)
		{
			$ugroups = $this->registry->db->query("
				SELECT * FROM " . X_PREFIX . "usergroups
			");

			if ($ugroups->rowCount() > 0)
			{
				$usergroup = array();
				foreach ($ugroups as $usergroups)
				{
					$usergroup[$usergroups['usergroupid']] = $usergroups;
				}

				$this->registry->cache->rebuild_cache('usergroups', $usergroup);
			}
		} else
		{
			$usergroup = $this->registry->cache->get_cache('usergroups');
		}
		$this->perms = unserialize($usergroup[$this->registry->user['usergroupid']]['permissions']);

		return true;
	}

	/**
	 * Checks a defined permission.
	 * @param $key The name of the permission
	 * @param $value The value of the permission
	 * @return unknown_type
	 */
	public function check($key, $value = 1)
	{
		if (@$this->perms[$key] == $value)
		{
			return 1;
		} else
		{
			return 0;
		}
	}

	/**
	 * Checks if a user is an administrator.
	 * @return 1 On Success
	 */
	public function is_admin()
	{
		if ($this->perms['is_admin'] == 1)
		{
			return 1;
		} else
		{
			return 0;
		}
	}

	/**
	 * Checks if a user is a moderator.
	 * @return 1 On Sucess
	 */
	public function is_mod()
	{
		if ($this->perms['is_mod'] == 1)
		{
			return 1;
		} else
			if ($this->is_admin() == 1)
			{
				return 1;
			} else
			{
				return 0;
			}
	}
}
