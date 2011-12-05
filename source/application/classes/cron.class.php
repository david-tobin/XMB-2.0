<?php

/**
 * BBCode Class
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Cron
{
	private $registry;

	private $cronjobs;

	public function __construct($registry)
	{
		$this->registry = &$registry;

		$cronquery = $this->registry->db->query("
				SELECT * FROM " . X_PREFIX . "cron
				WHERE cronactive = true
				ORDER BY nextrun ASC
			");

		$cronarray = array();

		if ($cronquery->rowCount() > 0)
		{
			foreach ($cronquery as $cron)
			{
				$cronarray[$cron['cronid']] = $cron;
			}
		}

		$this->cronjobs = $cronarray;
	}

	public function check_cron()
	{
		if (empty($this->cronjobs))
			return false;

		foreach ($this->cronjobs as $cronid => $cron)
		{
			if ($cron['nextrun'] <= X_TIME)
			{
				$this->run_cron($cronid);
			}
		}
	}

	private function run_cron($cronid)
	{
		if (file_exists(X_PATH . '/application/crons/' . $this->cronjobs[$cronid]['cronscript'] .
			'.cron.php'))
		{
			include (X_PATH . '/application/crons/' . $this->cronjobs[$cronid]['cronscript'] .
				'.cron.php');

			$nextrun = intval(X_TIME + $this->cronjobs[$cronid]['runevery']);

			$this->registry->db->query("
				UPDATE " . X_PREFIX . "cron
				SET nextrun = '" . $nextrun . "'
				WHERE cronid = '" . $cronid . "'
			");

			return true;
		} else
		{
			return false;
		}
	}

	public function force_run($cronid)
	{
		@$this->run_cron($cronid);
	}
}

?>