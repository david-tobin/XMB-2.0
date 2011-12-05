<?php

/**
 * XMB Database Class
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 * 
 * NOTE: THIS FILE IS NOW CONSIDERED REDUNDANT
 */
class XMB_Database
{
	private $registry;

	private $required = array();

	public $sql = '';

	public $db = '';

	public $querycount = 0;

	public $cachecount = 0;

	public $querys = array();

	public function __construct($registry, $database)
	{
		$this->registry = &$registry;
		

		$this->connect($database);
	}

	public function connect($required)
	{
		$this->required = &$required;

		$connect = mysql_connect($this->required['server'], $this->required['user'], $this->
			required['pass']);

		if (!$connect)
		{
			$this->registry->trigger_error('Could not connect to MySQL database: ' .
				mysql_error());

		}

		mysql_select_db($this->required['database'], $connect);

		$this->db = $this->required['database'];
	}

	public function escape_string($escape)
	{
		return mysql_escape_string($escape);
	}

	public function escape_string_like($escape)
	{
		return mysql_real_escape_string($escape);
	}

	public function query_one($sql)
	{
		$this->sql = $sql;

		$query = $this->query($this->sql);

		$giveback = $this->fetch_array($query, MYSQL_ASSOC);

		$this->free_result($query);

		return $giveback;
	}

	public function query($sql, $cache = 1, $cacheid = '')
	{
		$this->sql = &$sql;
		$this->querys[] = $sql;

		if ($cache == 3 && $this->registry->apc == true && apc_fetch($cacheid) == true)
		{
			$this->cachecount++;

			return apc_fetch($cacheid);
		} else
			if ($cache == 3 && $this->registry->apc == true && apc_fetch($cacheid) == false)
			{
				$query = mysql_query($this->sql);

				apc_store($cacheid, $query, (60 * 60 * 3));
				$this->querycount++;
			} else
			{
				$query = mysql_query($this->sql);
				$this->querycount++;
			}

			if ($query)
			{
				return $query;
			} else
			{
				$this->registry->trigger_error('Invalid SQL ' . mysql_errno() . $this->registry->
					action . ': ' . mysql_error() . '<br /><br /> SQL: <br />' . $this->sql);
			}
	}

	public function fetch_array($fetch, $cacheid = '')
	{
		$give = mysql_fetch_array($fetch, MYSQL_ASSOC);

		return $give;
	}

	public function free_result($free)
	{
		if ($free)
		{
			mysql_free_result($free);
		}
	}

	public function num_rows($rows)
	{
		$numrows = mysql_num_rows($rows);

		return $numrows;
	}

	public function lastid()
	{
		return mysql_insert_id();
	}
}

?>