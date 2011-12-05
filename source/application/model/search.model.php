<?php

class searchModel extends Model
{
	private $searchtime = 0;

	private $totalresults = array();

	public function index($id)
	{
		//$this->search();
	}

	public function today($id)
	{
		$hours = (isset($this->registry->urlquery['hours'])) ? intval($this->registry->
			urlquery['hours']) : 24;

		/** Latest Threads **/
		$cutoff = X_TIME - (60 * 60 * $hours);

		$threadquery = $this->registry->db->query("
		SELECT p.*, t.* FROM " . X_PREFIX . "posts AS p
		LEFT JOIN " . X_PREFIX . "threads AS t USING (tid) 
		WHERE p.dateline > '" . $cutoff . "'
		ORDER BY p.dateline DESC
		");

		$threads = '';
		$i = 1;
		if ($threadquery->rowCount() > 0)
		{
			foreach ($threadquery as $thread)
			{
				$lastpost = explode('|', $thread['lastpost']);

				$thread['lastpost'] = "By <a href=\"" . $this->registry->options['site_url'] .
					"/members/profile/" . $lastpost['1'] . "\">" . $lastpost['1'] . "</a> at " . $this->
					registry->xmbtime('h:ia', $lastpost['0']);
				$this->registry->view->setvar('thread', $thread);
				$this->registry->view->setvar('i', $i);
				$i++;

				$threads .= $this->registry->view->loadtovar('search_today_threads', 'search');
			}
			$this->registry->view->setvar('results', $threadquery->rowCount());
			$this->registry->view->setvar('threads', $threads);
		} else
		{
			$threads = 'No new posts today.';

			$this->registry->view->setvar('results', 0);
			$this->registry->view->setvar('threads', $threads);
		}
	}

	public function dosearch($id)
	{
		$this->registry->cleaner->clean('p', 'query', 'TYPE_STR');

		$searchquery = (isset($this->registry->cleaner->cleaned['query']) ? $this->
			registry->cleaner->cleaned['query'] : false);

		if ($this->registry->options['search_engine'] == 'sphinx')
		{
			$this->sphinxsearch($searchquery);
		} else
		{
			$this->xmbsearch($searchquery);
		}
	}

	private function xmbsearch($searchquery)
	{
		if (!empty($searchquery))
		{
			// Perform Search
			$beforetime = X_TIME;
			$searchresults = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "posts
			WHERE MATCH(message, subject) AGAINST (:query)
			ORDER BY dateline DESC
			");
			$searchresults->execute(array(':query' => $searchquery));

			$aftertime = X_TIME;

			$this->searchtime = $aftertime - $beforetime;
			$this->totalresults = $searchresults->rowCount();
			$this->build_search($searchresults, $searchquery);
		} else
		{
			$this->registry->xmberror('search_no_results');
		}
	}

	private function sphinxsearch($searchquery)
	{
		require_once (X_PATH . '/application/classes/3rdparty/sphinxapi.php');

		$search = new SphinxClient();
		$search->setServer('127.0.0.1', 9312);
		$search->setMatchMode(SPH_MATCH_ANY);
		$search->setSortMode(SPH_SORT_RELEVANCE);
		$search->setMaxQueryTime(10000);

		$postresults = $search->addQuery($searchquery, 'posts');
		$userresults = $search->addQuery($searchquery, 'users');

		$results = $search->RunQueries();

		if ($search->GetLastWarning() || $search->GetLastError())
		{
			$this->registry->xmberror('search_no_results');
		} else
			if ($results[0]['total_found'] == 0 || $results[1]['total_found'] == false)
			{
				$this->registry->xmberror('search_no_results');
			} else
			{
				$users = array();
				$this->totalresults['users'] = $results[1]['total_found'];
				foreach ($results[1]['matches'] as $doc => $docinfo)
				{
					$users[] = intval($doc);
				}

				$posts = array();
				$this->totalresults['posts'] = $results[0]['total_found'];
				foreach ($results[0]['matches'] as $doc => $docinfo)
				{
					$posts[] = intval($doc);
				}

				if ($this->totalresults > 0)
				{
					$timebefore = X_TIME;
					$postresults = $this->registry->db->prepare("
					SELECT * FROM " . X_PREFIX . "posts
					WHERE pid IN (:posts)
					ORDER BY dateline DESC
				");
					$postresults->execute(array(':posts' => implode(',', $posts)));

					$userresults = $this->registry->db->prepare("
					SELECT * FROM " . X_PREFIX . "members
					WHERE uid IN (:uids)
					ORDER BY username ASC
				");
					$userresults->execute(array(':uids' => implode(',', $users)));

					$searchresults = array('users' => $userresults, 'posts' => $postresults);

					$timeafter = X_TIME;
					$this->searchtime = ($results[0]['time'] + $results[1]['time']) + ($timeafter -
						$timebefore);

					$this->build_search($searchresults, $searchquery);
				} else
				{
					$this->registry->xmberror('search_no_results');
				}
			}
	}

	private function build_search($searchresults, $searchquery)
	{
		$results = '';

		if ($searchresults['posts']->rowCount() > 0 || $searchresults['users']->
			rowCount() > 0)
		{
			$resultcount = $searchresults['posts']->rowCount() + $searchresults['users']->
				rowCount();
			$postresults = $searchresults['posts']->fetchAll(PDO::FETCH_NAMED);
			$userresults = $searchresults['users']->fetchAll(PDO::FETCH_NAMED);

			foreach ($postresults as $search)
			{
				$search['date'] = $this->registry->xmbtime('l jS F Y', $search['dateline']);
				$search['message'] = substr($search['message'], 0, 150);
				$search['timetaken'] = $this->searchtime;
				$search['totalfound'] = $this->totalresults['posts'] + $this->totalresults['users'];
				$this->registry->view->setvar ( 'search', $search );


				$results .= $this->registry->view->loadtovar ( 'search_results_bits', 'search' );
			}

			foreach ($postresults as $search)
			{
				if ($search['subject'])
				{
					$postresults['date'] = $this->registry->xmbtime('l jS F Y', $search['dateline']);
					$postresults['message'] = substr($search['message'], 0, 150);
					$postresults['timetaken'] = $this->searchtime;
					$postresults['totalfound'] = $this->totalresults['posts'] + $this->totalresults['users'];
				}
			}

			foreach ($userresults as $search)
			{
				print_r($search);
				if ($search['uid'])
				{
					$userresults['regdate'] = $this->registry->xmbtime('d M Y', $search['regdate']);
					$userresults['timetaken'] = $this->searchtime;
					$userresults['totalfound'] = $this->totalresults['posts'] + $this->totalresults['users'];
				}
			}

			$this->registry->view->setvar('postresults', $postresults);
			$this->registry->view->setvar('userresults', $userresults);
			$this->registry->view->setvar('results', $resultcount);
		} else
		{
			$this->registry->xmberror('search_no_results');
		}
	}
}

?>