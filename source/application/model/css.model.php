<?php

class cssModel extends Model
{
	/**
	 * (non-PHPdoc)
	 * @see app/application/Model#index($id)
	 */
	public function index($id)
	{

	}

	public function load($id)
	{
		$controller = $this->registry->urlquery['css'];

		$cssquery = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "css
			WHERE styleid = :id AND (controller = :controller OR controller = 'global')
			ORDER BY cssid ASC
		");

		$cssquery->execute(array(':id' => $id, ':controller' => $controller));

		$outcss = '@charset "UTF-8";
';
		$outcss .= '/** XMB Generated CSS: ' . $controller . ' **/
		
';

		foreach ($cssquery as $css)
		{
			$outcss .= $css['contents'] . '
			
			';
		}
		$outcss = $this->parse_css($outcss);

		header("Content-Type: text/css");
		echo $outcss;
	}

	private function parse_css($css)
	{
		$css = preg_replace('/\{url\}/is', $this->registry->options['site_url'], $css);

		return $css;
	}
}

?>