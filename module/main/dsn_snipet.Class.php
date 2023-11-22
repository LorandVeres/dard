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
		'add_snippet',
		'add_project',
		'update_snippet',
		'get_snipet',
		'get_project_data',
		'save_snippet',
		'delete_snippet',
		'snipets',
		'get_snippet_by_name',
		'load_dummy_text',
		'load_projects_name',
		'load_snippet_type',
		'load_snippet_status',
		'load_tags',
		'responsive',
		'list_css_files'
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
	
	// This will return just the body of the snippet
	// Use get_snipet_by_name for all snipet properties and body
	private function get_snipet($dard){
		if( isset( $dard -> url_arguments['name']) ) {
			$name = str_replace('-', '_', $dard -> url_arguments['name'] );
			$query = "SELECT `body` FROM `snipets` WHERE `name` = '$name';";
			echo trim($dard ->selectDB('', $query, TRUE, 'string'), " ,\'\n\r\t\v\0");
		}else{
			echo json_encode('no name parameter set up');
		}
	}
	
	// If snippet exist will return all snippet properties and body
	private function get_snippet_by_name($dard){
		$obj = json_decode( file_get_contents('php://input') );
		$data = $obj->data;
		$data->project === 'dsn' ? $table_name = "dsn" : $table_name = 'dsn_'. $data->project;
		$query = "SELECT * FROM `". $table_name ."` WHERE `name` = '$data->name';";
		$res = $dard ->selectDB($data, $query, TRUE, 'array');
		$res['body'] = json_decode($res['body']);
		echo json_encode($res);
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
	
	private function load_snippet_type($dard) {
		$query = "SHOW COLUMNS FROM `snipets` LIKE 'type';";
		$res = $dard ->selectDB('', $query, TRUE, 'array');
		echo json_encode( $this -> prepare_db_enum_field_to_json($res['Type']) );
	}
	
	private function load_snippet_status($dard) {
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

	private function add_snippet($dard) {
		$obj = json_decode( file_get_contents('php://input'), true );
		$data = $obj['data'];
		isset($data['css']) ? $css = "'".$data['css']."'" : $css = ",''";
		$data['project'] === 'dsn' ? $table_name = "" : $table_name = '_'. $data['project'];
		$params = array ( json_encode($data['body']), $data['name'], $data['type'], $data['status'], $data['css'] );
		$res = $dard -> stmt( $table_name, $params, 'insertSnipet', $data);
		echo json_encode( $res );
	}
	
	private function get_project_data($dard) {
		$obj = json_decode( file_get_contents('php://input'), true );
		$data = $obj['data'];
		$_SESSION['snippet-project'] = $data['name']; // temporary, will need checked for invalid  characters
		$res = $dard -> stmt('', array($data['name']), 'getProject', $data['name']);
		echo json_encode( $res );
	}
	
	private function save_snippet($dard) {
		$obj = json_decode( file_get_contents('php://input'));
		$data = $obj->data;
		isset($data->css) ? $css = "'".$data->css."'" : $css = ",''";
		$data->project === 'dsn' ? $table_name = "" : $table_name = '_'. $data->project;
		$params = array ( json_encode($data->body), $data->name, $data->type, $data->status, $data->css, $data->id );
		$res = $dard -> stmt( $table_name, $params, 'saveSnippet', $data);
		echo json_encode( $res );
	}
	
	private function responsive ($dard, $tag) {
		if ( $_SERVER['REQUEST_METHOD']  === 'POST'){
			$obj = json_decode( file_get_contents('php://input'), true);
			$data = $obj['data'];
			$body = json_encode($data['body']);
			$query = "UPDATE `dsn` SET `body`='". $body . "' WHERE `name` = 'responsive-stash';";
			$res = $dard ->selectDB($body, $query, TRUE, 'array');
			echo json_encode($res);//var_dump($body);
		} else {
			$query = "SELECT `body` FROM `dsn` WHERE `name` = 'responsive-stash';";
			$res = $dard ->selectDB('', $query, TRUE, 'array') ;
			$body = json_decode($res['body'], true);
			$tag -> print_doc($this -> snipet_json_to_html( $body, $tag), 2);
		}
	}
	
	private function list_css_files() {
		echo json_encode(file_names_array('src/css'));
	}
}
?>