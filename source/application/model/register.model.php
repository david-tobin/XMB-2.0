<?php

class registerModel extends Model
{

	public function index($id)
	{
		$this->register();
	}

	public function register($id)
	{
		// If logged in then deny
		if ($this->registry->user['uid'] > 0)
		{
			$this->registry->xmberror('user_no_register');
		}

		// If registrations are off then deny
		if ($this->registry->options['registration_allow'] == 0)
		{
			$this->registry->xmberror('registration_off');
		}

		// reCaptcha
		require_once (X_PATH . '/application/classes/3rdparty/recaptchalib.php');
		$publickey = "6LexnwgAAAAAAFiEdXVkopET7xhbwe3si7G9_2Ws";

		$this->registry->view->setvar('recaptcha', recaptcha_get_html($publickey));

	}

	public function doregister($id)
	{
		$this->registry->loadphrases();

		if ($this->registry->options['registration_allow'] == 0)
		{
			$this->registry->xmberror('registration_off');
		} else
		{
			$this->registry->cleaner->clean('p', 'xmbusername', 'TYPE_STR');
			$this->registry->cleaner->clean('p', 'xmbemail', 'TYPE_STR');
			$this->registry->cleaner->clean('p', 'xmbpassword', 'TYPE_STR');
			$this->registry->cleaner->clean('p', 'xmbrepassword', 'TYPE_STR');

			$username = $this->registry->cleaner->xmbusername;
			$password = $this->registry->cleaner->xmbpassword;
			$repassword = $this->registry->cleaner->xmbrepassword;
			$email = $this->registry->cleaner->xmbemail;

			if ($this->registry->options['registration_recaptcha'] == 1)
			{
				require_once (X_PATH . '/application/classes/3rdparty/recaptchalib.php');

				$privatekey = "6LexnwgAAAAAAD7-Ed6xhsX0jLcm76RS2tU6-LP8";
				$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"],
					$_POST["recaptcha_response_field"]);

				if ($resp->is_valid == 0)
					$this->registry->xmberror('recaptcha_fail');
			}

			if (empty($username) || empty($password) || empty($email))
			{
				$this->registry->xmberror('reg_missing_info');
			}

			if (md5($password) !== md5($repassword))
			{
				$this->registry->xmberror('reg_password_mismatch');
			}

			require_once (X_PATH . '/application/datahandlers/user.datahandle.php');
			$user = new XMB_Datahandler_User($this->registry);

			$user->set_info('username', $username);
			$user->set_info('password', $password);
			$user->set_info('email', $email);

			$user->build();

			if ($this->registry->options['registration_welcome_pm_enable'] == 1)
			{
				require_once (X_PATH . '/application/datahandlers/pm.datahandle.php');
				$pm = new XMB_Datahandler_PM($this->registry);

				$subject = str_replace(array('{username}', '{sitename}'), array($username, $this->
					registry->options['site_name']), $this->registry->options['registration_welcome_pm_title']);
				$message = str_replace(array('{username}', '{sitename}'), array($username, $this->
					registry->options['site_name']), $this->registry->options['registration_welcome_pm_contents']);

				$pm->set_info('fromuser', $this->registry->options['registration_welcome_pm_fromuser']);
				$pm->set_info('recipient', array($username));
				$pm->set_info('subject', $subject);
				$pm->set_info('message', $message);

				$pm->build();
			}

			$this->registry->cron->force_run('stats');

			$this->registry->redirect('/register/complete/');
		}
	}

	public function complete($id)
	{
	}
}

?>