<?php
class XMB_Registry {
	/**
	 *
	 * @var unknown_type
	 */
	public $user = array ();
	/**
	 *
	 * @var unknown_type
	 */
	public $debug = false;
	/**
	 *
	 * @var unknown_type
	 */
	public $config = array ();
	/**
	 *
	 * @var unknown_type
	 */
	public $page = '';
	/**
	 *
	 * @var unknown_type
	 */
	public $nocontainer = false;
	/**
	 *
	 * @var unknown_type
	 */
	public $version = array ();

	public function __construct() {

	}

	public function login() {
		if ($this->page != 'processlogin')
		$this->page = 'login';
		$this->nocontainer = true;
	}

	public function loadsettinggroups() {
		$settinggroups = $this->db->query ( "
			SELECT * FROM " . X_PREFIX . "setting_groups
			ORDER BY groupid ASC
		" );

		$return = array ();

		while ( $groups = $this->db->fetch_array ( $settinggroups ) ) {
			$return [$groups ['varname']] = $groups ['groupname'];
		}

		return $return;
	}

	public function loadsettings_group($settings) {
		$setting = $this->db->query ( "
			SELECT * FROM " . X_PREFIX . "settings
			WHERE settinggroup = '" . $settings . "'
			ORDER BY displayorder ASC
		" );

		return $setting;
	}
}

?>