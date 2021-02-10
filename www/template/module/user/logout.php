<?php

include_once '../module/user/User.Class.php';
/**
 *
 */
class Logout extends User {

    function __construct($config, $DBconect, $_DARDSESSI, $myPage) {
        $this->logout($config, $DBconect, $_DARDSESSI, $myPage);
    }

    private function logout($config, $DBconect, $_DARDSESSI, $myPage) {
        $_DARDSESSI -> logout($config, $DBconect);
        $url = "http";
        if ($_SERVER["HTTPS"] == "on")
        	$url .= "s";
        $url .= "://" . $_SERVER["SERVER_NAME"] . "/";
        redirect($url);
    }

}

$logout = new Logout($config, $DBconect, $_DARDSESSI, $myPage);
?>