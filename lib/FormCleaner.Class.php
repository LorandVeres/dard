<?php
//if (!defined('DARDSTATUS'))
//    exit();
/**
 *
 */
class FormCleaner{

    public $post = FALSE;
        // $error store an array of error id's
    protected $errors = array();
        // $error_msg store an array of error message
    protected $error_msg = array();
    protected $is_error = false;
    private $regex_paswd = "/^(\S){6,24}$/";
    private $regex_user = "/^(\w){3,24}$/";
    private $regex_email = "/^([\w\.\-\+]){1,70}@[a-zA-Z0-9\-\.]{3,}[\.]{1}[a-zA-Z]{2,}$/";
    private $regex_name = "/\A(\p{L}[\s-'.]*){1,50}\z/";

    function __construct($config) {
        $this -> BoleanPost();
        $this -> utf8_matches($config);
    }

    private function utf8_matches($config) {
        if ($config -> language !== "en") {
            $this -> regex_paswd .= $this -> regex_paswd . "u";
            $this -> regex_user .= $this -> regex_user . "u";
        }
    }

    protected function BoleanPost() {
        if (isset($_POST) && count($_POST) > 0 && !empty($_POST)) {
            $this -> post = TRUE;
        }
    }

    protected function str_lenght($string, $min, $max) {
        $result = FALSE;
        $lenght = strlen($string);
        if ($lenght >= $min && $lenght <= $max)
            $result = TRUE;
        return $result;
    }

    protected function match($patern, $string, $error_number) {
        $result = FALSE;
        if (!empty($string) && isset($string)) {
            if (preg_match($patern, $string)) {
                $result = TRUE;
            } else {
                array_push($this -> errors, $error_number);
            }
        } else {
            array_push($this -> errors, $error_number);
        }
        return $result;
    }

    protected function clean_email($email) {
        return $this -> match($this -> regex_email, $email, 1);
    }

    protected function clean_login($email, $passwd) {
        $this -> match($this -> regex_email, $email, 4);
        $this -> match($this -> regex_paswd, $passwd, 4);
    }

    protected function clean_paswd($paswd) {
        return $this -> match($this -> regex_paswd, $paswd, 2);
    }

    protected function clean_captcha($captcha) {
        $result = FALSE;
        $captcha = trim($captcha);
        if (isset($_SESSION['captcha']))
            $captcha === $_SESSION['captcha'] ? $result = TRUE : $result = FALSE;
        if (!$result)
            array_push($this -> errors, 3);
        return $result;
    }
    
    protected function generate_query($module) {
        $query = "SELECT `message` FROM `error_message` WHERE";
        $where = '';
        if (count($this -> errors) > 0) {
            for ($i = 0; $i < count($this -> errors); $i++) {
                $j = $this -> errors[$i];
                if ($i == 0) {
                    $where = "`number` = '$j'";
                } else {
                    $where .= " OR `number` = '$j'";
                }
            }
        }
        return $query .= $where . " AND `module_name` = '$module';";
    }

    protected function retrive_error_msg($config, $DBconect, $module) {

        $query = $this -> generate_query($module);
        $this -> error_msg = $DBconect -> selectDB($this -> errors, $config, $query, FALSE, 'array');
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

}
?>