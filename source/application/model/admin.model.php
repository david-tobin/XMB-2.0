<?php

class adminModel extends Model
{
	/**
	 * (non-PHPdoc)
	 * @see app/application/Model#index($id)
	 */
	public function index($id)
	{

	}

	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function login($id)
	{
		DEFINE('NO_HEAD', true);
	}

	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function processlogin($id)
	{
		DEFINE('NO_HEAD', true);

		$this->registry->cleaner->clean('p', 'username', 'TYPE_STR');
		$this->registry->cleaner->clean('p', 'password', 'TYPE_STR');

		$username = $this->registry->cleaner->username;
		$password = $this->registry->cleaner->password;
		
		include ('./application/datahandlers/login.datahandle.php');

		$login = new XMB_Datahandler_Login($this->registry);

		$login->set_info('username', $username);
		$login->set_info('password', $password);
		$login->set_info('remember', 0);
		$login->set_info('type', 'admin');

		$login->build();

		$this->registry->redirect('/admin/index/');
	}

	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function home($id)
	{

	}

	/**
	 * 
	 * @param unknown_type $id
	 */
	public function credits($id)
	{

	}

	/**
	 * 
	 * @param unknown_type $id
	 */
	public function about($id)
	{

	}

	/**
	 * 
	 * @param unknown_type $id
	 */
	public function settings_landing($id)
	{
		$settings = $this->registry->db->query("
			SELECT * FROM " . X_PREFIX . "setting_groups
			ORDER BY groupname ASC
		");
		$settings = $settings->fetchAll();

		$this->registry->view->setvar('setting_groups', $settings);
	}

	public function settings_view($id)
	{
		$groupid = $this->registry->urlquery['setting_group'];

		$settings = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "settings
			WHERE settinggroup = :groupid
		");
		$settings->execute(array(':groupid' => $groupid));

		$settings = $settings->fetchAll();

		foreach ($settings as $key => $setting)
		{
			$datatype = $setting['datatype'];
			$settings[$key]['input'] = $this->$datatype($setting['varname'], $setting['value']);
		}

		$this->registry->view->setvar('settings', $settings);
	}
	
	
	public function settings_update($id) {
		$this->registry->cleaner->clean ( 'p', 'settings', 'TYPE_ARRAY' );
		$this->registry->cleaner->clean ( 'p', 'settingname', 'TYPE_STR' );
		
		$settings 		= $this->registry->cleaner->settings;
		$settingname	= $this->registry->cleaner->settingname_db;
		
		$update = 'SET ';
		$skip = 1;
		foreach ( $settings as $key => $value ) {
			if ($skip == 2) {
				$key = str_replace ( '_db', '', $key );
				$skip = 0;
				
				$this->registry->db->query ( "
				UPDATE " . X_PREFIX . "settings
				SET value = '".$value."'
				WHERE varname = '".$key."'
			" );
			}
			$skip ++;
		}
		
		$this->registry->purge_cache('options');
		
		$this->registry->redirect('/admin/settings/');
	}
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function plugins($id)
	{

	}

	public function cache_view($id)
	{
		$cache_results = $this->registry->db->query("
			SELECT * FROM " . X_PREFIX . "cache
		");

		$cached = '';
		foreach ($cache_results as $cache)
		{
			$cache['status'] = (!empty($cache['data'])) ? '&10003;' : '&10008';
			$cache['data'] = unserialize($cache['data']);

			$this->registry->view->setvar('cache', $cache);

			$cached .= $this->registry->view->loadtovar('cache_results', 'admin');
		}

		$this->registry->view->setvar('cached', $cached);
	}

	public function cache_inspect($id)
	{
		$cacheid = $this->registry->urlquery['cacheid'];

		$cache_results = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "cache
			WHERE cacheid = ?
		");
		$cache_results->execute(array($cacheid));
		$cache = $cache_results->fetch();
		$cache['data'] = unserialize($cache['data']);

		$data = 'array ( <br /><br />';
		$i = 0;
		foreach ($cache['data'] as $key => $value)
		{
			$data .= '{' . $i . '}[' . $key . '] => ' . htmlspecialchars($value) .
				'<br /><br />';
			$i++;
		}
		$data .= ')';
		$cache['data'] = $data;

		$this->registry->view->setvar('cache', $cache);
	}

	// ############################################
	// ################ FORM INPUTS ###############
	// ############################################

	public function onoff($var, $value = '')
	{
		$one = ($value == 1) ? 'selected' : '';
		$two = ($value == 0) ? 'selected' : '';

		$return = '';

		$return .= '<select class="admin-input" name="settings[' . $var . ']">';
		$return .= '<option value="1" ' . $one . '>On</option>';
		$return .= '<option value="0" ' . $two . '>Off</option>';
		$return .= '</select>';

		return $return;
	}

	public function yesno($var, $value = '')
	{
		$one = ($value == 1) ? 'selected' : '';
		$two = ($value == 0) ? 'selected' : '';

		$return = '';

		$return .= '<select class="admin-input" name="settings[' . $var . ']">';
		$return .= '<option value="1" ' . $one . '>Yes</option>';
		$return .= '<option value="0" ' . $two . '>No</option>';
		$return .= '</select>';

		return $return;
	}

	public function free($var, $value = '')
	{
		return '<input class="admin-input" size="40" type="text" name="settings[' . $var .
			']" value="' . $value . '" />';
	}

	public function textarea($var, $value)
	{
		return '<textarea class="admin-input" cols="38" rows="5" name="settings[' . $var .
			']">' . $value . '</textarea>';
	}
}

?>