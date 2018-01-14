<?php
include_once '../module/user/User.Class.php';

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
class RegisterUser extends User {

    private $mesage = array();
    private $is_error = FALSE;

    function __construct($config, $DBconect,$tag) {
        parent::__construct($config, $DBconect);
        $this->register_user($config, $DBconect);
    }

    private function check_error_msg() {
        if (count($this -> errors) > 0) {
            $this -> is_error = TRUE;
            return TRUE;
        }
    }

    public function html_wrap_errors($config, $DBconect,$tag) {
        $wrap = array();
        if ($this -> is_error) {
            $wrap = $tag -> tag('div', 'id="error_msg"', '');
            foreach ($this->error_msg as $value) {
                $tag -> append_tag($wrap, $tag -> tag('p', 'class="error_msg_id"', $value));
            }
            $tag -> docOutput($wrap);
        }
    }

    private function check_inputs($email, $pass1, $pass2, $captcha) {
        $pass1 === $pass2 ? $this -> clean_paswd($pass1) : array_push($this -> errors, 2);
        $this -> clean_email($email);
        $this -> clean_captcha($captcha);
    }

    private function insert_user($config, $DBconect) {
        $email = $_POST['email'];
        $password = $this -> passw_hassh($_POST['password'], $config);
        $arg = array($email, $password);
        $query = "INSERT INTO `user`(`email`, `password`) VALUES ('$email', '$password');";
        $DBconect -> insertDB($arg, $config, $query);
    }

    private function register_user($config, $DBconect) {
        if ($this -> post) {
            $this -> check_inputs($_POST['email'], $_POST['password'],$_POST['password1'], $_POST['captcha']);
            if ($this -> check_error_msg()) {
                $this -> retrive_error_msg($config, $DBconect);
            } else {
                $this -> insert_user($config, $DBconect);
            }
        }
    }

}

?>