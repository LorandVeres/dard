<?php
/**
 *
 */
class FormCleaner {

	public $post = FALSE;
	// $error store an array of error id's
	protected $errors = array();
	// $error_msg store an array of error message
	protected $error_msg = array();
	protected $is_error = FALSE;
	private $regex_paswd = "/^[\S\p{L}\p{M}*0-9]{6,50}$/";
	private $regex_user = "/^(\w){3,24}$/";
	private $regex_email = "/^([\w\.\-\+]){1,70}@[a-zA-Z0-9\-\.]{3,}[\.]{1}[a-zA-Z]{2,}$/";
	private $regex_name = "/^[\S\p{L}\p{M}*\s\-\.']{1,35}$/";

	function __construct($dard) {
		$this -> BoleanPost();
		$this -> utf8_matches($dard);
	}

	private function utf8_matches($dard) {
		if ($dard -> cf_language !== "en") {
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
		if (isset($string)) {
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
		if (is_array($module)) {
			$help = "(SELECT `id` FROM `module` WHERE `name` = '$module[0]')";
			$help1 = "(SELECT `id` FROM `page` WHERE `pagename` = '$module[1]')";
			$help_where = "(`module` = $help OR `page` = $help1)";
		} else if (is_string($module)) {
			$help_where = "`page` = (SELECT `id` FROM `page` WHERE `pagename` = '$module')";
		}
		$query = "SELECT `message` FROM `error_message` WHERE ";
		$where = '';
		$error_num =count($this -> errors);
		if ($error_num > 0) {
			for ($i = 0; $i < count($this -> errors); $i++) {
				$j = $this -> errors[$i];
				if ($i == 0) {
					$where = "(`number` = '$j'";
					if($error_num === 1)
						$where .= ")";
				} else {
					$where .= " OR `number` = '$j'";
					if($i === $error_num - 1)
						$where .= ")";
				}
			}
		}
		return $query .= $where . " AND " . $help_where . ";";
	}

	protected function retrive_error_msg($dard, $module) {
		$query = $this -> generate_query($module);
		$this -> error_msg = $dard -> selectDB($this -> errors, $query, FALSE, 'array');
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

}
?>