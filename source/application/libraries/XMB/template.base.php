<?php

class Template
{

	private $registry;

	private $vars = array();

	private $temps = array();

	public $css = '';

	public $styleid = -1;

	public $mod_templates = array();

	public function __construct($registry)
	{
		if (!defined('IN_CODE'))
		{
			exit('Not allowed to call this file directly');
		}

		$this->registry = &$registry;
	}

	public function setvar($varname, $value, $template = 0)
	{
		if ($template != 1)
		{
			$this->vars[$varname] = $value;
		} else
		{
			$this->temps[$varname] = $value;
		}
	}

	private function fetch_var_clean($varname)
	{
		if (isset($this->vars[$varname[1]][$varname[2]]) && !is_array($this->vars[$varname[1]][$varname[2]]))
		{
			return htmlspecialchars($this->vars[$varname[1]][$varname[2]]);
		} else
			if (isset($this->vars[$varname[1]]) && !is_array($this->vars[$varname[1]]))
			{
				return htmlspecialchars($this->vars[$varname[1]]);
			}
	}

	private function fetch_var_noclean($varname)
	{
		if (isset($this->vars[$varname[1]]) && !is_array($this->vars[$varname[1]]))
		{
			return $this->vars[$varname[1]];
		} else
			if (isset($this->vars[$varname[1]][$varname[2]]) && !is_array($this->vars[$varname[1]][$varname[2]]))
			{
				return $this->vars[$varname[1]][$varname[2]];
			}
	}

	private function fetch_phrase($phrase)
	{
		if (isset($this->registry->phrases[$phrase[1]]) && !empty($this->registry->
			phrases[$phrase[1]]))
		{
			return $this->registry->phrases[$phrase[1]];
		}
	}

	private function fetch_userinfo($varname)
	{
		if (isset($this->registry->user[$varname[1]]) && !empty($this->registry->user[$varname[1]]))
		{
			return $this->registry->user[$varname[1]];
		}
	}

	private function fetch_option($option)
	{
		if (isset($this->registry->options[$option[1]]) && !empty($this->registry->
			options[$option[1]]))
		{
			return $this->registry->options[$option[1]];
		}
	}

	private function fetch_url($option)
	{
		$url = explode('/', $option[1]);
		$controller = isset($url[0]) ? $url[0] : false;
		$model = isset($url[1]) ? $url[1] : false;
		$id = isset($url[2]) ? $url[2] : false;
		$this->registry->options['seo_urltype'] = 1;
		$returnurl = '';
		if ($this->registry->options['seo_urltype'] == 1) {
			if ($controller != false) $returnurl .= $controller . '/';
			if ($model != false) $returnurl .= $model . '/';
			if ($id != false) $returnurl .= $id . '/';
		} else {
			$returnurl = 'index.php?';
			if ($controller != false) $returnurl .= 'control=' . $controller;
			if ($model != false) $returnurl .= '&amp;action=' . $model;
			if ($id != false) $returnurl .= '&amp;id=' . $id;
		}
		
		return $returnurl;
	}

	private function xmb_foreach($varname)
	{
		if (isset($this->vars[$varname[1]]) && is_array($this->vars[$varname[1]]))
		{
			$newcode = '';
			foreach ($this->vars[$varname[1]] as $key => $value)
			{
				if (isset($varname[4]))
					$this->setvar($varname[4], $value);
				$this->setvar($varname[2], $key);

				$newcode .= $this->varify($varname[5], true);
			}

			return $newcode;
		}
	}
	
	private function xmb_if($varname) //BUG: NOT WORKING
	{
		eval("\$result = (\$varname[1]) ? true : false;");
		
		if ($result)
		{
			//return $varname[2];
		} else
		{
			return false;
		}
	}

	private function varify($content, $noforeach = false)
	{
		$content = preg_replace_callback('#\{xmb:if "(.*)?"\}(.*)?{/xmb:if\}#ism', array
			($this, 'xmb_if'), $content);
		$content = preg_replace_callback('#\{xmb:foreach ([a-zA-Z0-9_]*)? AS ([a-zA-Z0-9_]*)( => )?([a-zA-Z0-9_]*)?\}(.*)?\{/xmb:foreach\}#ism',
			array(&$this, 'xmb_foreach'), $content);
		$content = preg_replace_callback('#\{xmb:var ([a-zA-Z0-9_]*)\.?([A-Za-z0-9_]*)?\}#is',
			array(&$this, 'fetch_var_clean'), $content);
		$content = preg_replace_callback('#\{xmb:html ([a-zA-Z0-9_]*)\.?([A-Za-z0-9_]*)?\}#is',
			array(&$this, 'fetch_var_noclean'), $content);
		$content = preg_replace_callback('#\{xmb:phrase ([a-zA-Z0-9_]*)?\}#is', array(&
			$this, 'fetch_phrase'), $content);
		$content = preg_replace_callback('#\{xmb:user ([a-zA-Z0-9_]*)?\}#is', array(&$this,
			'fetch_userinfo'), $content);
		$content = preg_replace_callback('#\{xmb:option ([a-zA-Z0-9_]*)?\}#is', array(&
			$this, 'fetch_option'), $content);
		$content = preg_replace_callback('#\{xmb:url ([a-zA-Z0-9_/]*)?\}#is', array(&$this,
			'fetch_url'), $content);

		return $content;
	}

