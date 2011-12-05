<?php

class XMB_Form
{
	/**
	 * HTML of the form to be created
	 * 
	 * @var string
	 */
	private $formhtml = '';

	private $registry;

	public function __construct($registry, $action)
	{
		$this->registry = &$registry;

		$this->formhtml = '<form action="' . $this->registry->options['site_url'] . '/' .
			$action . '" method="post">';
	}

	public function addElement($name = '', $type = 'text', $value = '', $class = '')
	{
		$this->formhtml .= '<input type="' . $type . '" name="' . $name . '" class="' .
			$class . '" value="' . $value . '" />';
	}

	public function build_form()
	{
		$this->formhtml .= '</form>';

		return $this->formhtml;
	}
}
