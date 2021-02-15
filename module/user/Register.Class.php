<?php
include_once '../module/user/User.Class.php';

/**
 *
 */
class RegisterUser extends User {

    private $mesage = array();
    protected $is_error = FALSE;

    function __construct($myPage, $tag) {
        parent::__construct($myPage);
        $this->register_user($myPage);
    }

    private function check_error_msg() {
        if (count($this -> errors) > 0) {
            $this -> is_error = TRUE;
            return TRUE;
        }
    }

    public function html_wrap_errors($tag) {
        $wrap = array();
        if ($this -> is_error) {
            $wrap = $tag -> tag('div', 'id="error_msg"', '');
            foreach ($this->error_msg as $value) {
                $tag -> append_tag($wrap, $tag -> tag('p', 'class="error_msg_id"', $value));
            }
            $tag -> print_doc($wrap);
        }
    }

    private function check_inputs($email, $pass1, $pass2, $captcha) {
        $pass1 === $pass2 ? $this -> clean_paswd($pass1) : array_push($this -> errors, 2);
        $this -> clean_email($email);
        $this -> clean_captcha($captcha);
    }

    private function insert_user($myPage) {
        $email = $_POST['email'];
        $password = $this -> passw_hassh($_POST['password']);
        $arg = array($email, $password);
        $query = "INSERT INTO `user`(`email`, `password`) VALUES ('$email', '$password');";
        $myPage -> insertDB($arg, $query);
    }

    private function register_user($myPage) {
        if ($this -> post) {
            $this -> check_inputs($_POST['email'], $_POST['password'], $_POST['password1'], $_POST['captcha']);
            if ($this -> check_error_msg()) {
                $this -> retrive_error_msg($myPage, array('main', 'register'));
            } else {
                $this -> insert_user($myPage);
            }
        }
    }

}

?>