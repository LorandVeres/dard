<?php

/**
 *
 */

include_once '../lib/FormCleaner.Class.php';
class pages extends FormCleaner {
	function __construct($dard, $tag) {
		parent::__construct($dard);
		$this -> modules = $this -> getModules($dard);
		$this -> setModuleIdName();
		$this -> getMainPages($this -> module_id, $dard);
		$this -> css = $this -> getCssId($dard);
	}

	public $modules;
	public $module_id;
	public $module_name;
	public $mainPages;
	public $css;
	public $page;
	public $page_id;
	public $page_confirm_h2;
	public $confirm_message;
	public $methods = array();

	public function exist() {
		foreach (get_class_methods($this) as $key => $value) {
			$this -> methods[] = $value;
		}
	}

	private function getAll($dard, $query) {
		return $dard -> selectDB('', $query, TRUE, 'array');
	}

	private function failedTransaction($dard) {
		$this -> is_error = TRUE;
		$this -> generate_query('page');
		$this -> retrive_error_msg('page');
	}

	private function getModules($dard) {
		$query = "SELECT * FROM `module` ;";
		$modules = $this -> getAll($dard, $query);
		$this -> modules = $modules;
		return $modules;
	}

	private function setModuleIdName() {
		if ($this -> post) {
			if (isset($_POST['select_module']) && $_POST['select_module'] === 'select_module') {
				if (isset($_POST['module']) && $this -> match("/^[0-9]+$/", $_POST['module'], 9)) {
					$this -> module_id = $_POST['module'];
					//$this->module_name =
					foreach ($this->modules as $value) {
						if ($value['id'] === $this -> module_id)
							$this -> module_name = $value['name'];
					}
				}
			}
		}
	}

	private function getMainPages($module_id, $dard) {
		$module_id === null ? $module_id = 1 : $module_id = $dard->module_id;
		$query = "SELECT `id`, `pagename` FROM `page` WHERE `module_id` = $module_id AND `type` <> 'sub';";
		$pages = $dard -> selectDB($module_id, $query, TRUE, 'array');
		if (is_array($pages)){
			if ( array_key_exists('id', $pages))
			$pages = array($pages);
		}
		$this -> mainPages = $pages;
	}

	private function getCssId($dard) {
		$query = "SELECT `id`, `href` FROM `link_tag` WHERE `active` = 1 AND `general` = '0' AND `type` = 'text/css';";
		$result = $dard -> selectDB('', $query, TRUE, 'array');
		return $result;
	}

	private function addModule($module) {
		$query = "INSERT INTO `module`(`id`, `name`) VALUES (null, $module);";
		$dard -> insertDB($module, $query);
	}

	private function getPage($page) {
		$query = "SELECT * FROM `page` WHERE `pagename` = $page;";
		$this -> page = $dard -> selectDB($page, $query, TRUE, 'array');
	}

	private function addPageValues() {
		$values = '(';
		if ($this -> match("/^([a-zA-Z0-9\-]){1,50}$/", $_POST['pagename'], 1))
			$values .= "'" . $_POST['pagename'] . "', '";
		if ($this -> match("/^((main)?(sub)?(top)?){1}$/", $_POST['type'], 2))
			$values .= $_POST['type'] . "', ";
		if ($this -> match("/^([NULL]|[0-9])+$/", $_POST['parentpage'], 3))
			$values .= $_POST['parentpage'] . ", '";
		if ($this -> match("/^([\w\-\s\|]){3,70}$/", $_POST['title'], 4))
			$values .= $_POST['title'] . "', '";
		if ($this -> match("/^([\w\-\.\/]){5,50}$/", $_POST['pageURI'], 5))
			$values .= 'template/module/' . $this -> module_name . '/' . $_POST['pageURI'] . "', ";
		if ($this -> match("/^([0-9])+$/", $_POST['module_id'], 6))
			$values .= $_POST['module_id'] . ", ";
		if ($this -> match("/^[0-1]{1}$/", $_POST['arg'], 7))
			$values .= $_POST['arg'] . ", ";
		if ($this -> match("/^([NULL]|[0-9])+$/", $_POST['css'], 8))
			$values .= $_POST['css'] . ')';
		return $values;
	}

	private function addPage($dard) {
		$values = $this -> addPageValues();
		$query = "
            INSERT INTO `page`
            (`pagename`, `type`, `parentpage`, `title`, `pageURI`, `module_id`, `arg`, `css`)
            VALUES 
            $values;";
		return $dard -> insertDB($_POST, $query);
	}

	private function getConfirmMessage($page_id) {
		$query = "SELECT `message` FROM  `error_message` WHERE `module` = 2 AND `type` = 1 ;";
		$query .= " SELECT * FROM `page` WHERE `id` = $page_id;";
		$result = $dard -> selectDB($page_id, $query, FALSE, 'default');
		$result[0] = $this -> defaultToLiniarArray($result[0]);
		return $result;
	}

	private function defaultToLiniarArray($array) {
		$a = array();
		foreach ($array as $value) {
			$a[] = $value[0];
		}
		return $a;
	}

