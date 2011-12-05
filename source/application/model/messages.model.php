<?php

/**
 *
 * @author DavidT
 *
 */
class messagesModel extends Model
{
	/**
	 * (non-PHPdoc)
	 * @see app/application/Model#index($id)
	 */
	public function index($id)
	{
		$this->new($id);
	}

	/**
	 *
	 * @param $id
	 * @return unknown_type
	 */
	public function inbox($id)
	{

		$mymessages = $this->registry->db->prepare("
		SELECT * FROM " . X_PREFIX . "messages 
		WHERE touser = :to
		ORDER BY readstatus ASC, dateline DESC
		");
		$mymessages->execute(array(':to' => $this->registry->user['username']));

		$inbox = '';
		$incount = 0;

		require_once (X_PATH . '/application/classes/bbcode.class.php');

		$bbcode = new XMB_BBCode($this->registry);
		$bbcode->load_bbcode();

		if ($mymessages->rowCount() > 0)
		{
			foreach ($mymessages as $messages)
			{
				$messages['subject'] = ($messages['readstatus'] == 0) ?
					'<span style="font-weight: bold;">' . $messages['subject'] . '</span>' : $messages['subject'];
				$messages['sent'] = $this->registry->xmbtime('d-m-Y h:i a', $messages['dateline']);
				$messages['count'] = $incount;
				$messages['message'] = $bbcode->parse(substr($messages['message'], 0, 150) .
					'...');

				$this->registry->view->setvar('messages', $messages);

				$incount++;
				$inbox .= $this->registry->view->loadtovar('inbox', 'messages');
			}
		} else
		{
			$inbox = '<tr><td class="alt1"><span style="text-align: center; font-weight: bold;">No messages!</span></td></tr>';
		}

		if ($incount == 0)
			$inbox = '<tr><td class="alt1"><span style="text-align: center; font-weight: bold;">No messages!</span></td></tr>';
        
        $inbox .= '</table><div class="foot"></div>';
        
		$this->registry->view->setvar('content', $inbox);
	}

	public function view($id)
	{
		if (empty($id))
			$this->registry->xmberror('pm_not_valid_message');

		$message = $this->registry->db->prepare("
		SELECT * FROM " . X_PREFIX . "messages 
		WHERE (touser = :touser OR fromuser = :fromuser) AND pmid = :pmid 
		");
		$message->execute(array(':touser' => $this->registry->user['username'],
			':fromuser' => $this->registry->user['username'], ':pmid' => $id));
		$message = $message->fetch();

		if (empty($message['pmid']))
			$this->registry->xmberror('pm_invalid_pm');

		if ($message['readstatus'] == 0)
		{
			$this->registry->db->query("
			UPDATE " . X_PREFIX . "messages
			SET readstatus = 1
			WHERE readstatus = 0 AND pmid = $id
			");

			$userupdate = $this->registry->db->prepare("
				UPDATE " . X_PREFIX . "members
				SET unreadpm = unreadpm-1
				WHERE username = :username
			");
			$userupdate->execute(array(':username' => $this->registry->user['username']));
		}

		require_once (X_PATH . '/application/classes/bbcode.class.php');

		$bbcode = new XMB_BBCode($this->registry);
		$bbcode->load_bbcode();

		$message['message'] = $bbcode->parse($message['message']);
		$message['message'] = nl2br($message['message']);

		$this->registry->view->setvar('message', $message);

		$messages = $this->registry->view->loadtovar('message', 'messages');
		$this->registry->view->setvar('content', $messages);
	}

	public function outbox($id)
	{
		$mymessages = $this->registry->db->prepare("
		SELECT * FROM " . X_PREFIX . "messages 
		WHERE fromuser = :fromuser
		ORDER BY readstatus ASC, dateline DESC
		");
		$mymessages->execute(array(':fromuser' => $this->registry->user['username']));

		$outbox = '';
		$outcount = 0;

		require_once (X_PATH . '/application/classes/bbcode.class.php');

		$bbcode = new XMB_BBCode($this->registry);
		$bbcode->load_bbcode();

		if ($mymessages->rowCount() > 0)
		{
			foreach ($mymessages as $messages)
			{
				$messages['subject'] = ($messages['readstatus'] == 0) ?
					'<span style="font-weight: bold;">' . $messages['subject'] . '</span>' : $messages['subject'];
				$messages['sent'] = $this->registry->xmbtime('d-m-Y h:i a', $messages['dateline']);
				$messages['count'] = $outcount;
				$messages['message'] = $bbcode->parse(substr($messages['message'], 0, 150) .
					'...');

				$this->registry->view->setvar('messages', $messages);

				$outcount++;
				$outbox .= $this->registry->view->loadtovar('inbox', 'messages');
			}
		} else
		{
			$outbox = '<span style="text-align: center; font-weight: bold;">No messages!</span>';
		}

		if ($outcount == 0)
			$outbox = '<span style="text-align: center; font-weight: bold;">No messages</span>';

		$this->registry->view->setvar('content', $outbox);
	}

	public function send($id)
	{
		$content = $this->registry->view->loadtovar('send_new', 'messages');

		require_once (X_PATH . '/application/classes/bbcode.class.php');

		$bbcode = new XMB_BBCode($this->registry);
		$bbcode->load_bbcode_buttons();
        
        $this->registry->view->setvar('recipients', $id);
		$this->registry->view->setvar('content', $content);
	}

	public function create($id)
	{
		$clean = array('subject' => 'TYPE_STR', 'message' => 'TYPE_STR', 'recipients' =>
			'TYPE_STR', 'fromuser' => 'TYPE_STR');

		foreach ($clean as $key => $value)
		{
			$this->registry->cleaner->clean('p', $key, $value);
		}

		$this->registry->csrf_protection();

		$subject = $this->registry->cleaner->subject;
		$message = $this->registry->cleaner->message;
		$recipients = $this->registry->cleaner->recipients;
		$fromuser = $this->registry->cleaner->fromuser;

		$recipients = explode(', ', $recipients);

		include (X_PATH . '/application/datahandlers/pm.datahandle.php');

		$pm = new XMB_Datahandler_PM($this->registry);

		$pm->set_info('fromuser', $fromuser);
		$pm->set_info('subject', $subject);
		$pm->set_info('message', $message);
		$pm->set_info('recipient', $recipients);

		$pm->build();
	}
}

?>