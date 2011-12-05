<?php

/**
 * BBCode Class
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_BBCode
{
	private $registry;
	/**
	 * Holds non-param tags
	 * @var array
	 */
	public $tags = array();
	/**
	 * Holds param tags
	 * @var array
	 */
	public $tagsparams = array();

	public function __construct($registry)
	{
		$this->registry = $registry;

		$this->load_bbcode();
	}

	/**
	 * Parses bbcode on the selected data
	 *
	 * @param string $content
	 * @param bool $params
	 *
	 * @return string
	 */
	public function parse($content, $params = 0)
	{
		$regtags = $regreplace = array();

		foreach ($this->tags as $key => $value)
		{
			$regtags[] = '/\[(' . $key . ')\](.*?)\[\/(' . $key . ')\]/is';
			$regreplace[] = $value;
		}

		foreach ($this->tagsparams as $key => $value)
		{
			$regtags[] = '/\[(' . $key . ')=(.*?)\](.*?)\[\/(' . $key . ')\]/is';
			$regreplace[] = $value;
		}

		$parsed = preg_replace($regtags, $regreplace, $content);

		return $parsed;
	}

	/**
	 * Loads BBCode from the database
	 */
	public function load_bbcode()
	{
		if ($this->registry->cache->get_cache('bbcode_tags') == false || $this->
			registry->cache->get_cache('bbcode_tagsparams') == false)
		{

			$bbcodequery = $this->registry->db->query("
			SELECT * FROM " . X_PREFIX . "bbcode
			WHERE bbactive = 1
		");

			foreach ($bbcodequery as $bbcode)
			{
				if ($bbcode['params'] == 0)
				{
					$this->tags[$bbcode['tag']] = $bbcode['replacement'];
				} else
				{
					$this->tagsparams[$bbcode['tag']] = $bbcode['replacement'];
				}
			}

			$this->registry->cache->rebuild_cache('bbcode_tags', $this->tags);
			$this->registry->cache->rebuild_cache('bbcode_tagsparams', $this->tagsparams);
		} else
		{
			$this->tags = $this->registry->cache->get_cache('bbcode_tags');
			$this->tagsparams = $this->registry->cache->get_cache('bbcode_tagsparams');
		}
	}

	/**
	 * Loads BBCode buttons for use with the WYSIWYG editor
	 */
	public function load_bbcode_buttons()
	{
		$bbcodequery = $this->registry->db->query("
			SELECT * FROM " . X_PREFIX . "bbcode
			WHERE bbactive = 1
		");

		$bbcode_buttons = array();
		$buttons = '';
		foreach ($bbcodequery as $bbcode)
		{
			if (!empty($bbcode['button_name']))
			{
				$bbcode_buttons[$bbcode['button_name']]['tag'] = $bbcode['tag'];
				$bbcode_buttons[$bbcode['button_name']]['image'] = $this->registry->options['site_url'] .
					'/images/bbcode.gif';
				$buttons = $bbcode['button_name'] . ',';

				if ($bbcode['params'] == 1)
				{
					$bbcode_buttons[$bbcode['button_name']]['params'] = 1;
				} else
				{
					$bbcode_buttons[$bbcode['button_name']]['params'] = 0;
				}
			}
		}
		$buttons = substr($buttons, 0, -1);

		$this->registry->view->setvar('buttons', $buttons);
		$this->registry->view->setvar('bbcode_buttons', $bbcode_buttons);

	}
}

?>