<?php

include_once '../module/user/User.Class.php';
/**
 *
 */
class Logout extends User {

    function __construct($myPage) {
        $this->logout($myPage);
    }

    private function logout($myPage) {
        $myPage -> logout();
        $url = "http";
        if ($_SERVER["HTTPS"] == "on")
        	$url .= "s";
        $url .= "://" . $_SERVER["SERVER_NAME"] . "/";
        redirect($url);
    }

}

$logout = new Logout($myPage);
?>