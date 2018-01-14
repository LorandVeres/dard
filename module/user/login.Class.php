<?php
include_once '../module/user/User.Class.php';

include_once '../config.php';
$config = new DardConfig();


include_once '../lib/dbConect.Class.php';
$DBconect = new dbConect();

include_once '../lib/simpleTag.Class.php';
$tag = new simpleTag($config, $DBconect);
/**
 *
 */
class login extends User {

    private $mesage = array();
    private $is_error = FALSE;

    function __construct($config, $DBconect, $tag, $myPage) {
        parent::__construct($config, $DBconect);
        $this -> login_user($config, $DBconect);
        $myPage->pageUri = 'goggo';
    }

    private function check_error_msg() {
        if (count($this -> errors) > 0) {
            $this -> is_error = TRUE;
            return TRUE;
        }
    }

    public function html_wrap_errors($config, $DBconect, $tag) {
        $wrap = array();
        if ($this -> is_error) {
            $wrap = $tag -> tag('div', 'id="error_msg"', '');
            foreach ($this->error_msg as $value) {
                $tag -> append_tag($wrap, $tag -> tag('p', 'class="error_msg_id"', $value));
            }
            $tag -> docOutput($wrap);
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

    private function failed_login($config, $DBconect) {
        array_push($this -> errors, 4);
        $this -> generate_query();
        $this -> retrive_error_msg($config, $DBconect);
        $this -> is_error = TRUE;
    }

    private function check_user_login_credentials($config, $DBconect) {
        $email = $_POST['email'];
        $password = $this -> passw_hassh($_POST['password'], $config);
        $arg = array($email, $password);
        $query = "SELECT `id`, `email`, `password`, `u_group`, `firstname` FROM `user` WHERE `email` = '$email';";
        $cred = $DBconect -> selectDB($arg, $config, $query, TRUE, 'array');
        if ($cred === NULL || !password_verify($_POST['password'], $cred['password'])) {
            return FALSE;
        } else {
            return $cred;
        }
    }

    private function login_user($config, $DBconect) {
        if ($this -> post) {
            $this -> check_inputs($_POST['email'], $_POST['password'], $_POST['captcha']);

            //here some more filtering functions would have to come

            if ($this -> check_error_msg()) {
                $this -> retrive_error_msg($config, $DBconect);
            } else {
                $is_user = $this -> check_user_login_credentials($config, $DBconect);
                if (!$is_user) {
                    $this -> failed_login($config, $DBconect);
                } else {
                    $this -> set_session($is_user);
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