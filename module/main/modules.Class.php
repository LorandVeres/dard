<?php
include_once '../lib/FormCleaner.Class.php';
/**
 *
 * @author  Lorand Veres
 */
class modules extends FormCleaner {

	function __construct($dard, $tag) {
		//$this -> select_all_modules();
	}

	public $modules ;
	/**
	 * undocumented function
	 *
	 * @return array
	 * @author  Lorand Veres
	 */
	private function select_all_modules($dard) {
		$query = "SELECT * FROM `module`;";
		return $dard -> selectDB('',  $query, true, 'array');
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
			$tag -> append_tag($link_div, $tag -> tag('a', 'href="modules?a=view-pages&moduleid' .$module_id. '" class="col_r spacer_1"', 'View pages'));
			$tag -> append_tag($link_div, $tag -> tag('a', 'href="pages/error-messages?a=view&moduleid='. $module_id. '" class="col_r spacer_1"', 'Run time mesages'));
			$tag -> append_tag($col, $tag -> tag('h3', '', $modules_all[$i]["name"]));
			$tag -> append_tag($col, $tag -> tag('p', '', $modules_all[0]["description"]));
			$tag -> append_tag($col, $link_div);
			$tag -> append_tag($box, $col);
		}
		$tag -> print_doc($box, 3);
	}

}
?>