	public function loadview($view, $controller, $title = "")
	{
	   if (empty($title)) {
	       $title = ucfirst($this->registry->controller);
	   }
       
		// while(@ob_end_clean());
		ob_start();
		$this->loadheader($title);
		$this->loadfooter();
		if (file_exists(X_PATH . '/application/views/' . $controller . '/' . $view .
			'.view.php') == false)
		{
			throw new Exception('Template: ' . $view . ' not found.');
		}

		$generation = $this->registry->gentime();
		$xmb['gentime'] = $generation;

		$version = $this->registry->version;
		$generation = $this->registry->gentime();
		$xmb['gentime'] = $generation;
		$xmb['loginstatus'] = $this->registry->loginstatus;

		ob_start();
		require (X_PATH . '/application/views/' . $controller . '/' . $view .
			'.view.php');
		$content = ob_get_contents();
		ob_end_clean();

		$content = $this->varify($content);

		echo $content;
	}

	public function loadtovar($template, $controller)
	{
		if (isset($this->vars))
		{
			foreach ($this->vars as $index => $value)
			{
				$$index = $value;
			}
		}
		
		if (isset($this->registry->user))
		{
			foreach ($this->registry->user as $index => $value)
			{
				$user[$index] = $value;
			}
		}
		/*
		if (isset($this->registry->options))
		{
			foreach ($this->registry->options as $index => $value)
			{
				$options[$index] = $value;
			}
		}

		if (isset($this->registry->phrases))
		{
			foreach ($this->registry->phrases as $key => $value)
			{
				$phrases[$key] = $value;
			}
		}*/

		$version = $this->registry->version;
		$generation = $this->registry->gentime();
		$xmb['gentime'] = $generation;
		$xmb['loginstatus'] = $this->registry->loginstatus;
		$this->setvar('xmb', $xmb);

		ob_start();
		include (X_PATH . '/application/views/' . $controller . '/' . $template .
			'.view.php');

		$output = ob_get_contents();
		ob_end_clean();

		$output = $this->varify($output);

		return $output;
	}

	public function loadcss($css)
	{ // FURTHER REVISION NEEDED
		$this->css = $css;
	}

	public function loadheader($title)
	{
		$reglink = $loglink = $guestnotice = $notices = $topnotice = '';

		if (!empty($this->registry->notice))
		{
			$notice = $this->registry->notice;
			$this->registry->view->setvar('notice', $notice);

			if (in_array($this->registry->user['uid'], explode(',', $this->registry->config['superadmins'])))
			{
				$notices = $this->registry->view->loadtovar('notice', 'global');
			}
		}

		// $this->registry->view->setvar('loglink', $loglink);
		$this->registry->view->setvar('notices', $notices);
		$this->registry->view->setvar('guestnotice', $guestnotice);
		$this->registry->view->setvar('current', $this->registry->controller);

		$this->registry->view->loadcss('main'); // REQUIRES REVISION
		$this->registry->view->setvar('title', $title . ' - ' . $this->registry->
			options['site_name']); // REQUIRES REVISION


		if ($this->registry->controller == 'admin')
		{
			$header = $this->registry->view->loadtovar('header', 'admin');
			$this->registry->view->setvar('header', $header);
		} else
		{
			$sidebar = $this->registry->view->loadtovar('sidebar', 'global');
			$this->registry->view->setvar('sidebar', $sidebar);

			$header = $this->registry->view->loadtovar('header', 'global');
			$this->registry->view->setvar('header', $header);
		}
	}

	public function loadfooter()
	{
		$errors = '';
		if (!empty($this->registry->errors))
			$errors .= '<div class="head">Errors</div><table class="xtable">';
		if (!empty($this->registry->errors))
		{
			foreach ($this->registry->errors as $key => $value)
			{
				$errors .= $value;
			}
		}
		if (!empty($this->registry->errors))
			$errors .= '</table><div class="foot"></div>';

		$this->registry->view->setvar('errors', $errors);

		if ($this->registry->controller == 'admin')
		{
			$footer = $this->registry->view->loadtovar('footer', 'admin');
		} else
		{
			$footer = $this->registry->view->loadtovar('footer', 'global');
		}

		$this->registry->view->setvar('footer', $footer);
	}

	public function debug()
	{
		if ($this->registry->config['debug'] == 1)
		{
			echo '<br /><br /><div class="debug">';
			echo 'Debug: <br /><br />';

			echo 'Queries: <br />';
			foreach ($this->registry->db->querys as $key => $value)
			{
				echo '<span class="querys">' . $value . '</span><br />';
			}

			echo '<br />Permissions: <br />';
			$i = 1;
			foreach ($this->registry->perm->perms as $key => $value)
			{
				echo $key . " - " . $value;

				if ($i == 2)
				{
					echo "<br />";
					$i = 0;
				} else
				{
					echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
				}
				$i++;
			}

			echo "</div>";
		}
	}
}

?>