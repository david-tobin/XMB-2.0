<?php

class forumModel extends Model
{
	/**
	 * (non-PHPdoc)
	 * @see app/application/Model#index($id)
	 */
	public function index($id)
	{

	}

	/**
	 *
	 * @param $id
	 * @return unknown_type
	 */
	public function display($id)
	{
		$id = intval($id);
		$page = (!empty($this->registry->urlquery['page'])) ? intval($this->registry->
			urlquery['page']) : 1;
		$perpage = (!empty($this->registry->urlquery['perpage'])) ? intval($this->
			registry->urlquery['perpage']) : 20;

		$limitstart = ($page - 1) * $perpage;

		if (!isset($id) || $id == 0)
		{
			$this->registry->xmberror('invalid_forum');
		} else
		{
			$totalthreads = $this->registry->db->prepare("
			SELECT COUNT(tid) AS total FROM " . X_PREFIX . "threads
			WHERE fid = :fid
			");
			$totalthreads->execute(array(':fid' => $id));
			$totalthreads = $totalthreads->fetch();

			$totalthreads = $totalthreads['total'];

			$forum = $this->registry->db->prepare("
			SELECT f.*, f.fid AS forumid, t.* FROM " . X_PREFIX . "forums AS f
			LEFT JOIN " . X_PREFIX . "threads AS t USING (fid)
			WHERE fid = :fid
			ORDER BY t.lastpost DESC, t.subject ASC
			Limit :limit, :perpage
			");
			$forum->bindParam(':limit', $limitstart, PDO::PARAM_INT);
			$forum->bindParam(':perpage', $perpage, PDO::PARAM_INT);
			$forum->bindParam(':fid', $id, PDO::PARAM_INT);
			$forum->execute();

			$pagination = $this->registry->pagination($totalthreads, $this->registry->
				options['site_url'] . '/forum/display/' . $id . '/');

			$this->registry->view->setvar('pagination', $pagination);

			$threads = '';
			$forums = array();
			$this->registry->modeltocontroller['navname'] = '';
			foreach ($forum as $forums)
			{
				// @TODO FIX - Odd Behavior
				// if (empty($forums['fid'])) $this->registry->xmberror('invalid_forum');
				if ($totalthreads > 0)
				{
					$forums['lastpost'] = explode('|', $forums['lastpost']);
					$forums['lastposttime'] = $this->registry->xmbtime('M d Y  h:i a', $forums['lastpost'][0],
						1);
					$forums['lastpostby'] = $forums['lastpost'][1];
					$forums['lastpostid'] = $forums['lastpost'][2];
					$forums['lastposttitle'] = $forums['lastpost'][3];
				}

				$forums['rating_color'] = ($forums['rating'] > 0) ? 'green' : 'red';
				if ($forums['rating'] == 0)
					$forums['rating_color'] = 'inherit';

				$this->registry->view->setvar('forums', $forums);
				$this->registry->modeltocontroller['navname'] = $forums['name'];

				if ($totalthreads > 0)
				{
					$threads .= $this->registry->view->loadtovar('display_thread', 'forum');
				}
			}

			if ($totalthreads > 0)
			{
				$this->registry->view->setvar('threads', $threads);
				$this->registry->view->setvar('totalthreads', $totalthreads);
			} else
			{
				$noresults = '<tr><td class="alt1" colspan="4">This forum currently has no threads.</td></tr>';

				$this->registry->view->setvar('threads', $noresults);
			}
		}
	}
}

?>