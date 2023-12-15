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
		'get_snippet_body',
		'get_project_data',
		'save_snippet',
		'delete_snippet',
		'snipets',
		'get_snippet_by_name',
		'load_dummy_text',
		'load_projects_name',
		'load_snippet_type',
		'load_snippet_status',
		'load_snippet_group',
		'load_snippet_subcat',
		'load_tags',
		'responsive',
		'list_css_files',
		'search_snippets',
		'get_auto_id',
		'get_auto_class',
		'update_snippet_settings',
		'get_basic_menu',
		'load_css_file',
		);
	private $action;
	private $jx_data = array( 'project_table' => '');
	private $data_error;
	

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

	private function read_data($dard) {
		if( $dard -> ajax ) {
			$obj = json_decode( file_get_contents('php://input'), true );
			isset($obj['data']) && ( $obj['data'] === null || $_SERVER['REQUEST_METHOD'] !== 'POST' ) ? $this -> data_error = "Ajax error: Server can't fetch the data sent over..." : $this -> data_error = null ;
			/* Uncoment for debuging
			echo json_encode($obj['data']);
			exit;
			*/
			if( isset($obj['data']['project']) ) {
				$obj['data']['project'] === 'dsn' ? $this -> jx_data['project_table'] = "dsn" : $this -> jx_data['project_table'] = 'dsn_'. $obj['data']['project'];
				unset($obj['data']['project']);
			}
			if(isset($obj['data']) && is_array($obj['data']) && count($obj['data']) > 0 ) {
				$this -> jx_data = array_merge($this -> jx_data, $obj['data']);
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
		$this -> read_data($dard);
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
	private function get_snippet_body(){
		$name = $this -> jx_data['name'];
		$query = "SELECT `body` FROM `". $this -> jx_data['project_table'] ."` WHERE `name` = '".$name."';";
		$res = trim($this ->selectDB($name, $query, TRUE, 'string'), " ,\'\n\r\t\v\0");
		//$res = $this ->selectDB($name, $query, TRUE, 'string');
		echo $res;
	}
	
	// If snippet exist will return all snippet properties and body
	private function get_snippet_by_name($dard){
		$query = "SELECT * FROM `". $this -> jx_data['project_table'] ."` WHERE `name` = '" . $this -> jx_data['name'] ."';";
		$res = $this ->selectDB( $this -> jx_data , $query, TRUE, 'array');
		if( isset($res['body']) ){
			$res['body'] = json_decode($res['body'], true);
		}
		$this -> data_error ? $res = json_encode( $this -> data_error ) : $res = json_encode($res);
		echo $res;
	}
	
	private function load_tags($dard) {
		$query = "SELECT * FROM `dsn_tag`;";
		$res = $this ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}
	
	private function load_dummy_text($dard) {
		$query = "SELECT * FROM `dsn_dummy_text`;";
		$res = $this ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}
	
	private function load_projects_name($dard) {
		$query = "SELECT `name` FROM `dsn_project`  ORDER BY `id` ASC;";
		$res = $this ->selectDB('', $query, TRUE, 'array');
		echo json_encode($res);
	}
	
	private function load_snippet_type($dard) {
		$query = "SHOW COLUMNS FROM `dsn` LIKE 'type';";
		$res = $this ->selectDB('', $query, TRUE, 'array');
		echo json_encode( $this -> prepare_db_enum_field_to_json($res['Type']) );
	}
	
	private function load_snippet_group($dard) {
		$query = "SELECT DISTINCT `sgroup` AS `name` FROM `". $this -> jx_data['project_table']. "` WHERE `sgroup` <> '';";
		$res = $this ->selectDB('', $query, TRUE, 'array');
		count($res) === 1 ? $res = array($res) : null ;
		echo json_encode( $res );
	}
	
	private function load_snippet_subcat($dard) {
		$query = "SELECT DISTINCT `subcat` AS `name` FROM `". $this -> jx_data['project_table']. "` WHERE `subcat` <> '';";
		$res = $this ->selectDB('', $query, TRUE, 'array');
		( is_array($res) && count($res) === 1 ) ? $res = array($res) : null ;
		echo json_encode( $res );
	}
	
	private function load_snippet_status($dard) {
		$query = "SHOW COLUMNS FROM `snipets` LIKE 'status';";
		$res = $this ->selectDB('', $query, TRUE, 'array');
		echo json_encode( $this -> prepare_db_enum_field_to_json($res['Type']) );
	}
	
	private function add_project($dard) {
		$res = $this -> stmt($this -> jx_data['name'], array(), 'snippet__createProject', $this -> jx_data['name']);
		echo json_encode( $res );
	}

	private function add_snippet($dard) {
		isset($this -> jx_data['css']) ? $css = "'". $this -> jx_data['css'] ."'" : $css = ",''";
		$params = array ( json_encode($this -> jx_data['body']), $this -> jx_data['name'], $this -> jx_data['type'], $this -> jx_data['status'], $this -> jx_data['css'] );
		$res = $this -> stmt( $this -> jx_data['project_table'], $params, 'snippet__insertSnippet', $this -> jx_data);
		echo json_encode( $res );
	}
	
	private function get_project_data($dard) {
		$_SESSION['snippet']['project-name'] = $this -> jx_data['name']; // temporary, will need checked for invalid  characters
		$res = $this -> stmt('', array($this -> jx_data['name']), 'snippet__getProject', $this -> jx_data['name']);
		echo json_encode( $res );
	}
	
	private function save_snippet($dard) {
		isset($this -> jx_data['css']) ? $css = "'".$this -> jx_data['css']."'" : $css = ",''";
		$params = array ( json_encode($this -> jx_data['body']), $this -> jx_data['name'], $this -> jx_data['type'], $this -> jx_data['status'], $this -> jx_data['css'], $this -> jx_data['id'] );
		$res = $this -> stmt( $this -> jx_data['project_table'], $params, 'snippet__save', $this -> jx_data);
		echo json_encode( $res );
	}
	
	private function responsive ($dard, $tag) {
		if ( $_SERVER['REQUEST_METHOD']  === 'POST'){
			$body = json_encode($this -> jx_data['body']);
			$_SESSION['snippet']['project-name'] = $this -> jx_data['projectname'];
			$query = "UPDATE `dsn` SET `body`='". $body . "' WHERE `name` = 'responsive-stash';";
			$res = $this ->selectDB($body, $query, TRUE, 'array');
			echo json_encode($res);
		} else {
			$query = "SELECT `body` FROM `dsn` WHERE `name` = 'responsive-stash';";
			$res = $this ->selectDB('', $query, TRUE, 'array') ;
			$body = json_decode($res['body'], true);
			$tag -> print_doc($this -> snippet_json_to_html( $body, $tag), 2);
		}
	}
	
	private function list_css_files() {
		$cssfiles = array();
		$sassets =array('dir' => array('sassets' => array( 'dir' => array('dard' => array( 'dir' => array( 'css' => file_names_array('../sassets/dard/css')))))));
		$cssfiles = file_names_array('src/css');
		echo json_encode( array( $cssfiles, $sassets ) );
	}
	
	private function search_snippets() {
		$search = array();
		if(!isset($this -> jx_data['limit'])){
			$res = $this -> stmt( $this -> jx_data['project_table'], $search, 'snippet__searchSnippetsStared', $search);
		}else{
			$search = array( $this -> jx_data['type'], $this -> jx_data['status'], $this -> jx_data['sgroup'], $this -> jx_data['limit'] );
			$res = $this -> stmt( $this -> jx_data['project_table'], $search, 'snippet__searchSnippet', $search);
		}
		if(is_array($res) && array_key_exists('name', $res))
			$res = array($res);
		echo json_encode($res);
		
	}
	
	private function get_auto_id() {
		$res = $this -> stmt( str_replace('dsn_', '', $this -> jx_data['project_table']), array(), 'snippet__getMaxId', array());
		echo json_encode($res);
	}
	
	private function get_auto_class() {
		$res = $this -> stmt( str_replace('dsn_', '', $this -> jx_data['project_table']), array(), 'snippet__getMaxClass', array());
		echo json_encode($res);
	}
	
	private function update_snippet_settings ($dard) {
		$vorder = array ( 'name', 'type', 'status', 'css', 'sgroup', 'stared', 'locked', 'background', 'width', 'description', 'id', 'subcat', 'cssf');
		$params = array();
		for($i = 0 ; $i < count($vorder) ; $i++) {
			$params[$i] = $this -> jx_data [ $vorder[$i] ];
		}
		$res = $this -> stmt( $this -> jx_data['project_table'], $params, 'snippet__updateSettings', $params);
		//$res = $this -> stmt( $obj['data']['project'], $params, 'snippet__updateSettings', $params);
		$this -> data_error ? $res = json_encode( $this -> data_error ) : $res = json_encode($res);
		echo $res;
	}
	
	private function get_basic_menu() {
		$prj = $this -> jx_data['project_table'];
		$main = array(); $mi = 0;
		$si =0;
		$res = $this -> stmt ('', array($prj), 'snippet__getMenuMain', array($prj));
		foreach ( $res as $key => $values) {
			$main[$mi]['subcat'] = $this -> stmt ($prj, array($values), 'snippet__getMenuSubCat', array($values));
			$main[$mi]['menu'] = $values;
			foreach( $main[$mi]['subcat'] as $k => $val){
				$main[$mi]['subcat'][$si]['catname'] = array_shift($val);
				$main[$mi]['subcat'][$si]['items'] = $this -> stmt ($prj, array($values, $main[$mi]['subcat'][$si]['catname']), 'snippet__getMenuItems', array($values, $main[$mi]['subcat'][$si]['catname']));
				$si++;
			}
			$mi++;
			$si = 0;
		}
		//echo json_encode($this -> data_error);
		echo json_encode($main);
	}
	
	private function load_css_file(){
		if( isset($this -> jx_data['addcssfile']) ) {
			$readf = explode('/', $this -> jx_data['addcssfile']);
			$readf[1] === 'sassets' ? $readdir = '..' : $readdir = getcwd();
			if( is_file($readdir. $this -> jx_data['addcssfile']) ) {
				$file = read_file_to_variable( $readdir. $this -> jx_data['addcssfile']);
				echo json_encode($file);
				$readdir === getcwd() ?
					$_SESSION['snippet']['cssfiles'][] = $this -> jx_data['addcssfile'] :
					$_SESSION['snippet']['cssfiles']['read'][] = substr( $this -> jx_data['addcssfile'], 1) ;
			}
		}
		if( isset($this -> jx_data['removecssfile']) ) {
			$readf = explode('/', $this -> jx_data['removecssfile']);
			
			if(isset($_SESSION['snippet']['cssfiles'])) {
				if( $readf[1] === 'sassets' && isset( $_SESSION['snippet']['cssfiles']['read'] )) {
					foreach($_SESSION['snippet']['cssfiles']['read'] as $key => $value) {
						if( $value === substr( $this -> jx_data['removecssfile'], 1) ) {
							unset($_SESSION['snippet']['cssfiles']['read'][$key]) ;
						}
					}
				} else {
					foreach($_SESSION['snippet']['cssfiles'] as $key => $value) {
						if( $value === $this -> jx_data['removecssfile']) {
							unset($_SESSION['snippet']['cssfiles'][$key]) ;
						}
					}
				}
				echo json_encode( $_SESSION['snippet']['cssfiles'] );
			}
		}
	}
}
?>