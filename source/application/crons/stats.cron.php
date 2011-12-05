<?php

/** STATISTICS **/
$stats = $this->registry->db->query("
		SELECT username, uid
		FROM " . X_PREFIX . "members
		ORDER BY regdate DESC
		");

$stats = $stats->fetch();

$count = $this->registry->db->query("
	SELECT COUNT(uid) AS totalusers
	FROM xmb_members
");
$count = $count->fetch();

//$count = $count->fetch();
$usercount = $count['totalusers'];

$newest = array('username' => $stats['username'], 'uid' => $stats['uid'],
	'usernamelink' => str_replace(' ', '-', $stats['username']));

/** STATISTICS **/

$stats = $this->registry->db->query("
		SELECT COUNT(DISTINCT tid) AS threads, COUNT(pid) AS totalposts
		FROM " . X_PREFIX . "posts
		ORDER BY dateline DESC
		");
$stats = $stats->fetch();

$threadcount = $stats['threads'];
$postcount = $stats['totalposts'];

$statistics = array('threadcount' => $threadcount, 'postcount' => $postcount,
	'usercount' => $usercount, 'newest_username' => $newest['username'],
	'newest_uid' => $newest['uid'], 'newest_usernamelink' => $newest['usernamelink']);

$this->registry->cache->rebuild_cache('statistics', $statistics);

?>