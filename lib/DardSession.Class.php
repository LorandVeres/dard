<?php
//include_once '../config.php';
//include_once 'dbConect.Class.php';
/**
 *
 */
class DardSession extends dbConect{

    // 24 minutes max session life time
    private $time;
    // home of web app
    private $path;
    // max garbage colection probability default 100
    private $gcp = 100;
    // max garbage life lenght default to 1 day
    private $max_g_life = 86400;
    //max utd cookie life 1 year
    private $utd_life ;

    function __construct() {
        $this -> init_params();
        $this -> init_session();
        $this -> user_cookie(time());
        $this -> init_user_session();
        $this -> check_session_last_time();
    }
	
	private function init_params() {
		$this -> time = $this -> cf_session_lifetime;
		$this -> path = $this -> cf_session_path;
		$this -> utd_life = $this -> cf_session_utd_life;
	}
	
    public function init_session() {
        $options = array ( 
            'expires' => time() + $this -> time,
            'path' => $this -> path,
            'domain' => '.' . $_SERVER['HTTP_HOST'],
            'secure' => TRUE,
            'httponly' => TRUE,
            'samesite' => 'Strict'
        );
        if (!isset($_COOKIE['DARDSESSID'])) {
            session_set_cookie_params(
                $this -> time, $this -> path, '.' . $_SERVER['HTTP_HOST'], TRUE, 'SameSite=Strict' 
            );
            session_name('DARDSESSID');
            session_start();
        } else {
            session_start();
            setcookie('DARDSESSID', session_id(), $options);
        }
    }
    
    private function check_session_last_time() {
        if(!isset($_SESSION['session_last_time'])){
            $_SESSION['session_last_time'] = time() + $this -> time;
        }else if( isset($_SESSION['session_last_time']) ){
            if ( time() > $_SESSION['session_last_time'] && ( $_SESSION['user_name'] !== 'anonymous' || $_SESSION['user_loged'] )) {
				$this -> end_session();
				$_SESSION['session_last_time'] = time() + $this -> time;
			}else { 
				$_SESSION['session_last_time'] = time() + $this -> time;
			}
        
        }
    }

    public function end_session() {
        if (session_status() === 1)
            session_start();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), 1, $params["path"], $params["domain"], $params["secure"], $params["httponly"], $params['samesite']);
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
		$options = array ( 
            'expires' => $time,
            'path' => $this -> path,
            'domain' => '.' . $_SERVER['HTTP_HOST'],
            'secure' => TRUE,
            'httponly' => TRUE,
            'samesite' => 'Strict'
        );
        if (!isset($_COOKIE['d_utd'])) {
            setcookie('d_utd', $this -> utd_str($la), $options);
        } else {
            $n = explode('|', $_COOKIE['d_utd']);
            $string = $n[0] . '|' . $la;
            setcookie('d_utd', $string, $options);
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
        $this -> init_user_session();
    }

    protected function init_user_session() {
        if (!isset($_SESSION['user_name']))
            $_SESSION['user_name'] = 'anonymous';
        if (!isset($_SESSION['user_loged']))
            $_SESSION['user_loged'] = FALSE;
	}
    
}
?>