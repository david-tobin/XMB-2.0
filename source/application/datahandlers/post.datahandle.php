<?php

if (!defined('IN_CODE'))
{
	die('You cannot run this file directly');
}
/**
 * Handles Post/Thread Data Management
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_Datahandler_Post extends XMB_Datahandler
{
	/**
	 *
	 * @var $type string
	 */
	private $type = '';
	/**
	 *
	 * @var $title string
	 */
	private $subject = '';
	/**
	 * @var $message string
	 */
	private $message = '';
	/**
	 * @var $author string
	 */
	private $author = '';
	/**
	 *
	 * @var $dateline unknown_type
	 */
	private $dateline = X_TIME;
	/**
	 *
	 * @var $errors array
	 */
	private $errors = array();
	/**
	 * @var $fid integer
	 */
	private $fid = 0;
	/**
	 *
	 * @var $tid integer
	 */
	private $tid = 0;
	/**
	 *
	 * @var $pid integer
	 */
	private $pid = 0;
	/**
	 *
	 * @var $ip string
	 */
	private $ip = '';
	/**
	 * @var $bbcode integer
	 */
	private $bbcode = 1;
	/**
	 *
	 * @var $smilies integer
	 */
	private $smilies = 1;
	/**
	 * @var $lastpost string
	 */
	private $lastpost = '';
	/**
	 *
	 * @var unknown_type
	 */
	private $replysubject = false;

	/**
	 * Sets info for a setting
	 * @param unknown_type $info
	 * @param unknown_type $value
	 */
	public function set_info($info, $value)
	{
		switch ($info)
		{
			case 'author':
				$this->author = $value;
				break;

			case 'message':
				$this->message = $value;
				break;

			case 'subject':
				$this->subject = $value;
				break;

			case 'dateline':
				$this->dateline = intval($value);
				break;

			case 'fid':
				$this->fid = intval($value);
				break;

			case 'tid':
				$this->tid = intval($value);
				break;

			case 'ip':
				$this->ip = $value;
				break;

			case 'bbcode':
				$this->bbcode = intval($value);
				break;

			case 'smilies':
				$this->smilies = intval($value);
				break;

			case 'type':
				$this->type = ($value == 'thread') ? 'thread':
				'post';
		}
	}

	/**
	 * Builds the post/thread
	 */
	public function build()
	{
		$this->perform_checks();

		if (empty($this->errors))
		{
			$type = 'insert_' . $this->type;
			$this->$type();
		} else
		{
			foreach ($this->errors as $key => $value)
			{
				$this->registry->xmberror($value);
				exit();
			}
		}

		return $this->tid . '/' . $this->pid;
	}

	/**
	 * Performs checks before building a thread/post
	 */
	public function perform_checks()
	{
		if (empty($this->author))
		{
			$this->errors[] = "thread_missing_author";
		}

		if (empty($this->message))
		{
			$this->errors[] = "thread_missing_message";
		}

		if (empty($this->subject) && $this->type == 'thread')
		{
			$this->errors[] = "thread_missing_subject";
		} else
		if (empty($this->subject) && $this->type == 'post')
		{
			$this->replysubject = 1;
		}
	}

	/**
	 * Inserts the post
	 *
	 * @return $pid integer
	 */
	public function insert_post()
	{
		if (empty($this->fid))
		{
			$fid = $this->registry->db->query("
				SELECT fid, subject FROM " . X_PREFIX . "threads
				WHERE tid = '" . $this->tid . "'
			");
			$fid = $fid->fetch();

			if (!empty($fid['fid']))
			$this->fid = $fid['fid'];
			if ($this->type == 'post' && empty($this->subject))
			$this->subject = 'RE: ' . $fid['subject'];
		}

		$this->lastpost = $this->dateline . '|' . $this->author . '|' . $this->tid . '|' .
		$this->subject;

		$post = $this->registry->db->prepare("
			INSERT INTO " . X_PREFIX .
			"posts (tid, subject, author, message, useip, bbcodeoff, smileyoff, dateline)
			VALUES (:tid, :subject, :author, :message, :ip, :bbcode, :smilies, :dateline)
		");
		$post->execute(array(':tid' => $this->tid, ':subject' => $this->subject,
			':author' => $this->author, ':message' => $this->message, ':ip' => $this->ip,
			':bbcode' => $this->bbcode, ':smilies' => $this->smilies, ':dateline' => $this->
		dateline));

		if ($this->registry->user['uid'] > 0)
		{
			$this->registry->db->query("
				UPDATE " . X_PREFIX . "members
				SET posts = posts+1
				WHERE uid = '" . $this->registry->user['uid'] . "'
			");
		}

		$plus = 0;
		$reply = '';
		if ($this->type == 'thread')
		$plus = 1;
		if ($this->type != 'thread')
		$reply = ',replies = replies+1';

		$tup = $this->registry->db->prepare("
				UPDATE " . X_PREFIX . "threads
				SET lastpost = :lastpost
				$reply
				WHERE tid = :tid
			");

				$tup->execute(array(':lastpost' => $this->lastpost, ':tid' => $this->tid));

				$fup = $this->registry->db->prepare("
				UPDATE " . X_PREFIX . "forums
				SET lastpost = :lastpost,
				posts = posts+1,
				threads = threads+$plus
				WHERE fid = :fid
			");

				$fup->execute(array(':lastpost' => $this->lastpost, ':fid' => $this->fid));

				$this->pid = $this->registry->db->lastInsertId();

				$stream = Module::register('stream');

				if ($stream != false) {
					if ($this->type == 'thread') {
						$streaminfo = array(
		        			'streamer'	=>	$this->registry->user['username'],
		        			'type'		=>	'threadpost',
		        			'params'	=>	array('dateline' => X_TIME, 'subject' => $this->subject, 'tid' => $this->tid)
						);
					} else {
						$shortmessage = substr($this->message, 0, 200) . '...';
						$streaminfo = array(
							'streamer'	=> $this->registry->user['username'],
							'type'		=> 'reply',
							'params'	=> array('dateline' => X_TIME, 'subject' => $this->subject, 'tid' => $this->tid, 'message' => $shortmessage)
						);
					}
					$stream->addStream($streaminfo);
				}

				return $this->pid;
	}

	/**
	 * Inserts the thread
	 *
	 * @return $tid integer
	 */
	public function insert_thread()
	{
		$thread = $this->registry->db->prepare("
			INSERT INTO " . X_PREFIX . "threads (fid, subject, author, dateline)
			VALUES (:fid , :subject , :author , :dateline)
		");

		$thread->execute(array(':fid' => $this->fid, ':subject' => $this->subject,
			':author' => $this->author, ':dateline' => $this->dateline));

		$this->tid = $this->registry->db->lastInsertId();

		$this->insert_post();

		return $this->tid;
	}
}

?>