<?php

include_once '../module/user/User.Class.php';
/**
 *
 */
class Logout extends User {

    function __construct($dard) {
        parent::__construct($dard);
        $this->logout($dard);
    }

	private function update_user_autologin_cookie() {
		$cook = explode('|', $_COOKIE['d_utd']);
		$newcookie = $cook[0] . '|' . (time()-86400) . '|'. '+1';
		$params = array ($GLOBALS['cf_session_utd_cookie'], $newcookie);
		$this -> stmt('', $params, 'updateUserCookie', $params);
	}

    private function logout($dard) {
        $this -> update_user_autologin_cookie();
        $dard -> logout();
        $url = "http";
        if ($_SERVER["HTTPS"] == "on")
        	$url .= "s";
        $url .= "://" . $_SERVER["SERVER_NAME"] . "/";
        redirect($url);
    }

}

$logout = new Logout($dard);
?>