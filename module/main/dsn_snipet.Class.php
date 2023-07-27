<?php
include_once '../lib/FormCleaner.Class.php';
/**
 *
 * @author  Lorand Veres
 */
class dsn_snipet extends FormCleaner {

	use snipetHandler;
	public $modules;
	private $posible_actions = array('add_snipet', 'get_snipet', 'get_snipet_by_name', 'get_dummy_text', 'update_snipet', 'delete_snipet', 'snipets', 'get_tags');
	private $action;

	function __construct($dard, $tag) {
		FormCleaner::__construct($dard);
		$this -> call_action($dard, $tag);
	}

/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function get_url_params($dard) {
		if (isset($dard -> url_arguments['a'])) {
			if (in_array(str_replace('-', '_', $dard -> url_arguments['a']), $this -> posible_actions)) {
				$this -> action = str_replace('-', '_', $dard -> url_arguments['a']);
			}
		}
	}


/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function call_action($dard, $tag) {
		$this -> get_url_params($dard);
		if ($this -> action !== NULL) {
			$fn = $this -> action;
			if (method_exists($this, $fn)) {
				$this -> $fn($dard, $tag);
			} else {
				echo "Action does not yet implemented. = " . $fn;
			}
		}
	}
	
	private function get_snipet_by_name($dard){
		if( isset( $dard -> url_arguments['name']) ) {
			$name = str_replace('-', '_', $dard -> url_arguments['name'] );
			$query = "SELECT `snipet` FROM `snipets` WHERE `name` = '$name';";
			echo trim($dard ->selectDB('', $query, TRUE, 'string'), " ,\'\n\r\t\v\0");
		}else{
			echo json_encode('no name parameter set up');
		}
	}
	
	private function get_tags($dard) {
		$query = "SELECT * FROM `dsn_tag`;";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}
	
	private function get_dummy_text($dard) {
		$query = "SELECT * FROM `dsn_dummy_text`;";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}

}
?>