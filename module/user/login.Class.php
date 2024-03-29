<?php
include_once '../module/user/User.Class.php';

/**
 *
 */
class login extends User {

	private $mesage = array();

	function __construct($dard, $tag) {
		parent::__construct($dard);
		$this -> login_user($dard);
	}

	private function check_error_msg() {
		if (count($this -> errors) > 0) {
			$this -> is_error = TRUE;
			return TRUE;
		}
	}

	private function check_inputs($email, $pass, $captcha) {
		$this -> clean_login($email, $pass);
		$this -> clean_captcha($captcha);
	}

	private function insert_failed_login_ip() {

	}

	private function check_failed_ip_number() {

	}

	private function failed_login($dard) {
		array_push($this -> errors, 4);
		$this -> retrive_error_msg($dard, array('main', 'login'));
		$this -> is_error = TRUE;
	}

	private function check_user_login_credentials($dard) {
		isset($_POST['email']) ? $email = $_POST['email'] : $email = '';
		$cred = $this -> stmt('', array($email), 'userLogin', array($email));
		if ($cred === NULL || !password_verify($_POST['password'], $cred['password'])) {
			return FALSE;
		} else {
			return $cred;
		}
	}

	private function login_user($dard) {
		if ($this -> post) {
			$this -> check_inputs($_POST['email'], $_POST['password'], $_POST['captcha']);

			//here some more filtering functions would have to come

			if ($this -> check_error_msg()) {
				$this -> retrive_error_msg($dard, array('main', 'login'));
			} else {
				$is_user = $this -> check_user_login_credentials($dard);
				if (!$is_user) {
					$this -> failed_login($dard);
				} else {
					$this -> set_session($is_user);
					$_SERVER["HTTPS"] == "on" ? $http = "https://" : $http = "http://";
					empty($dard -> cf_login_redirect) ? $re_direct = $_SERVER["SERVER_NAME"] : $re_direct = $dard -> cf_login_redirect ;
					$query_params = array($_SESSION['user_id'], $GLOBALS['cf_session_utd_cookie']);
					$newcook = $this -> stmt('', $query_params, 'updateUserCookieAtLogin', $query_params);
					redirect($http . $re_direct);
				}
			}
		}
	}

	private function startSession() {
		if (session_status() === 1) {
			include '../../lib/DardSession.Class.php';
			$_DARDSESSI = new DardSession();
			return $_DARDSESSI;
		}
	}

	private function set_session($user) {
		if (!isset($_DARDSESSION))
			$_DARDSESSION = $this -> startSession();
		if ($user['firstname'] === NULL) {
			$email = $user['email'];
			$username = substr_replace($email, '', strpos($email, '@'));
		} else {
			$username = $user['firstname'];
		}
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['user_name'] = $username;
		$_SESSION['user_loged'] = TRUE;
	}

}
?>