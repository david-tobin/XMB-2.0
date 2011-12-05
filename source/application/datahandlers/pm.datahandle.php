<?php

if (!defined('IN_CODE'))
{
	die('You cannot run this file directly');
}
/**
 * Handles PM Data Management
 * 
 * @author David "DavidT" Tobin
 * @copyright 2010 The XMB Group
 * @licence GPL 3.0
 * @package XMB 2.0
 */
class XMB_Datahandler_PM extends XMB_Datahandler
{
	private $fromuser = '';
	private $recipients = array();
	private $subject = '';
	private $message = '';

	private $errors = array();

	public function set_info($info, $value)
	{
		switch ($info)
		{
			case 'fromuser':
				$this->fromuser = $value;
				break;

			case 'subject':
				$this->subject = $value;
				break;

			case 'message':
				$this->message = $value;
				break;

			case 'recipient':
				if (is_array($value))
				{
					foreach ($value as $val)
					{
						$this->recipients[] = $val;
					}
				} else
				{
					$this->recipients[] = $val;
				}
				break;
		}
	}

	public function build()
	{
		$this->perform_checks();

		if (empty($this->errors))
		{
			$this->insert_pm();
		} else
		{
			foreach ($this->errors as $key => $value)
			{
				$this->registry->xmberror($key);
				exit;
			}
		}
	}

	public function perform_checks()
	{
		if (empty($this->fromuser) || empty($this->message) || empty($this->recipients) ||
			empty($this->subject))
		{
			$this->errors[] = "error_pm_missing_info";
		}

		if (count($this->recipients) == 0)
		{
			$this->errors[] = 'error_pm_not_recipients';
		}
	}

	public function insert_pm()
	{
		$total = count($this->recipients);
		if ($total > $this->registry->options['pm_max_cutoff'])
			$total = $this->registry->options['pm_max_cutoff'];

		for ($i = 0; $i < $total; $i++)
		{
			$pm = $this->registry->db->prepare("
				INSERT INTO " . X_PREFIX .
				"messages (touser, fromuser, type, subject, message, readstatus, dateline)
				VALUES (:touser, :fromuser, '1', :subject, :message, '0', '" . X_TIME . "')
			");

			$pm->execute(array(':touser' => $this->recipients[$i], ':fromuser' => $this->
				fromuser, ':subject' => $this->subject, ':message' => $this->message));

			$userupdate = $this->registry->db->prepare("
				UPDATE " . X_PREFIX . "members
				SET unreadpm = unreadpm+1,
				pmtotal = pmtotal+1
				WHERE username = :username
			");

			$userupdate->execute(array(':username' => $this->recipients[$i]));
		}

		return true;
	}
}

?>