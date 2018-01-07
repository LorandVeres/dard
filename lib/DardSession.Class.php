<?php

/**
 *
 */
class DardSession {

    // 24 minutes max session life time
    private $time = 1440;
    // home of web app
    private $path = "/";
    // max garbage colection probability default 100
    private $gcp = 100;
    // max garbage life lenght default to 1 day
    private $max_g_life = 86400;
    //max utd cookie life 1 year
    private $utd_life = 31536000;

    function __construct() {
        $this -> init_session();
        $this -> user_cookie('la');
    }

    public function init_session() {
        if (!isset($_COOKIE['PHPSESSID'])) {
            session_set_cookie_params($this -> time, $this -> path, '.' . $_SERVER['HTTP_HOST'], TRUE, TRUE);
            session_start();
        } else {
            session_start();
            setcookie('PHPSESSID', session_id(), time() + $this -> time, $this -> path, '.' . $_SERVER['HTTP_HOST'], TRUE, TRUE);
        }
        $this -> user_session();
    }

    public function end_session() {
        if (session_status() === 1)
            session_start();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), session_id(), time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        session_commit();
    }

    public function gc_session() {
        if (1 === rand(1, $this -> gcp)) {
            $files = scandir(session_save_path());
            foreach ($files as $value) {
                if (is_file(session_save_path() . '/' . $value)) {
                    if ($value !== "." && $value !== ".." && fileatime(session_save_path() . '/' . $value) < time() - $this -> max_g_life) {
                        unlink(session_save_path() . '/' . $value);
                    }
                }
            }
        }
    }

    // User Transmited Data cookie
    public function user_cookie($la) {
        $_SERVER['HTTPS'] = 'ON' ? $http = TRUE : $http = FALSE;
        $time = time() + $this -> utd_life;
        if (!isset($_COOKIE['d_utd'])) {
            setcookie('d_utd', $this -> utd_str($la), $time, $this -> path, '.' . $_SERVER['HTTP_HOST'], $http, $http);
        } else {
            $n = explode('|', $_COOKIE['d_utd']);
            $string = $n[0] . '|' . $la;
            setcookie('d_utd', $string, $time, $this -> path, '.' . $_SERVER['HTTP_HOST'], $http, $http);
        }
    }

    private function utd_str($la) {
        $str = time();
        $str .= $_SERVER['HTTP_USER_AGENT'];
        $r = rand(5, 15);
        while ($r) {
            $name = md5($str);
            $r--;
        }
        return substr($name, 0, -16) . '|' . $la;
    }

    public function logout() {
        $this -> end_session();
        $this -> init_session();
        $this -> user_session();
    }

    private function user_session($value = '') {
        if (!isset($_SESSION['user_name']))
            $_SESSION['user_name'] = 'user_anonymous';
        if (!isset($_SESSION['user_loged']))
            $_SESSION['user_loged'] = FALSE;
        if (!isset($_SESSION['user_priv']))
            $_SESSION['user_priv'] = 'public';
    }

}
?>