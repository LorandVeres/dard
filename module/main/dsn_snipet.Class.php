<?php
include_once '../lib/FormCleaner.Class.php';
/**
 *
 * @author  Lorand Veres
 */
class dsn_snipet extends FormCleaner {

	use snipetHandler;
	public $modules;
	private $posible_actions = array(
		'add_snipet',
		'add_project',
		'update_snipet',
		'get_snipet',
		'delete_snipet',
		'snipets',
		'get_snipet_by_name',
		'load_dummy_text',
		'load_projects_name',
		'load_snipet_type',
		'load_snipet_status',
		'load_tags'
		);
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
			$query = "SELECT `body` FROM `snipets` WHERE `name` = '$name';";
			echo trim($dard ->selectDB('', $query, TRUE, 'string'), " ,\'\n\r\t\v\0");
		}else{
			echo json_encode('no name parameter set up');
		}
	}
	
	private function load_tags($dard) {
		$query = "SELECT * FROM `dsn_tag`;";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}
	
	private function load_dummy_text($dard) {
		$query = "SELECT * FROM `dsn_dummy_text`;";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}
	
	private function load_projects_name($dard) {
		$query = "SELECT `name` FROM `dsn_project`  ORDER BY `id` ASC;";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}
	
	private function load_snipet_type($dard) {
		$query = "SHOW COLUMNS FROM `snipets` LIKE 'type';";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode( $this -> prepare_db_enum_field_to_json($res['Type']) );
	}
	
	private function load_snipet_status($dard) {
		$query = "SHOW COLUMNS FROM `snipets` LIKE 'status';";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode( $this -> prepare_db_enum_field_to_json($res['Type']) );
	}
	
	private function add_project($dard) {
		$obj = json_decode( file_get_contents('php://input') );
		$data = $obj->data;
		$res = $dard -> stmt($data->Name, array(), 'createSnipetProject', $data->Name);
		echo json_encode( $res );
	}

	private function add_snipet($dard) {
		$obj = json_decode( file_get_contents('php://input'), true );
		$data = $obj['data'];
		isset($data['css']) ? $css = "'".$data['css']."'" : $css = ",''";
		$data['project'] === 'dsn' ? $table_name = "" : $table_name = '_'. $data['project'];
		$params = array ( json_encode($data['body']), $data['name'], $data['type'], $data['status'], $data['css'] );
		$res = $dard -> stmt( $table_name, $params, 'insertSnipet', $data);
		echo json_encode( $res );
	}
	
	
}
?>