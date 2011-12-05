<?php
class Module {
	private static $registry;
	
	private static $modules;
	
	public static function register($moduleid) {
		if (isset(self::$modules[$moduleid]) && self::$modules[$moduleid]['moduleactive'] == 1) {
			require(X_PATH . '/application/modules/' . $moduleid . '.module.php');
			
			$classid = $moduleid . 'Module';
			return new $classid(self::$registry);
		} else {
			return false;
		}
	}
	
	public static function set_registry($registry) {
		self::$registry =& $registry;
		self::$modules = self::$registry->cache->get_cache('modules');
	}
}