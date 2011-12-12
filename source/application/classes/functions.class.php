<?php

/**
 * XMB_Core
 *
 * @package XMB20x
 * @author David "DavidT" Tobin
 * @copyright 2010 The XMB Group
 * @version $Id$
 * @access public
 */
class XMB_Core extends XMB_Registry {
	/**
	 * XMB_Core::setcookie()
	 *
	 * @param mixed $cname
	 * @param mixed $cvalue
	 * @param mixed $ctime
	 * @return
	 */
	public function setcookie($cname, $cvalue, $ctime) {
		setcookie ( $cname, $cvalue, $ctime, '/' );
	}

	/**
	 * XMB_Core::loadsettings()
	 *
	 * @return
	 */
	public function loadsettings() {
		$optionquery = $this->db->query ( "SELECT * FROM " . X_PREFIX . "settings" );

		$returnoptions = array ();
		foreach ( $optionquery as $options ) {
			$varname = $options ['varname'];
			$returnoptions [$varname] = $options ['value'];
		}

		return $returnoptions;
	}
	
	public function purge_cache($id) {
		if ($id) {
			$purge = $this->db->prepare("
				DELETE FROM " . X_PREFIX . "xmb_cache
				WHERE cacheid = ?
			");
			
			$purge->execute(array($id));
		}
	}

	/**
	 * XMB_Core::revert_magic()
	 *
	 * @param mixed $value
	 * @param integer $depth
	 * @return
	 */
	private function revert_magic(&$value, $depth = 0) {
		if (is_array ( $value )) {
			foreach ( $value as $key => $val ) {
				if (is_string ( $val )) {
					$value ["$key"] = stripslashes ( $val );
				} else if (is_array ( $val ) and $depth < 10) {
					$this->revert_magic ( $value ["$key"], $depth + 1 );
				}
			}
		}
	}

	/**
	 * XMB_Core::remove_magic()
	 *
	 * @return
	 */
	public function remove_magic() {
		$this->revert_magic ( $_POST );
		$this->revert_magic ( $_COOKIE );
		$this->revert_magic ( $_GET );
		$this->revert_magic ( $_REQUEST );

		@ini_set ( 'magic_quotes_sybase', 0 );
	}

	/**
	 * XMB_Core::trigger_error()
	 *
	 * @param mixed $error
	 * @return
	 */
	public function trigger_error($error) {
		if ($this->debug == true) {
			die ( $error );
		} else {
			die ( 'Error encountered while loading this page. <br />Please set debug to true in config.php to view this error.' );
		}
		exit ();
	}

	/**
	 * XMB_Core::gentime()
	 *
	 * @return
	 */
	public function gentime() {
		$endtime = microtime ( true );

		$gentime = round ( $endtime - X_START, 5 );

		return $gentime;
	}

	/**
	 * XMB_Core::loadhooks()
	 *
	 * @return
	 */
	public function loadhooks() {
		if (isset ( $this->controller )) {
			if ($this->cache->get_cache ( 'plugins' ) === false) {
				$plugins = $this->db->prepare ( "SELECT * FROM " . X_PREFIX . "plugins WHERE pluginactive = true AND pluginlocation = ? OR pluginlocation = 'global' ORDER BY pluginid ASC" );
				$plugins->execute ( array ($this->controller ) );

				if ($plugins->rowCount () > 0) {
					foreach ( $plugins as $plugin ) {
						if (isset ( $plugin ['pluginhook'] ) && isset ( $plugin ['plugincode'] )) {
							if (empty ( $this->hookdata [$plugin ['pluginhook']] )) {
								$this->hookdata [$plugin ['pluginhook']] = $plugin ['plugincode'];
							} else {
								$this->hookdata [$plugin ['pluginhook']] .= $plugin ['plugincode'];
							}
						}
					}
				} else {
					$this->hookdata = array ();
				}
				$this->cache->rebuild_cache ( 'plugins', $this->hookdata );
			} else {
				$this->hookdata = $this->cache->get_cache ( 'plugins' );
			}
		}
	}

	/**
	 * XMB_Core::fetch_hook()
	 *
	 * @param mixed $hookname
	 * @return
	 */
	public function fetch_hook($hookname) {
		if (isset ( $this->hookdata [$hookname] )) {
			return $this->hookdata [$hookname];
		} else {
			return false;
		}
	}

	/**
	 * XMB_Core::xmbtime()
	 *
	 * @param mixed $format
	 * @param mixed $converttime
	 * @param integer $today
	 * @return
	 */
	public function xmbtime($format, $converttime, $detailed = 1) {
		if (empty ( $format ) || empty ( $converttime ))
		return 'Never';
		date_default_timezone_set ( 'Europe/London' );

		$returntime = date ( $format, $converttime );
		
		if ($detailed == 1 && ((X_TIME - $converttime) < 60)) {
			$returntime = 'A few seconds ago';
		} else if ($detailed == 1 && (X_TIME - $converttime) > 60 && (X_TIME - $converttime) < (10*60)) {
			$returntime = 'A few minutes ago';
		} else if ($detailed == 1 && (X_TIME - $converttime) < (24 * 60 * 60)) {
			$returntime = 'Today at ' . date('h:i a', $converttime);
		} else if ($detailed == 1 && (X_TIME - $converttime) > (24 * 60 * 60) && (X_TIME - $converttime) < (48 * 60 * 60)) {
			$returntime = 'Yesterday at' . date('h:i a', $converttime);
		}

		if ($converttime == 0) {
			$returntime = 'Never';
		}

		return $returntime;
	}

	/**
	 * XMB_Core::createsession()
	 *
	 * @param mixed $userinfo
	 * @param string $type
	 * @return
	 */
	public function createsession($userinfo, $type = 'user') {
		$sessioncount = $this->db->prepare ( "
		SELECT * FROM " . X_PREFIX . "session
		WHERE username = :username
		" );
		$sessioncount->execute ( array (':username' => $userinfo ['username'] ) );

		if ($sessioncount->rowCount () == 0) {
			// Remove our guest session
			$purgeguest = $this->db->prepare ( "
				DELETE FROM " . X_PREFIX . "session
				WHERE ipaddress = :ipaddress
			" );
			$purgeguest->execute(array(':ipaddress' => $_SERVER['REMOTE_ADDR']));
			
			$sess = $this->db->prepare ( "
				INSERT INTO " . X_PREFIX . "session (username, userid, ipaddress, lastactive, location, sesstype) 
				VALUES (?, ?, ?, '" . X_TIME . "', ?, ?)
			" );

			if ($type == 'user') {
				if ($userinfo ['uid'] > 0) {
					$sess->execute ( array ($userinfo ['username'], $userinfo ['uid'], $_SERVER ['REMOTE_ADDR'], $this->controller, $userinfo ['type'] ) );
				} else {
					$sess->execute ( array ('Guest', 0, $_SERVER ['REMOTE_ADDR'], $this->controller, '' ) );
				}
			} else if ($type == 'admin') {
				if ($userinfo ['uid'] > 0) {
					$sess->execute ( array ($userinfo ['username'], $userinfo ['uid'], $_SERVER ['REMOTE_ADDR'] . '.00', 'admin', $userinfo ['type'] ) );
				}
			}
		} else {
			$sess = $this->db->prepare("
				UPDATE ".X_PREFIX."session
				SET lastactive = :timenow
			");
			$sess->execute(array(':timenow' => X_TIME));
		}
	}

	/**
	 * XMB_Core::in_admin()
	 *
	 * @return
	 */
	public function in_admin() {
		if ($this->in_admin == true) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * XMB_Core::loadmodel()
	 *
	 * @param mixed $model
	 * @param mixed $action
	 * @param mixed $id
	 * @return
	 */
	public function loadmodel($model, $action, $id) {
		$loadmodel = X_PATH . "/application/model/" . $model . ".model.php";
		$modelname = $model . "Model";
        
        if (!class_exists($modelname)) {
            include ($loadmodel);
        }
        
		$model = new $modelname ( $this );

		$model->$action ( $id );
	}
    
    public function load_class($classname) {
        if (file_exists('./application/classes/' . $classname . '.class.php')) {
            require_once('./application/classes/' . $classname . '.class.php');
        }
    }

	/**
	 * XMB_Core::generate_securitytoken()
	 *
	 * @return
	 */
	public function generate_securitytoken() {
		$token = 'xmb_' . md5 ( $this->user ['uid'] * 48 . rand ( 1000, 10000 ) );

		return $token;
	}

	/**
	 * XMB_Core::csrf_protection()
	 *
	 * @return
	 */
	public function csrf_protection() {
		$this->cleaner->clean ( 'p', 'securitytoken', 'TYPE_STR' );

		$securitytoken = (isset ( $this->cleaner->cleaned ['securitytoken'] ) ? $this->cleaner->cleaned ['securitytoken'] : false);

		if (isset ( $securitytoken ) && $securitytoken != false) {
			if ($securitytoken != $this->user ['securitytoken']) {
				//$this->xmberror ( 'invalid_token' );
			}
		} else {
			//$this->xmberror ( 'invalid_token' );
		}
	}

	/**
	 * XMB_Core::redirect()
	 *
	 * @param mixed $url
	 * @return
	 */
	public function redirect($url) {
		header ( 'Location: ' . $this->options ['site_url'] . $url );
	}

	/**
	 * XMB_Core::loadphrases()
	 *
	 * @param mixed $phrases
	 * @return
	 */
	public function loadphrases($phrases = array()) {
		if ($this->cache->get_cache ( 'phrases' ) == false) {
			$phrasequery = $this->db->query ( "
				SELECT * FROM " . X_PREFIX . "phrases
			" );

			if ($phrasequery->rowCount () > 0) {
				foreach ( $phrasequery as $phrases ) {
					$this->phrases [$phrases ['varname']] = $phrases ['value'];
				}
			}
			$this->cache->rebuild_cache ( 'phrases', $this->phrases );
		} else {
			$this->phrases = $this->cache->get_cache ( 'phrases' );
		}
	}

	/**
	 * XMB_Core::geturlquery()
	 *
	 * @return
	 */
	public function geturlquery() {
		$url = $_SERVER ["REQUEST_URI"];
		$url = parse_url ( htmlspecialchars ( $url ) );

		if (isset ( $url ['query'] )) {
			$query = explode ( '&', $url ['query'] );

			foreach ( $query as $key => $value ) {
				$separate = explode ( '=', $value );
				if (! isset ( $separate [1] ))
				$separate [1] = '';

				$this->urlquery [$separate [0]] = urldecode ( $separate [1] );
			}
		}
	}

	/**
	 * XMB_Core::xmberror()
	 *
	 * @param mixed $phrase
	 * @return
	 */
	public function xmberror($phrase) {
		if (! empty ( $phrase )) {
			$phrases = array ();
			$this->loadphrases ();

			if (isset ( $this->phrases [$phrase] )) {
				$this->view->setvar ( 'error', $this->phrases [$phrase] );
			} else {
				$this->view->setvar ( 'error', $this->phrases ['unknown_error'] );
			}

			$this->construct_navbits ( array ('' => 'Error' ) );

			$this->view->loadview ( 'error', 'misc', 'Error' ); // PRINT PAGE
			exit ();
		}
	}

	/**
	 * XMB_Core::construct_navbits()
	 *
	 * @param mixed $navbits
	 * @return
	 */
	public function construct_navbits($navbits = array()) {
		$navhtml = '&bull; <a href=' . $this->options ['site_url'] . '>' . $this->options ['site_name'] . '</a>';

		if (is_array ( $navbits )) {
			foreach ( $navbits as $link => $name ) {
				if (! empty ( $link ))
				$link = $this->options ['site_url'] . $link;

				if (empty ( $link )) {
					$navhtml .= ' &rarr; ' . $name;
				} else {
					$navhtml .= ' &rarr; <a href="' . $link . '">' . $name . '</a>';
				}
			}
		}

		$this->navbits = $navhtml;
	}

	/**
	 * XMB_Core::no_guest()
	 *
	 * @return
	 */
	public function no_guest() {
		if ($this->user ['uid'] == 0) {
			$this->xmberror ( 'guest_no_access' );
		}
	}

	/**
	 * XMB_Core::pagination()
	 *
	 * @param mixed $count
	 * @param mixed $url
	 * @param integer $range
	 * @return
	 */
	public function pagination($count, $url, $range = 2) {
		$page = (! empty ( $this->urlquery ['page'] )) ? intval ( $this->urlquery ['page'] ) : 1;
		$perpage = (! empty ( $this->urlquery ['perpage'] )) ? intval ( $this->urlquery ['perpage'] ) : 20;

		$html = '';
		$totalpages = ceil ( $count / $perpage );
	
		if ($totalpages == 0) {
			$totalpages = 1; // Will always have atleast 1 page.
		}
		
		$html .= '<span class="smallfont">Page ' . $page . ' of ' . $totalpages . '&nbsp;</span>';

		if ($page != 1)
		$html .= '<span class="pagination"><a href="' . $url . '?page=1&amp;perpage=' . $perpage . '">1</a></span>... ';

		if ($page > $totalpages)
		$page = $totalpages;

		for($i = $page - $range; $i < ($page + $range) + 1; $i ++) {
			if ($i > 0 && $i <= $totalpages && $totalpages != 1) {
				if ($i == $page) {
					$html .= '<span style="border: none; padding: 5px;">' . $i . '</span>';
				} else {
					$html .= '<span class="pagination"><a href="' . $url . '?page=' . $i . '&amp;perpage=' . $perpage . '">' . $i . '</a></span>';
				}
			}
		}
		
		if ($page != $totalpages)
		$html .= '... <span class="pagination"><a href="' . $url . '?page=' . $totalpages . '&amp;perpage=' . $perpage . '">' . $totalpages . '</a></span>';

		return $html;
	}

	/**
	 * XMB_Core::seolink()
	 *
	 * @param mixed $id
	 * @param mixed $wordstring
	 * @return
	 */
	public function seolink($id, $wordstring) {
		$stopwords = explode ( '<br />', nl2br ( strtolower ( $this->options ['seo_stopwords'] ) ) );
		$symbols = array ('!', '$', '%', '^', '&', '*', '(', ')', '_', '-', '+', '=', '[', '{', '}', ']', '@', ';', '?', '>', '<', ':' );

		$keywords = explode ( ' ', $wordstring );
		$keywords = str_replace ( $symbols, '', $keywords );
		$keywords = str_replace ( $stopwords, '', $keywords );
		$keywords = strtolower ( implode ( '-', $keywords ) );
		if (! empty ( $keywords ))
		$keywords = '-' . $keywords;
		$seolink = trim ( $id . $keywords );

		return $seolink;
	}

	/**
	 * XMB_Core::construct_urls()
	 *
	 * @param mixed $urls
	 * @return
	 */
	public function construct_urls($urls = array()) {
		$this->options ['seo_urlformat'] = 1;
		if (empty ( $urls ) || ! is_array ( $urls ) || empty ( $this->options ['seo_urlformat'] )) {
			return false;
		} else {
			$returnurls = array ();
			foreach ( $urls as $urlid => $url ) {
				$url = explode ( '/', $url );

				$controller = (isset ( $url [0] )) ? $url [0] : '';
				$action = (isset ( $url [1] )) ? $url [1] : '';
				$id = (isset ( $url [2] )) ? $url [2] : '';

				$urlformat = $this->options ['seo_urlformat'];

				if ($urlformat == 1) {
					$returnurls [$urlid] = 'index.php?control=' . $controller . '&amp;action=' . $action . '&amp;id=' . $id;
				} else if ($urlformat == 2) {
					if (empty ( $action )) {
						$urlstring = $controller . '/';
					} else if (empty ( $id ) && ! empty ( $action )) {
						$urlstring = $controller . '/' . $action . '/';
					} else {
						$urlstring = $controller . '/' . $action . '/' . $id;
					}

					$returnurls [$urlid] = $urlstring;
				}
			}

			$this->view->setvar ( 'xmburl', $returnurls );

			return true;
		}
	}

	public function modules_init() {
		if ($this->cache->get_cache('modules') == false) {
			$modules = $this->db->prepare("
				SELECT * FROM " . X_PREFIX . "modules
				WHERE moduleactive = 1
			");

			$modules->execute();

			$moduleset = array();
			foreach($modules AS $module) {
				$moduleset[$module['moduleid']] = $module;
			}
			$this->cache->rebuild_cache('modules', $moduleset);
		}
		$this->registry->modules = $this->cache->get_cache('modules');
	}
}