	private function filterAddPageConfirm($page_id) {
		$result = $this -> getConfirmMessage($page_id);
		$new = array();
		foreach ($result as $key => $value) {
			if ($key === 1 && $value !== null) {
				foreach ($value as $k => $val) {
					if ($k === 5 || $k === 0) {
						continue;
					} else {
						if ($val === null) {
							$val = 'NO.';
						} elseif ($k === 8) {
							$val === '0' ? $val = 'No' : $val = 'Yes';
						}
						$new[] = $val;
					}
				}
			}
			if ($key === 0) {
				$this -> page_confirm_h2 = $value[0];
			}
		}
		$result[1] = $new;
		return $result;
	}

	public function wrapConfirm($tag) {
		if (is_numeric($this -> page_id)) {
			//$result = $this -> filterAddPageConfirm($page_id);
			$result = $this -> confirm_message;
			$wrap = array();
			$row = array();
			$wrap = $tag -> tag('div', 'class="confirm_wrapper spacer_3 center_box"', '');
			for ($i = 1; $i < count($result[1]); $i++) {
				$row[$i] = $tag -> tag('div', 'class="confirm_row section group"', '');
				$tag -> append_tag($row[$i], $tag -> tag('div', 'class="confirm_cell col half"', $result[0][$i]));
				$tag -> append_tag($row[$i], $tag -> tag('div', 'class="confirm_cell col half"', $result[1][$i - 1]));
				$tag -> append_tag($wrap, $row[$i]);
			}
			$tag -> docOutput($wrap);
		}
	}

	public function includeAddForm($tag) {
		//the las inserted page id
		$page_id = '';
		$modid = 0;
		if (isset($_POST['select_module']) || isset($_POST['addpage'])) {
			if (isset($_POST['module_id']) && isset($_POST['addpage'])) {
				if ($_POST['module_id'] > 1)
					$modid = $_POST['module_id'] - 1;
				$this -> module_name = $this -> modules[$modid]['name'];
				$page_id = $this -> addPage($dard);
				$this -> page_id = $page_id;
				if (!is_numeric($page_id)) {
					$this -> failedTransaction($dard);
					include_once 'template/module/page/forms/form-addpage.php';
				} else {
					if (is_numeric($page_id)) {
						$this -> confirm_message = $this -> filterAddPageConfirm($page_id);
						include_once 'template/module/page/confirm-message.php';
					}
				}
			} elseif (!isset($_POST['module_id']) && !isset($_POST['addpage'])) {
				include_once 'template/module/page/forms/form-addpage.php';
			}
		} elseif (empty($page_id) && !isset($_POST['addpage'])) {
			if (isset($_POST['select_module']) && $_POST['select_module'] === 'select_module') {
				$this -> setModuleIdName();
			} else {
				include_once 'template/module/page/forms/form-selet-module.php';
			}
		}
	}

	public function search($dard) {
		if (isset($dard -> url_arguments['search'])) {
			$param = $dard -> url_arguments['search'];
			$this -> wrapSearchAddErrorMessage($param, $dard);
			if (!$dard -> ajax) {
				return ;
			}
		}
	}

	private function searchPageSql($param, $dard) {
		$query = "SELECT P.`id`, P.`pagename`, P.`module_id`, M.`name` FROM `page` AS P , `module` AS M WHERE P.`pagename` LIKE '%" . $param . "%' AND P.`module_id` = M.`id` ;";
		$result = $dard -> selectDB($param, $query, TRUE, 'array');
		return $result;
	}

	private function wrapSearchAddErrorMessage($param, $dard) {
		$result = $this -> searchPageSql($param, $dard);
		$link = 'error-messages?a=add&pageid=';
		$print = '<div>';
		if (isset($result[0])) {
			foreach ($result as $key => $value) {
				$i = array();
				$name = array();
				foreach ($value as $k => $val) {
					if ($k === 'id' || $k === 'module_id')
						$i[] = $val;
					if ($k === 'pagename' || $k === 'name')
						$name[] = $val;
				}
				$data = 'data-pageid="'.$i[0].'" data-moduleid="'.$i[1]. '" data-pagename="'.$name[0]. '"';
				$print .= '<p><a href="#" '. $data .'><strong>' . $name[0] . '</strong> in module ' . $name[1] . '</a></p>' . "\n";
			}
		} elseif (isset($result['id'])) {
			$data = 'data-pageid="'.$result['id'].'" data-moduleid="'.$result['module_id'] . '" data-pagename="'.$result['pagename']. '"';
			$print .= '<p><a href="#" '. $data .'><strong>' . $result['pagename'] . '</strong> in module ' . $result['name'] . '</a></p>' . "\n";
		} elseif ($result === null) {
			$print .= '<p class="search-no-result">No results found</p>';
		}
		$print .= '</div>';

		printf("%s", $print);
	}

	public function slect_box($arg, $attr, $tag, $default, $param) {
		if(!is_array($arg))
			$arg = array();
		$tag -> print_select_option($attr, array_merge(array($default), $arg), 10);
	}
}
?>