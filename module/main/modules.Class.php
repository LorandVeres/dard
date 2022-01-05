<?php
include_once '../lib/FormCleaner.Class.php';
/**
 *
 * @author  Lorand Veres
 */
class modules extends FormCleaner {

	public $modules;
	private $posible_actions = array('add_module', 'add_page', 'view_pages');
	private $action;

	function __construct($dard, $tag) {
		//$this -> select_all_modules();
		//$this ->call_action($dard, $tag);
	}

	/**
	 * undocumented function
	 *
	 * @return array
	 * @author  Lorand Veres
	 */
	private function select_all_modules($dard) {
		$query = "SELECT * FROM `module`;";
		return $dard -> selectDB('', $query, true, 'array');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	public function print_modules_combo_boxes($dard, $tag) {
		$modules_all = $this -> select_all_modules($dard);
		$box = $tag -> tag('div', 'class="section group max_row_9 center_box"', '');
		for ($i = 0; $i < count($modules_all); $i++) {
			$module_id = $modules_all[$i]["id"];
			$col = $tag -> tag('div', 'class="module_comp_box"', '');
			$link_div = $tag -> tag('div', 'class="section group center"', '');
			$tag -> append_tag($link_div, $tag -> tag('a', 'href="modules?a=add-page&moduleid='. $module_id .'" class="col_r spacer_1"', 'Add page'));
			$tag -> append_tag($link_div, $tag -> tag('a', 'href="modules?a=view-pages&moduleid=' .$module_id. '" class="col_r spacer_1"', 'View pages'));
			$tag -> append_tag($link_div, $tag -> tag('a', 'href="pages/error-messages?a=view&moduleid='. $module_id. '" class="col_r spacer_1"', 'Run time mesages'));
			$tag -> append_tag($col, $tag -> tag('h3', '', $modules_all[$i]["name"]));
			$tag -> append_tag($col, $tag -> tag('p', '', $modules_all[0]["description"]));
			$tag -> append_tag($col, $link_div);
			$tag -> append_tag($box, $col);
		}
		$tag -> print_doc($box, 3);
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
	public function call_action($dard, $tag) {
		$this -> get_url_params($dard);
		if ($this -> action !== NULL) {
			$fn = $this -> action;
			if (method_exists($this, $fn)) {
				$this -> $fn($dard, $tag);
			} else {
				echo "Action does not work yet. = " . $fn;
			}
		} else {
			$this -> print_modules_combo_boxes($dard, $tag);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function add_module($dard, $tag) {
		echo "I am here it works";
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	 private function view_pages($dard, $tag){
	 	if (isset($dard -> url_arguments['moduleid']) && is_numeric($dard -> url_arguments['moduleid']))
			$module_id = $dard -> url_arguments['moduleid'];
		$query = "SELECT
					O.`id`,
					O.`pagename`,
					O.`type`,
					P.`pagename` AS parent_page,
					O.`title`,
					O.`file_path`,
					O.`arg`,
					O.`status`
				FROM
					`page` AS O,
					`page` AS P
				WHERE
					O.`parentpage` = P.`id` AND P.`parentpage` IS NULL AND O.`module_id` = '".$module_id."'
				UNION
				SELECT
					`id`,
					`pagename`,
					`type`,
					`parentpage` AS parent_page,
					`title`,
					`file_path`,
					`arg`,
					`status`
				FROM
					`page`
				WHERE
    				`module_id` = '".$module_id."' AND `parentpage` IS NULL
				ORDER BY
					`id`;";
		$table = array(
			'tb' => 'style="width:90%;overflow-x:auto;"',
			'th' => array('Id', 'Page Name', 'Type', 'Parent Page', 'Title', 'File Path', 'Args', 'Status'),
			'attr' => array(),
			'data' => $dard -> selectDB('', $query, false, 'array')
		);
		if(isset($table['data'][0])){
			if(!is_array($table['data'][0]) && (is_string($table['data'][0]) || is_numeric($table['data'][0]))){
				$ar = $table['data'];
				$table['data'] = array();
				$table['data'][0] = $ar;
			}
		}
		$tag -> print_table($table);
	 }
	 
	 /**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	 private function add_page($dard, $tag){
	 	echo "Add pages.";
	 }

}
?>