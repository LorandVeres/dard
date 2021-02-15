<?php
include_once '../module/user/User.Class.php';

/**
 *
 */
class login extends User {

	private $mesage = array();

	function __construct($myPage, $tag) {
		parent::__construct($myPage);
		$this -> login_user($myPage);
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

	private function failed_login($myPage) {
		array_push($this -> errors, 4);
		$this -> retrive_error_msg($myPage, array('main', 'login'));
		$this -> is_error = TRUE;
	}

	private function check_user_login_credentials($myPage) {
		isset($_POST['email']) ? $email = $_POST['email'] : $email = '';
		$query = "SELECT `id`, `email`, `password`, `u_group`, `firstname` FROM `user` WHERE `email` = '$email';";
		$cred = $myPage -> selectDB($email, $query, TRUE, 'array');
		if ($cred === NULL || !password_verify($_POST['password'], $cred['password'])) {
			return FALSE;
		} else {
			return $cred;
		}
	}

	private function login_user($myPage) {
		if ($this -> post) {
			$this -> check_inputs($_POST['email'], $_POST['password'], $_POST['captcha']);

			//here some more filtering functions would have to come

			if ($this -> check_error_msg()) {
				$this -> retrive_error_msg($myPage, array('main', 'login'));
			} else {
				$is_user = $this -> check_user_login_credentials($myPage);
				if (!$is_user) {
					$this -> failed_login($myPage);
				} else {
					$this -> set_session($is_user);
					$_SERVER["HTTPS"] == "on" ? $http = "https://" : $http = "http://";
					empty($myPage -> cf_login_redirect) ? $myPage -> cf_login_redirect = $_SERVER["SERVER_NAME"] : '';
					redirect($http . $myPage -> cf_login_redirect);
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
		$_SESSION['user_priv'] = $user['u_group'];
	}

}
?>