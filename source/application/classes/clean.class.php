<?php

/**
 * XMB Data Cleaner
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Cleaner
{
	private $registry;

	/**
	 * Array of cleaned data
	 *
	 * @var array
	 */
	public $cleaned = array();

	public function __construct($registry)
	{
		$this->registry = &$registry;
		
	}

	/**
	 * Returns a cleaned item
	 * Usage: $this->registry->cleaner->var
	 *
	 * @param string $varname
	 */
	public function __get($varname)
	{
		if (isset($this->cleaned[$varname]))
		{
			return $this->cleaned[$varname];
		} else
		{
			return false;
		}
	}

	public function fetch($var)
	{
		if (isset($this->cleaned[$var]))
		{
			return $this->cleaned[$var];
		}
	}

	/**
	 * Cleans inputed data.
	 *
	 * @param string $method (p/g/c/f/r)
	 * @param string $varname
	 * @param string $type TYPE_(STR|INT|BOOL|ARRAY)
	 */
	public function clean($method = 'p', $varname, $type = 'TYPE_STR')
	{
		// NEEDS FULL REVISION
		switch ($method)
		{
			case 'c':
				$input = &$_COOKIE;
				break;

			case 'p':
				$input = &$_POST;
				break;

			case 'g':
				$input = &$_GET;
				break;

			case 'r':
				$input = &$_REQUEST;
				break;

			case 'f':
				$input = &$_FILES;
				break;

			default:
				$input = &$_POST;
		}

		if (isset($input[$varname]) && !is_array($input[$varname]))
			$input[$varname] = strip_tags(htmlspecialchars($input[$varname]));

		// NEEDS FULL REVISION
		switch ($type)
		{
			case 'TYPE_STR':
				if (isset($input[$varname]))
				{
					$this->cleaned[$varname] = strval($input[$varname]);
				}
				break;

			case 'TYPE_INT':
				if (isset($input[$varname]))
				{
					$this->cleaned[$varname] = intval($input[$varname]);
				}
				break;

			case 'TYPE_BOOL':
				if (isset($input[$varname]))
				{
					$input[$varname] = intval($input[$varname]);

					is_bool($input[$varname]) ? $this->cleaned[$varname] = $input[$varname] : $this->
						cleaned[$varname] = 0;
				}
				break;

			case 'TYPE_ARRAY':
				if (isset($input[$varname]) && is_array($input[$varname]))
				{
					$this->cleaned[$varname] = array();

					foreach ($input[$varname] as $key => $value)
					{
						if (is_int($value) || is_bool($value))
						{
							$this->cleaned[$varname][$key] = intval($value);
						} else
							if (is_string($value))
							{
								$this->cleaned[$varname][$key] = $value;
							} else
								if (is_array($value))
								{ //@TODO Requires Revision
									$this->array_input($value);
								}
					}
				}
				break;

			default:
				if (isset($input[$varname]) && is_string($input[$varname]))
				{
					$this->cleaned[$varname] = $input[$varname];
				}
		}

		if (!isset($input[$varname]))
		{ // Prevents PHP Errors
			$this->cleaned[$varname] = '';
		}
	}

	// @TODO Finish Function. Currently Not Functional!
	public function array_input($array)
	{
		$returnarray = array();

		foreach ($array as $key => $value)
		{
			if (is_string($value))
			{
				$returnarray[$key] = $value;
			}

			if (is_int($value))
			{
				$returnarray[$key] = intval($value);
			}

			if (is_array($value))
			{
				$this->array_input();
			}
		}

		return $returnarray;
	}
}

?>