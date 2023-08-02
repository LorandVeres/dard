<?php
/**
 *
 */
class FormCleaner {

	public $post = FALSE;
	// $error store an array of error id's
	protected $errors = array();
	// $error_msg store an array of error messages
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
		if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_POST) && count($_POST) > 0 && !empty($_POST))) {
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

	/*
	 * Compare a string against a patern
	 *
	 * @params
	 *
	 * $patern the patern to be compared against
	 * $string the string to be checked
	 * $error_number is the number of the error per module what will be generated
	 *
	 * @return Bool : true on success false otherwise
	 */
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

	/*
	 *  Check if string is not empty and against a patern
	 *
	 * @params
	 * Same as match function
	 * $field the name of the field
	 *
	 * @return Bool : true on success false otherwise
	 */
	protected function non_empty_match($patern, $string, $error_number, $field) {
		$result = FALSE;
		if (isset($string)) {
			if (empty($string)) {
				$result = TRUE;
				$this -> is_error = TRUE;
				array_push($this -> error_msg, 'Field ' . $field . 'is empty.');
			} else {
				$result = $this -> match($patern, $string, $error_number);
			}
		}
		return $result;
	}

	/**
	 * undocumented function
	 *
	 * @return bool
	 */
	protected function clean_email($email) {
		return $this -> non_empty_match($this -> regex_email, $email, 1, 'Email');
	}

	/**
	 * undocumented function
	 *
	 * @return bool
	 */
	protected function clean_paswd($paswd) {
		return $this -> non_empty_match($this -> regex_paswd, $passwd, 2, 'Password');
	}

	protected function clean_login($email, $passwd) {
		$this -> non_empty_match($this -> regex_email, $email, 4, 'Email');
		$this -> non_empty_match($this -> regex_paswd, $passwd, 4, 'Password');
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
		$error_num = count($this -> errors);
		if ($error_num > 0) {
			for ($i = 0; $i < count($this -> errors); $i++) {
				$j = $this -> errors[$i];
				if ($i == 0) {
					$where = "(`number` = '$j'";
					if ($error_num === 1)
						$where .= ")";
				} else {
					$where .= " OR `number` = '$j'";
					if ($i === $error_num - 1)
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
	
	// Will return an aray ready to be sent via ajax for select box options
	protected function prepare_db_enum_field_to_json($arg){
		$i = 0;
		$result = array();
		$arg = str_replace("'", "", str_replace("enum(", "", str_replace(")", "" ,$arg  )));
		foreach ( explode( ',', $arg ) as $value ){
			$result[$i] = array ( 'name' => $value );
			$i++;
		}
		return $result;
	}
	
	// Checks if posted data is in the enum field type in table;
	protected function filter_post_vs_db_enum($post, $table, $column, $dard){
		$query = "SHOW COLUMNS FROM `. $table .` LIKE '". $column ."';";
		$enum =  $dard ->selectDB('', $query, TRUE, 'array');
		$$enum = str_replace("'", "", str_replace("enum(", "", str_replace(")", "" , $enum  )));
		return in_array( $post, explode( ',', $enum ));
	}

	//=============================================
	//
	// Form preparing functions from db
	//
	//=============================================

	/**
	 * Retriving the form data from data base
	 * @param $form_identifier
	 * --Can be a numerical form id from db or a string for form name
	 *
	 * @return array
	 */
	private function get_form_fields($dard, $form_identifier) {
		if (is_numeric($form_identifier)) {
			$query = "SELECT * FROM `forms` WHERE `id` = '$form_identifier';";
			$query .= "SELECT * FROM `form_fields` WHERE `form_id` = '$form_identifier';";
		} elseif (is_string($form_identifier)) {
			$query = "SELECT * FROM `forms` WHERE `name` = '$form_identifier';";
			$query .= "SELECT * FROM `form_fields` WHERE `form_id` = (SELECT `id` FROM `forms` WHERE `name` = '$form_identifier');";
		}
		return $dard -> selectDB($form_identifier, $query, TRUE, 'default');
	}

	public function wrap_form_array($dard, $form_identifier) {
		$form_array = array();
		$form_data = $this -> get_form_fields($dard, $form_identifier);
		$form = $form_data[0];
		$form_array['header'] = array($form["header_type"], $form["form_header"]);
		$form_array['attr'] = 'name="' . $form['name'] . '" id="' . $form['form_id'] . '" method="' . $form['method'] . '" action="' . $form['action'] . '" ' . ($form['attributes'] !== NULL ? $form['attributes'] : '');
		$form_array['fields'] = array();
		$field_attr = '';
		foreach ($form_data[1] as $val) {
			// checking possible null or empty values
			$val['label_for'] === NULL ? $label = FALSE : $label = TRUE;
			$val['label_attr'] === NULL ? $label_attr = '' : $label_attr = ' ' . $val['label_attr'];
			$val['field_text'] === NULL || empty($val['field_text']) ? $field_text = '' : $field_text = $val['field_text'];
			$val['id'] === NULL ? $field_attr .= '' : $field_attr .= ' id="' . $val['field_id'] . '"';
			$val['type'] === NULL ? $field_attr .= '' : $field_attr .= ' type="' . $val['type'] . '"';
			$val['name'] === NULL || empty($val['name']) || $val['type'] ==='submit' ? $field_attr .= '' : $field_attr .= ' name="' . $val['name'] . '"';
			$val['value'] === NULL ? $field_attr .= '' : $field_attr .= ' value="' . $val['value'] . '"';
			$val['field_placeholder'] === '' ? $field_attr .= '' : $field_attr .= ' placeholder="' . $val['field_placeholder'] . '"';
			$val['field_attributes'] === NULL ? $field_attr .= '' : $field_attr .= ' ' . $val['field_attributes'];
			if (is_array($val)) {
				if ($label) {
					if(substr_count($field_attr, 'type="radio"') ==1 || substr_count($field_attr, 'type="checkbox"') == 1){
						array_push($form_array['fields'], array('label',  array('for'=>$val['label_for'], $label_attr), array(array($val['field'], $field_attr, $field_text ), $val['label_text'])));
					}else{
						array_push($form_array['fields'], array('label',  array('for'=>$val['label_for'], $label_attr), $val['label_text']));
						array_push($form_array['fields'], array($val['field'], $field_attr, $field_text ));
					}
				} else {
					array_push($form_array['fields'], array($val['field'], $field_attr, $field_text ));
				}
			}
			$field_attr  ='';
		}
		return $form_array;
	}

}
?>
