<?php

class threadModel extends Model
{

	public function index($id)
	{
		$this->view($id);
	}

	public function view($id)
	{
		$id = explode('-', $id);
		$id = intval($id[0]);

		$this->registry->view->setvar('threadid', $id);

		$posts = $menus = '';

		$threadinfo = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "threads AS t
			LEFT JOIN " . X_PREFIX . "forums USING (fid)
			WHERE t.tid = :tid
		");
		$threadinfo->execute(array(':tid' => $id));
		$threadinfo = $threadinfo->fetch();

		$threads = $this->registry->db->prepare("
		SELECT p.*, p.subject AS postsubject, p.author AS poster, m.status AS mstatus, m.posts AS totalposts, m.*, p.rating AS rating, p.dateline AS dateline FROM " .
			X_PREFIX . "posts AS p
		LEFT JOIN " . X_PREFIX . "members AS m ON (p.author = m.username)
		WHERE p.tid = :tid ORDER BY p.dateline ASC
		");
		$threads->execute(array(':tid' => $id));

		if ($threads->rowCount() == 0)
		{
			$this->registry->xmberror('invalid_thread');
		} else
		{
			// Add a view if neccessary
			$this->registry->db->query("
		UPDATE " . X_PREFIX . "threads 
		SET views = views+1
		WHERE tid = '" . $id . "'
		");

			require_once (X_PATH . '/application/classes/bbcode.class.php');

			$bbcode = new XMB_BBCode($this->registry);

			$attaches = array();
			foreach ($threads as $thread)
			{
				$thread['regdate'] = $this->registry->xmbtime('d M y', $thread['regdate']);
				$thread['posted'] = $this->registry->xmbtime('M d Y @ h:i a', $thread['dateline'],
					1);
				$thread['online'] = (in_array($thread['poster'], $this->registry->whoisonline)) ?
					'offline' : 'online';
				$thread['online_color'] = ($thread['online'] == 'online') ? 'green' : 'red';
				$thread['sig'] = ($thread['usesig'] == 'no') ? false : $thread['sig'];
				$thread['rating_prefix'] = ($thread['rating'] > 0) ? '+' : '';
				$thread['rating_color'] = ($thread['rating'] > 0) ? 'green' : 'red';
				$thread['message'] = $bbcode->parse($thread['message']);
				$thread['fid'] = $threadinfo['fid'];
				$thread['name'] = $threadinfo['name'];
				$thread['title'] = $threadinfo['subject'];
				$attaches[$thread['pid']] = $thread['attachments'];
				if ($thread['rating'] == 0)
				{
					$thread['rating_color'] = '#5f5f5f';
				}

				if (!empty($thread['raters']))
				{
					$thread['raters'] = unserialize($thread['raters']);
				} else
				{
					$thread['raters'] = array();
				}

				$this->registry->modeltocontroller['fid'] = $thread['fid'];
				$this->registry->modeltocontroller['fname'] = $thread['name'];
				$this->registry->modeltocontroller['title'] = $thread['title'];
				$this->registry->view->setvar('thread', $thread);

				$postattach = '';

				$menus .= $this->registry->view->loadtovar('view_post_menus', 'thread');
				$posts .= $this->registry->view->loadtovar('view_post', 'thread');
			}

			$globalattach = '';
			foreach ($attaches as $pid => $attach)
			{
				if (!empty($attach))
				{
					$globalattach .= $attach . ',';
				}
			}
			if (!empty($globalattach))
			{
				$globalattach = substr($globalattach, 0, -2);


				$attaches = $this->registry->db->query("
				SELECT aid, pid, filename, filetype, filesize, downloads, img_size
				FROM " . X_PREFIX . "attachments				
				WHERE aid IN (" . $globalattach . ")
				");

				$attachcache = array();
				foreach ($attaches as $attach)
				{
					$attachcache[$attach['pid']]['aid'][] = $attach['aid'];
					$attachcache[$attach['pid']]['title'][] = $attach['filename'];
				}
				$this->registry->view->setvar('attachments', $attachcache);
			}
		}

		$this->registry->view->setvar('menus', $menus);
		$this->registry->view->setvar('posts', $posts);
	}

	public function newthread($id)
	{
		$forumid = intval($id);

		if ($forumid == false)
		{
			$this->registry->xmberror('invalid_forum');
		}

		if (!$this->registry->user['uid'] > 0)
		{
			$this->registry->xmberror('guest_no_post');
		}

		$this->registry->view->setvar('forumid', $forumid);

		require_once (X_PATH . '/application/classes/bbcode.class.php');

		$bbcode = new XMB_BBCode($this->registry);
		$bbcode->load_bbcode_buttons();
	}

	public function postthread($id)
	{
		//die(print_r($_FILES));
		$clean = array('message' => 'TYPE_STR', 'subject' => 'TYPE_STR', 'forumid' =>
			'TYPE_INT');

		foreach ($clean as $key => $value)
		{
			$this->registry->cleaner->clean('p', $key, $value);
		}

		$this->registry->cleaner->message = nl2br($this->registry->cleaner->message);

		$thread = array();

		$thread['subject'] = $this->registry->cleaner->subject;
		$thread['message'] = $this->registry->cleaner->message;
		$thread['forumid'] = $this->registry->cleaner->forumid;
		$thread['author'] = $this->registry->user['username'];

		$lastpost = array(X_TIME, $thread['author'], $this->registry->user['uid'], $thread['subject']);
		$lastpost = implode('|', $lastpost);

		/** DEBUGGING **/
		$thread['usesig'] = 1;

		/** POST THREAD **/
		require_once (X_PATH . '/application/datahandlers/post.datahandle.php');
		$threader = new XMB_Datahandler_Post($this->registry);

		$threader->set_info('subject', $thread['subject']);
		$threader->set_info('author', $thread['author']);
		$threader->set_info('message', $thread['message']);
		$threader->set_info('fid', $thread['forumid']);
		$threader->set_info('ip', $_SERVER['REMOTE_ADDR']);

		$threader->set_info('type', 'thread');

		$threadinfo = $threader->build();

		$threadinfo = explode('/', $threadinfo);

		$threadid = $threadinfo[0];
		$postid = $threadinfo[1];

		/** Attachments **/
		$files = array();
		$current = 0;

		$this->registry->cleaner->clean('f', 'attach', 'TYPE_ARRAY');

		require_once (X_PATH . '/application/datahandlers/attachment.datahandle.php');
		$attach = new XMB_Datahandler_Attachment($this->registry);

		$this->registry->cleaner->clean('f', 'attachment', 'TYPE_ARRAY');
		$files = $this->registry->cleaner->attachment;

		$this->attachments_handle($files, $postid);

		$this->registry->redirect('/thread/view/' . $threadid);
	}

	public function reply($id)
	{
		$id = intval($id);

		require_once (X_PATH . '/application/classes/bbcode.class.php');

		$bbcode = new XMB_BBCode($this->registry);
		$bbcode->load_bbcode_buttons();

		$this->registry->view->setvar('tid', $id);

		if (!$this->registry->user['uid'] > 0)
		{
			$this->registry->xmberror('guest_no_post');
		}
	}

	public function doreply($id)
	{
		$clean = array('message' => 'TYPE_STR', 'subject' => 'TYPE_STR');

		$this->registry->csrf_protection();

		foreach ($clean as $key => $value)
		{
			$this->registry->cleaner->clean('p', $key, $value);
		}

		$this->registry->cleaner->message = nl2br($this->registry->cleaner->message);

		$thread = array();

		$thread['subject'] = $this->registry->cleaner->subject;
		$thread['message'] = $this->registry->cleaner->message;
		$thread['author'] = $this->registry->user['username'];

		$lastpost = array(X_TIME, $thread['author'], $this->registry->user['uid'], $thread['subject']);
		$lastpost = implode('|', $lastpost);

		/** DEBUGGING **/
		$thread['usesig'] = 1;

		/** POST THREAD **/
		require_once (X_PATH . '/application/datahandlers/post.datahandle.php');
		$threader = new XMB_Datahandler_Post($this->registry);

		$threader->set_info('subject', $thread['subject']);
		$threader->set_info('author', $thread['author']);
		$threader->set_info('message', $thread['message']);
		$threader->set_info('dateline', X_TIME);
		$threader->set_info('ip', $_SERVER['REMOTE_ADDR']);
		$threader->set_info('tid', $id);

		$threader->set_info('type', 'post');

		$threadinfo = $threader->build();

		$threadinfo = explode('/', $threadinfo);

		$threadid = $threadinfo[0];
		$postid = $threadinfo[1];

		/** Attachments **/
		$files = array();
		$current = 0;

		$this->registry->cleaner->clean('f', 'attachment', 'TYPE_ARRAY');
		$files = $this->registry->cleaner->attachment;

		$this->attachments_handle($files, $postid);

		$this->registry->redirect('/thread/view/' . $threadid);
	}

	private function attachments_handle($files, $pid)
	{
		require_once (X_PATH . '/application/datahandlers/attachment.datahandle.php');
		$attach = new XMB_Datahandler_Attachment($this->registry);

		if (!empty($_FILES['attachment']))
		{ // @TODO Security and other tasks
			$i = 0;
			foreach ($_FILES as $fileid => $file)
			{
				$data = $attach->get_temp_file($file['tmp_name'][$i]);
				if (empty($data))
					return;

				$attach->set_info('name', $file['name'][$i]);
				$attach->set_info('size', $file['size'][$i]);
				$attach->set_info('data', $data);
				$attach->set_info('type', $file['type'][$i]);
				$attach->set_info('pid', $pid);

				$attach->break_file();
				$i++;
			}

			$attach->build();
		}
	}
}
