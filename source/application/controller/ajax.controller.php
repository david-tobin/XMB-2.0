<?php

/**
 * Ajax Controller
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class ajaxController extends Controller
{
	private $method;

	public function index($id = '')
	{

	}
    
    public function update_status($id = '') {
   	    $this->registry->cleaner->clean('p', 'status', 'TYPE_STR');
		$status = $this->registry->cleaner->status;

		if (!empty($status)) {
			if ($this->registry->user['uid'] > 0) {
				$updatestatus = $this->registry->db->prepare("
    				UPDATE ".X_PREFIX."members
    				SET status = :status
    				WHERE username = :username
		      	");
				
                $updatestatus->execute(array(':status' => $status, ':username' => $this->registry->user['username']));
                
                $stream = Module::register('stream');

				if ($stream != false) {
					$streaminfo = array(
					'streamer'	=> $this->registry->user['username'],
					'type'		=> 'status',
					'params' 	=> array('dateline' => X_TIME, 'status' => $status)
					);
					$stream->addStream($streaminfo);
					
                    if ($id == 1) {
					   $this->doRefreshStream($stream);
                    }
                }
            }
            
            echo "1";
        } else {
            echo "0";
        }
    }

	public function edittitle($id = '')
	{
		//$this->registry->csrf_protection();

		$this->registry->cleaner->clean('p', 'title', 'TYPE_STR');
		$this->registry->cleaner->clean('p', 'tid', 'TYPE_STR');

		$title = (isset($this->registry->cleaner->cleaned['title']) ? $this->registry->
		cleaner->cleaned['title'] : '0');
		$tid = (isset($this->registry->cleaner->cleaned['tid']) ? $this->registry->
		cleaner->cleaned['tid'] : '0');
		$tid = intval($tid);

		if ($this->registry->perm->check('can_rename') == 1)
		{
			$edit = $this->registry->db->prepare("
			UPDATE " . X_PREFIX . "threads SET subject = :title WHERE tid = :tid
			");
			$edit->execute(array(':title' => $title, ':tid' => $tid));

			echo 'success';
		} else
		{
			echo 'fail';
		}
	}

	public function deletethread($id = '')
	{
		$this->registry->csrf_protection();

		$this->registry->cleaner->clean('p', 'tid', 'TYPE_INT');
		$this->registry->cleaner->clean('p', 'fid', 'TYPE_INT');

		$tid = (isset($this->registry->cleaner->cleaned['tid']) ? $this->registry->
		cleaner->cleaned['tid'] : '0');
		$fid = (isset($this->registry->cleaner->cleaned['fid']) ? $this->registry->
		cleaner->cleaned['fid'] : '0');
		$fid = intval($fid);
		$tid = intval($tid);

		if ($this->registry->perm->check('can_harddelete') == 1 && $tid != 0 && $fid !=
		0)
		{
			$this->registry->db->query("
			DELETE FROM " . X_PREFIX . "threads WHERE tid = '" . $tid . "'
			");

			$this->registry->db->query("
			DELETE FROM " . X_PREFIX . "posts WHERE tid = '" . $tid . "'
			");

			$lastpost = $this->registry->db->query("
				SELECT * FROM " . X_PREFIX . "threads
				WHERE fid = '" . $fid . "'
				ORDER BY tid DESC Limit 1
			");
			$lastpost = $lastpost->fetch();

			$postcount = $this->registry->db->query("
				SELECT COUNT(pid) AS totalposts
				FROM " . X_PREFIX . "posts
				WHERE tid = $tid
			");
			$postcount = $postcount->fetch();

			if (isset($lastpost['dateline']))
			{
				$update = $this->registry->db->prepare("
					UPDATE " . X_PREFIX . "forums
					SET lastpost = :lastpost,
					posts = posts-:postcount,
					threads = threads-1
					WHERE fid = :fid
				");
				$update->execute(array(':lastpost' => $lastpost['dateline'] . "|" . $lastpost['author'] .
					"|" . $lastpost['tid'] . "|" . $lastpost['subject'], ':postcount' => $postcount['totalposts'],
					':fid' => $lastpost['fid']));
			}

			echo 'success';
		} else
		{
			echo 'fail';
		}
	}

	public function checkusername($id = '')
	{
		// @TODO Fix $this->registry->csrf_protection();

		$this->registry->cleaner->clean('p', 'username', 'TYPE_STR');

		$username = (isset($this->registry->cleaner->cleaned['username']) ? $this->
		registry->cleaner->cleaned['username'] : false);

		if ($username != false)
		{

			if (strlen($username) < 3)
			{
				echo '<span style="font-size: 11px;"><span style="color: red; font-weight: bold;">&#10008;</span> Username must be greater than 3 characters.</span>';
				exit;
			}

			$checkusername = $this->registry->db->prepare("
			SELECT username FROM " . X_PREFIX .
				"members WHERE LOWER(username) = :username
			");
			$checkusername->execute(array('username' => strtolower($username)));
			$checkusername = $checkusername->fetch();

			$username = stripslashes($username);

			if ($checkusername['username'] == false)
			{
				echo '<span style="color: green; font-weight: bold;">&#10004;</span>';
				exit;
			} else
			{
				echo '<span style="font-size: 11px;"><span style="color: red; font-weight: bold;">&#10008;</span> That username is already taken.';
				exit;
			}
		}
	}

	public function checkemail($id = '')
	{
		$this->registry->cleaner->clean('p', 'email', 'TYPE_STR');

		$email = $this->registry->cleaner->email;

		$check = preg_match('/^([a-zA-Z0-9]{1})+([a-zA-Z0-9_-])*@([a-zA-Z0-9]{1})+([a-zA-Z0-9_-])*\.([a-zA-Z\.])+?$/',
		$email);

		if ($check == 1)
		{
			echo '<span style="color: green; font-weight: bold;">&#10004;</span>';
		} else
		{
			echo '<span style="font-size: 11px;"><span style="color: red; font-weight: bold;">&#10008;</span> That is an invalid email.';
		}
	}

	public function checkpassword($id = '')
	{
		$strength = 0;

		$this->registry->cleaner->clean('p', 'password', 'TYPE_STR');

		$check = $this->registry->cleaner->password;

		if (strlen($check) >= 6)
		$strength = $strength + 0.6;

		if (preg_match('/([\[!\^\$%�&\*\(\)-_\+=\]\{\}~#@;:\?><\.,"])+?/', $check) == 1)
		$strength = $strength + 0.6;

		if (preg_match('/([A-Z])+?/', $check) == 1)
		$strength = $strength + 0.6;

		if (preg_match('/([0-9])+?/', $check) == 1)
		$strength = $strength + 0.6;

		if (preg_match('/([a-z])+?/', $check) == 1)
		$strength = $strength + 0.6;

		$strength = round($strength);

		if ($strength == 3)
		{
			echo '<span style="color: green; font-weight: bold;">&#10004;</span>';
			exit;
		} else
		if ($strength == 2)
		{
			echo '<span style="color: yellow; font-weight: bold;">&#10004;</span> <span style="font-size: 11px;">Medium Password.</span>';
			exit;
		} else
		{
			echo '<span style="color: red; font-weight: bold;">&#10004;</span> <span style="font-size: 11px;">Weak Password.</span>';
			exit;
		}
	}

	public function sidebar_status($id = '')
	{
		$id = intval($id);

		$this->registry->csrf_protection();

		if ($id == 1 && $this->registry->user['uid'] > 0)
		{

			$sidebar = $this->registry->db->prepare("
				UPDATE " . X_PREFIX . "members
				SET sidebar = '0'
				WHERE uid = :uid
			");
			$sidebar->execute(array(':uid' => $this->registry->user['uid']));

			echo "success";
		} else
		if ($id == 2 && $this->registry->user['uid'] > 0)
		{
			$this->registry->cleaner->clean('p', 'page', 'TYPE_STR');

			$current = unserialize($this->registry->user['sidebar_specific']);
			$current[] = $this->registry->cleaner->page;
			$current = serialize($current);

			$sidebar = $this->registry->db->prepare("
				UPDATE " . X_PREFIX . "members
				SET sidebar_specific = :sbs
				WHERE uid = :uid
			");
			$sidebar->execute(array(':sbs' => $current, ':uid' => $this->registry->user['uid']));

			echo "success2";
		} else
		{
			$this->registry->xmberror();
		}
	}

	public function post_rate($id = '')
	{
		if ($this->registry->options['thread_ratings'] == 0)
		exit;

		$id = intval($id);

		$this->registry->csrf_protection();

		$this->registry->cleaner->clean('p', 'postid', 'TYPE_INT');
		$postid = $this->registry->cleaner->postid;

		$raters = $this->registry->db->prepare("
			SELECT raters, parent FROM " . X_PREFIX . "posts
			WHERE pid = :postid
			Limit 1
		");
		$raters->execute(array(':postid' => $postid));
		$raters = $raters->fetch();

		$hasparent = $raters['parent'];
		$raters = unserialize($raters['raters']);
		$raters[] = $this->registry->user['username'];
		$raters = serialize($raters);

		if ($this->registry->user['uid'] > 0)
		{
			if ($id == 1)
			{ // POSITIVE RATING
				$update = $this->registry->db->prepare("
					UPDATE " . X_PREFIX . "posts
					SET rating = rating+1,
						raters = :raters
					WHERE pid = :pid
				");
				$update->execute(array(':raters' => $raters, ':pid' => $postid));

				echo "positive";
			} else
			{ // NEGATIVE RATING
				$this->registry->db->prepare("
					UPDATE " . X_PREFIX . "posts
					SET rating = rating-1,
						raters = :raters
					WHERE pid = :pid
				");
				$update->execute(array(':raters' => $raters, ':pid' => $postid));

				echo "negative";
			}
		}
	}

	public function namesuggest($id = '')
	{
		$this->registry->cleaner->clean('p', 'username', 'TYPE_STR');

		$username = $this->registry->cleaner->username;

		$username = explode(', ', trim($username));
		$total = count($username);

		$username = $username[$total - 1];

		if (strlen($username) < 2)
		exit;

		$namesquery = $this->registry->db->prepare("
			SELECT username FROM " . X_PREFIX . "members
			WHERE LOWER(username) LIKE :username
			LIMIT 5
		");
		$namesquery->execute(array(':username' => '%' . strtolower($username) . '%'));

		$namelist = '';
		foreach ($namesquery as $names)
		{
			$names['username'] = str_replace($username, '<span style="background: yellow;">' .
			$username . '</span>', $names['username']);
			$namelist .= '<li><a id="namesuggest">' . $names['username'] . '</a></li>';
		}

		echo $namelist;
	}

	public function post_stream_status($id = '') {
		$this->registry->cleaner->clean('p', 'status', 'TYPE_STR');
		$status = $this->registry->cleaner->status;

		if (!empty($status)) {
			if ($this->registry->user['uid'] > 0) {
				$updatestatus = $this->registry->db->prepare("
				UPDATE ".X_PREFIX."members
				SET status = :status
				WHERE username = :username
			");
				$updatestatus->execute(array(':status' => $status, ':username' => $this->registry->user['username']));

				$stream = Module::register('stream');

				if ($stream != false) {
					$streaminfo = array(
					'streamer'	=> $this->registry->user['username'],
					'type'		=> 'status',
					'params' 	=> array('dateline' => X_TIME, 'status' => $status)
					);
					$stream->addStream($streaminfo);
					
					$this->doRefreshStream($stream);
				} else {
					return false;
				}
			}
		}
	}

	public function doRefreshStream($stream) {
		if ($stream != false) {
			echo '<table class="xtable">';
			echo '<tr class="xrow">
     			  	<td class="xhead" colspan="2">
            			<input id="streaminput" size="100" type="text" class="input" style="float: left !important;" placeholder="Post a new status update..." />
       				</td>
    			</tr>';
			echo $stream->createStream();
			echo '</table>';
            
            return;
		}
	}
	
	public function refreshStream() {
		$stream = Module::register('stream');
		
		$this->doRefreshStream($stream);
	}
}

?>