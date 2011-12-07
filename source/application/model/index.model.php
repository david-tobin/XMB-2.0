<?php

class indexModel extends Model
{
    public function index($id) {
        // DO NOTHING    
    }
    
    public function stream($id) {
        $stream = Module::register('stream');
        
        if ($stream != false) {
        	$stream->createStream();
        } else {
        	$this->registry->xmberror('feature_disabled');
        }
    }
    
	public function home($id)
	{
		/** GRAB FORUMS **/

		$forumlist = $this->registry->db->query("
		SELECT * FROM " . X_PREFIX . "forums
		WHERE status = '1'
		ORDER BY parentid ASC, displayorder ASC, name ASC
		");

		$forum = '';
		$forumcache = array();
		foreach ($forumlist as $forums)
		{
			if ($forums['parentid'] == 0)
			{
				$forumcache['forums'][$forums['fid']]['catagory'] = $forums;
			} else
				if ($forums['type'] == 'forum')
				{
					$forumcache['forums'][$forums['parentid']]['forum'][$forums['fid']] = $forums;
				} else
					if ($forums['type'] == 'subforum')
					{
						$subforumcache['subforums'][$forums['parentid']][$forums['fid']] = $forums['name'];
					}
		}
		$this->forumcache = $forumcache;
		$this->subforumcache = $subforumcache;

		foreach ($this->forumcache['forums'] as $key => $value)
		{
			$this->currentforums = $this->forumcache['forums'][$key]['forum'];
			$cat = $value['catagory'];

			if ($cat['fid'])
			{
				$this->registry->view->setvar('forum', $cat);
				$forum .= $this->registry->view->loadtovar('index_catagory', 'index');
			}

			foreach ($this->currentforums as $forumkey => $forumvalue)
			{
				$lastpost = explode('|', $forumvalue['lastpost']);
				if (count($lastpost) > 1)
				{
					$forumvalue['lastposttime'] = $this->registry->xmbtime('d-m-Y @ h:i a', $lastpost['0'],
						1);
					$forumvalue['lastposter'] = $lastpost['1'];
					$forumvalue['lastposterlink'] = str_replace(' ', '-', $lastpost['1']);
					$forumvalue['lastpostid'] = $lastpost['2'];
					$forumvalue['lastposttitle'] = $lastpost['3'];
					$forumvalue['seolink'] = $this->registry->seolink($lastpost['2'], $lastpost['3']);
				} else
				{
					$forumvalue['lastposttime'] = $forumvalue['lastposter'] = $forumvalue['lastposterlink'] =
						$forumvalue['lastpostid'] = $forumvalue['seolink'] = $forumvalue['lastposttitle'] =
						'';
				}

				$forumvalue['subforums'] = '';
				if (isset($this->subforumcache['subforums'][$forumvalue['fid']]))
				{
					foreach ($this->subforumcache['subforums'][$forumvalue['fid']] as $subid => $subname)
					{
						$forumvalue['subforums'] .= '<a href="' . $this->registry->options['site_url'] .
							'/forum/display/' . $subid . '">' . $subname . '</a>, ';
					}
				}
				$forumvalue['subforums'] = substr($forumvalue['subforums'], 0, -2);

				$this->registry->view->setvar('forum', $forumvalue);
				$forum .= $this->registry->view->loadtovar('index_forums', 'index');
			}

			$forum .= $this->registry->view->loadtovar('index_catagory_end', 'index');
		}
		$this->registry->view->setvar('forumdisplay', $forum);

		if ($this->registry->fetch_hook('index_complete') != false)
			eval($this->registry->fetch_hook('index_complete'));
	}
    
    public function set_statistics($id) {
		$stats = $this->registry->cache->get_cache('statistics');

		/** SET VARIABLES TO BE LOADED IN PAGE **/
		$this->registry->view->setvar('stats', $stats);
    }
}

?>