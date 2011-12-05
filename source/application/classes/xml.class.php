<?php

/**
 * XMB XML Handler
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2010 XMB Group
 * @Licence GPL 3.0
 */
class XMB_XML
{

	private $registry;

	private $parser;

	public $attr = array();

	public $value = array();

	private $url = '';

	public function __construct($registry, $url)
	{
		$this->registry = $registry;
		

		$this->url = $url;
		$this->parse();
	}

	private function parse()
	{
		$xml = '';

		$this->parser = xml_parser_create("UTF-8");

		xml_set_object($this->parser, $this);

		if (!($fp = @fopen($this->url, 'r')))
		{
			$this->error("Cannot open $this->url");
		}

		while (!feof($fp))
		{
			$xml .= trim(fread($fp, 8192));
			echo fread($fp, 8192);
		}

		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);

		xml_parse_into_struct($this->parser, $xml, $values);

		xml_parser_free($this->parser);

		foreach ($values as $key => $value)
		{
			if (isset($value['attributes']))
			{
				$attrs = array();
				foreach ($value['attributes'] as $attrkey => $attrval)
				{
					$attrs[$this->registry->db->escape_string($attrkey)] = $this->registry->db->
						escape_string($attrval);
				}
			}

			$value['tag'] = $this->registry->db->escape_string($value['tag']);

			if ($value['type'] == 'open')
			{
				$this->value[$value['tag']] = isset($value['value']) ? $this->registry->db->
					escape_string($value['value']) : '';
				$this->attr[$value['tag']] = isset($attrs) ? $attrs : '';
			} else
				if ($value['type'] == 'complete')
				{
					$this->value[$value['tag']][] = isset($value['value']) ? $this->registry->db->
						escape_string($value['value']) : '';
					$this->attr[$value['tag']][] = isset($attrs) ? $attrs : '';
				}
		}
	}

	private function error($msg)
	{
		echo "<div align=\"center\">
            <font color=\"red\"><b>Error: $msg</b></font>
            </div>";
		exit();
	}
}

?>