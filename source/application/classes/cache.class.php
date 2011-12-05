<?php

/**
 * XMB Cache Class
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Cache
{
	private $registry;

	/**
	 * Holds cached data
	 * 
	 * @var array
	 */
	public $cache = array();

	public function __construct($registry)
	{
		$this->registry = $registry;
		

		$cachequery = $this->registry->db->query("
			SELECT * FROM " . X_PREFIX . "cache
		");

		foreach ($cachequery as $cachedata)
		{
			$this->cache[$cachedata['cacheid']] = $cachedata['data'];
		}
	}

	/**
	 * Rebuilds a selected cache item
	 * 
	 * @param string $cacheid
	 * @param string $data
	 */
	public function rebuild_cache($cacheid, $data)
	{
		$data = serialize($data);

		$this->registry->db->beginTransaction();
		
		$cachedelete = $this->registry->db->prepare("
			DELETE FROM " . X_PREFIX . "cache
			WHERE cacheid = ?
		");
		$cachedelete->execute(array($cacheid));
		
		$cacheupdate = $this->registry->db->prepare("
			INSERT INTO " . X_PREFIX . "cache (cacheid, data, dateline)
			VALUES(?, ?, ?)
		");

		$cacheupdate->execute(array($cacheid, $data, X_TIME));

		$this->registry->db->commit();
	}

	/**
	 * Fetches loaded cache data. Returns false if cache data is missing.
	 * 
	 * @param string $cacheid
	 */
	public function get_cache($cacheid)
	{
		if (isset($this->cache[$cacheid]))
		{
			return unserialize($this->cache[$cacheid]);
		} else
		{
			return false;
		}
	}
}