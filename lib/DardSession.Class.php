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

    function __construct() {
        $this -> init_session();
    }

    public function init_session() {
        if (!isset($_COOKIE['PHPSESSID'])) {
            session_set_cookie_params($this -> time, $this -> path, '.' . $_SERVER['HTTP_HOST'], TRUE, TRUE);
            session_start();
        } else {
            session_start();
            setcookie('PHPSESSID', session_id(), time() + $this -> time, $this -> path, '.' . $_SERVER['HTTP_HOST'], TRUE, TRUE);
        }
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

}
?>