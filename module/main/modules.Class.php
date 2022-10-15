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
		FormCleaner::__construct($dard);
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
		$form = $this -> wrap_form_array($dard, 'add_module');
		$hdl_post = function(){

		};

		$json_snipet_to_html = function($snipet, $tag) use (&$json_snipet_to_html){
			$new_tag = array();
			$set_attributes = function($snipet_attr){
				$attr = '';
				foreach ($snipet_attr as $key => $value) {
					$attr .= ' '.$key.'="'.$value.'"';
				}
				return $attr;
			};
			if(isset($snipet['e_type'])){
				if(is_string($snipet['e_content'])){
					$new_tag = $tag -> tag(($snipet['e_name'] !== '#text' ? strtolower($snipet['e_name']) : ''), (isset($snipet['e_attr']) ? $set_attributes($snipet['e_attr']) : ''), $snipet['e_content']);
				}elseif(is_array($snipet['e_content'])){
					$new_tag = $tag -> tag(strtolower($snipet['e_name']), $set_attributes($snipet['e_attr']), '');
					foreach ($snipet['e_content'] as $value) {
						if(is_array($value)){
							if(count($value) >=1){
								$tag -> append_tag($new_tag, $json_snipet_to_html($value, $tag));
								//var_dump($value);
							}
						}else{
							//var_dump($value);
							if (is_string($value))
								$tag ->append_tag($new_tag, $tag -> tag('', '', $snipet['e_content']));
						}
					}
				}
			}
			return $new_tag;
		};

		if( $_SERVER['REQUEST_METHOD'] === 'POST'){
			if( $dard->ajax ) {
				$post = file_get_contents('php://input');
				$query = "UPDATE `snipets` SET `snipet` = \"" . $post . "\" WHERE `id` = 1;";
				//$dard -> insertDB($post, $query);
			}
		}elseif($_SERVER['REQUEST_METHOD'] === 'GET' ){
			if($dard -> ajax ) {
				$query = "SELECT `snipet` FROM `snipets` WHERE `page_id` = 15;";
				echo trim($dard ->selectDB('', $query, TRUE, 'string'), " ,\'\n\r\t\v\0");
			}else{
				//$tag -> print_simple_form($form, 4);
				$query = "SELECT `snipet` FROM `snipets` WHERE `page_id` = 15;";
				$snipet = json_decode(trim($dard ->selectDB('', $query, TRUE, 'string'), " ,\'\n\r\t\v\0"), TRUE);
				//var_dump($json_snipet_to_html($snipet, $tag));
				$tag -> print_doc($json_snipet_to_html($snipet, $tag), 4);
			}
		}
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