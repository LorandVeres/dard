<?php
include_once '../lib/FormCleaner.Class.php';
/**
 *
 * @author  Lorand Veres
 */
class modules extends FormCleaner {

	function __construct($myPage, $tag) {
		//$this -> select_all_modules();
	}

	public $modules ;
	/**
	 * undocumented function
	 *
	 * @return array
	 * @author  Lorand Veres
	 */
	private function select_all_modules($myPage) {
		$query = "SELECT * FROM `module`;";
		return $myPage -> selectDB('',  $query, true, 'array');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	public function print_modules_combo_boxes($myPage, $tag) {
		$modules_all = $this -> select_all_modules($myPage);
		$box = $tag -> tag('div', 'class="section group row_8 center_box"', '');
		for ($i = 0; $i < count($modules_all); $i++) {
			$module_id = $modules_all[$i]["id"];
			$col = $tag -> tag('div', 'class="module_comp_box"', '');
			$h = $tag -> tag('h3', '', 'Module : ' . $modules_all[$i]["name"]);
			$p = $tag -> tag('p', '', $modules_all[0]["description"]);
			$link_div = $tag -> tag('div', 'class="section group center" style="margin-right:20px"', '');
			$link1 = $tag -> tag('a', 'href="modules?a=add-page" class="col_r spacer_1"', 'Add page');
			$link2 = $tag -> tag('a', 'href="modules?a=view-pages" class="col_r spacer_1"', 'View pages');
			$link3 = $tag -> tag('a', 'href="pages/error-messages?a=view&moduleid='.$modules_all[$i]["id"].'" class="col_r spacer_1"', 'Run time mesages');
			$tag -> append_tag($link_div, $link1);
			$tag -> append_tag($link_div, $link2);
			$tag -> append_tag($link_div, $link3);
			$tag -> append_tag($col, $h);
			$tag -> append_tag($col, $p);
			$tag -> append_tag($col, $link_div);
			$tag -> append_tag($box, $col);
		}
		$tag -> print_doc($box, 3);
	}

}
?>