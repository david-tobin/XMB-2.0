<?php

class membersModel extends Model
{

	public function index($id)
	{

	}

	public function all($id)
	{
		switch ($id)
		{ //@TODO Finish
			case 'username':
				$id = 'username ASC';
				break;

			case 'location':
				$id = 'location ASC';
				break;

			case 'joined':
				$id = 'regdate DESC';
				break;

			default:
				$id = 'username ASC';
				break;
		}

		//@TODO Pagination!
		$members = $this->registry->db->query("
			SELECT * FROM " . X_PREFIX . "members
			ORDER BY $id
			LIMIT 0,50
		");

		$memberlist = '';
		if ($members->rowCount() > 0)
		{
			foreach ($members as $member)
			{
				$member['regdate'] = $this->registry->xmbtime('d M Y', $member['regdate']);
				$this->registry->view->setvar('member', $member);

				$memberlist .= $this->registry->view->loadtovar('all_bits', 'members');
			}
			$this->registry->view->setvar('memberlist', $memberlist);
		} else
		{
			$this->registry->xmberror();
		}
	}

	public function profile($id)
	{
		$profile = $this->registry->db->prepare("
		SELECT m.*, mf.*, m.uid AS uid FROM " . X_PREFIX . "members AS m
		LEFT JOIN " . X_PREFIX . "memberfields AS mf USING (uid)
		WHERE m.username = :username
		");
		$profile->execute(array(':username' => $id));
		$profile = $profile->fetch();

		$lastpost = $this->registry->db->prepare("
		SELECT * FROM " . X_PREFIX . "posts WHERE author = :author
		ORDER BY dateline DESC
		");
		$lastpost->execute(array(':author' => $id));
		$lastpost = $lastpost->fetch();

		require_once (X_PATH . '/application/classes/bbcode.class.php');

		$bbcode = new XMB_BBCode($this->registry);
		$bbcode->load_bbcode();

		if (!isset($profile['uid']))
		{
			$this->registry->xmberror('member_not_exists');
		} else
		{
			$profile['regdate'] = $this->registry->xmbtime('d-m-Y', $profile['regdate']);
			$profile['subject'] = $lastpost['subject'];
			$profile['message'] = $bbcode->parse(substr($lastpost['message'], 0, 255) .
				"....");
			$profile['dateline'] = $this->registry->xmbtime('d-m-Y', $lastpost['dateline']);
			$profile['lastpostid'] = $lastpost['pid'];
			$profile['lastthreadid'] = $lastpost['tid'];

			$this->registry->view->setvar('members', $profile);
		}
	}
}

?>