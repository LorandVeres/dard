<?php

class GetMyPage extends DardSession {

	protected $links;
	protected $top_page_name;
	protected $page_file_path;
	protected $relativePath;
	protected $sub_page_id;
	protected $class;
	protected $meta_tags = array();
	protected $link_tags = array();
	protected $all_js_scripts = array();
	protected $body_js_scripts = array();
	protected $URI = array(); // $_SERVER["REQUEST_URI"] directories splitted in an array
	protected $current_page_id = 1;
	protected $all_page_properties;
	protected $current_page_groups_priv;
	protected $current_module_id;
	public $ajax = FALSE;
	public $url_arguments = array();
	public $headers = array();
	public $page_crumbs;
	public $dard_stats = array(); // statistics properties

	function __construct($tag) {
		$this -> init_user_session();
		$this -> getAllPage();
		$this -> generate_relative_path();
		$this -> sendDoc($tag);
	}

	public function fullURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on")
			$pageURL .= "s";
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80" || $_SERVER["SERVER_PORT"] != "443") {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;

	}

	private function genURI() {
		$uri = $_SERVER["REQUEST_URI"];
		if (preg_match("/^(\/home){1}$/", $uri))
			header("Location: /", TRUE, 301);
		$_SERVER["REQUEST_URI"] === "/" ? $this -> URI[0] = 'home' : $this -> URI = explode("/", trim($_SERVER["REQUEST_URI"], '/'));
	}

	private function generate_relative_path() {
		$relative = '';
		if (is_array($this -> URI)) {
			for ($i = 0; $i < count($this -> URI); $i++) {
				$relative .= '../';
			}
		}
		$this -> relativePath = $relative;
	}

	private function getTopPageName() {
		if ($this -> URI !== NULL) {
			$pos = strpos($this -> URI[0], '?');
			$pos ? $this -> top_page_name = substr($this -> URI[0], 0, $pos) : $this -> top_page_name = $this -> URI[0];
		} else {
			$this -> top_page_name = 'home';
		}
	}

	private function setIfTopPage() {
		if (preg_match("/^[\w-]*$/", $this -> top_page_name)) {
			$this -> sqlTopPage();
		} else {
			$this -> current_page_id = 1;
			$this -> top_page_name = 'error';
		}
	}

	private function sqlTopPage() {
		$arg = $this -> top_page_name;
		$query = "SELECT `id`, `type`, `user_priv`, `parentpage`, `module_id` FROM `page` WHERE `pagename` = '$arg';";
		$result = $this -> selectDB($arg, $query, TRUE, 'array');
		if (!$result) {
			$this -> current_page_id = 1;
			$this -> top_page_name = 'error';
		} elseif ($result['id']) {
			if ($result['parentpage'] && ($result['type'] !== 'top' || $result['type'] !== 'main')) {
				$this -> current_page_id = 1;
			} else {
				$this -> current_page_id = $result['id'];
				$this -> current_page_groups_priv = $result['user_priv'];
				$this -> current_module_id = $result['module_id'];
			}
		}
	}

	private function setIfSubPage() {
		$pos = strpos($this -> URI[1], '?');
		if ($pos !== false) {
			$sub_page = substr($this -> URI[1], 0, $pos);
			preg_match("/^[\w-]*$/", $sub_page) ? $this -> sqlSubPage($sub_page) : $this -> sub_page_id = 1;
		} else {
			preg_match("/^[\w-]*$/", $this -> URI[1]) ? $this -> sqlSubPage($this -> URI[1]) : $this -> sub_page_id = 1;
		}
	}

	private function sqlSubPage($sub_page) {
		$arg = $sub_page;
		$parent_id = "
                    SELECT
                        `parentpage`
                    FROM
                        `page`
                    WHERE
                        `pagename` = '$arg'
                    ";
		$query = "SELECT
                    S.`id`,
                    S.`pagename`,
                    S.`user_priv`,
                    S.`module_id`,
                    P.`pagename` AS top_page_name
                FROM
                    `page` AS S,
                    `page` AS P
                WHERE
                    P.`id` = ($parent_id) AND S.`pagename` = '$arg';";
		$result = $this -> selectDB($arg, $query, TRUE, 'array');
		if (!$result || $result['top_page_name'] !== $this -> top_page_name) {
			$this -> sub_page_id = 1;
			$this -> top_page_name = 'error';
		} elseif ($result) {
			if ($result['top_page_name'] !== $this -> top_page_name) {
				$this -> sub_page_id = 1;
				$this -> top_page_name = 'error';
			} else {
				$this -> sub_page_id = $result['id'];
				$this -> current_page_groups_priv = $result['user_priv'];
				$this -> current_module_id = $result['module_id'];
			}
		}
	}

	//
	//Need attention
	//
	private function setIsPage() {
		$this -> genURI();
		$this -> getTopPageName();
		if (is_array($this -> URI) ? count($this -> URI) <= 1 || $this -> URI === null : FALSE) {
			$this -> setIfTopPage();
		} elseif (is_array($this -> URI) ? count($this -> URI) == 2 : FALSE) {
			$this -> setIfSubPage();
		} else {
			$this -> current_page_id = 1;
		}
	}

	//
	//Need attention
	//
	private function prepAllPage() {
		$this -> setIsPage();
		if (is_array($this -> URI) ? count($this -> URI) > 1 && $this -> sub_page_id > 1 : FALSE) {
			$this -> current_page_id = $this -> sub_page_id;
		} elseif (is_array($this -> URI) ? count($this -> URI) <= 1 && $this -> current_page_id > 1 : FALSE) {
			$this -> current_page_id;
		} else {
			$this -> current_page_id = 1;
			$this -> top_page_name = 'error';
		}
		if ($this -> current_page_id === 1)
			array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
	}

	private function getAllPage() {
		$start = microtime(1);
		$this -> set_url_arguments();
		$this -> prepAllPage();
		$this -> checkUserPriv();
		$arg = $this -> current_page_id;
		$query = " 
            SELECT
                `pagename`,
                `title`,
                `file_path` AS page_file_path
            FROM
                `page`
            WHERE
                `id` = '$arg'
            ;";
		// Query for meta tags
		$query .= "SELECT `name`, `http-equiv`, `property`, `itemprop`, `content`, `charset`
					FROM `meta_tag`
					WHERE (`general` = 1 OR `per_module` = $this->current_module_id OR `all_public` = 'Y') AND `active` = 1
					UNION
					SELECT M.`name`, M.`http-equiv`, M.`property`, M.`itemprop`, M.`content`, M.`charset`
					FROM `meta_tag` AS M, `page_resources` AS R
					WHERE R.`type`='meta' AND R.`page_id` = $this->current_page_id AND M.`id` = R.`res_id`;";
		// Query for link tags
		$query .= "SELECT `rel`, `type`, `sizes`, `title`, `href`, `media`
					FROM `link_tag`
					WHERE (`general` = 1 OR `per_module` = $this->current_module_id OR `all_public` = 'Y') AND `active` = 1
					UNION
					SELECT L.`rel`, L.`type`, L.`sizes`, L.`title`, L.`href`, L.`media`
					FROM `link_tag` AS L, `page_resources` AS R
					WHERE R.`type`='link' AND R.`page_id` = $this->current_page_id AND L.`id` = R.`res_id`;";
		// Query for js scripts and files
		$query .= "SELECT `file`, `script`, `type`, `placement`
					FROM `js_files_script`
					WHERE (`general` = 1 OR `per_module` = $this->current_module_id OR `all_public` = 'Y') AND `active` = 1
					UNION
					SELECT J.`file`, J.`script`, J.`type`, J.`placement`
					FROM `js_files_script` AS J, `page_resources` AS R
					WHERE R.`type`='JS' AND R.`page_id` = $this->current_page_id AND J.`id` = R.`res_id`";
		$result = $this -> selectDB($arg, $query, TRUE, 'default');
		$this -> set_all_page_properties($result);
		$result = array();
		unset($result);
		$end = microtime(1);
		$this -> dard_stats['get_from_db_all_page']= $end - $start;
	}

	private function set_all_page_properties($result) {
		$this -> all_page_properties = $result[0];
		$this -> meta_tags = $result[1];
		$this -> link_tags = $result[2];
		$this -> js_scripts = $result[3];
		$this -> page_file_path = $this -> all_page_properties['page_file_path'];
	}

	private function set_url_arguments() {
		$pos = strpos($_SERVER["REQUEST_URI"], "?");
		$pos ? $argstr = substr($_SERVER["REQUEST_URI"], $pos + 1) : $argstr = '';
		$arg = array();
		if (!empty($argstr)) {
			$argpairs = explode('&', $argstr);
			foreach ($argpairs as $key => $value) {
				$param = substr($value, 0, strpos($value, '='));
				$val = substr($value, strpos($value, '=') + 1);
				$arg[$param] = $val;
			}
			$this -> url_arguments = $arg;
		}
	}

	private function create_doc_top($tag){
		
		$head = $tag -> tag('head', '', '');
		$prepare_attributes = function($value, $key){
			if($value !== null || !empty($value))
				return $key.'="'.$value.'" ';
		};
		
		$elements = function($tag, $varr, $el, $prepare, &$head){
			for ($i = 0 ; $i < count($varr); $i++) {
				$attr = '';
				foreach ($varr[$i] as $k => $val) {
					$attr .= $prepare ($val, $k);
				}
				$attr = substr_replace($attr, '', -1, strlen($attr));
				$tag -> append_tag($head, $tag -> tag($el, $attr, ''));
			}
		};
		
		$tag -> append_tag($head, $tag -> tag('title', '', $this -> all_page_properties['title']));
		$elements($tag, $this -> meta_tags, 'meta', $prepare_attributes, $head);
		$elements($tag, $this -> link_tags, 'link', $prepare_attributes, $head);
		
		$doc = "<!DOCTYPE html>\n";
		$doc .= "<html lang=" . $this -> cf_language . ">\n";
		printf("%s", $doc);
		$tag ->print_doc($head, 1);
		printf("%s", "\t<body>\n");
		//var_dump($head);
	}
	
	private function checkAjax() {
		$ajax = FALSE;
		$headers = apache_request_headers();
		if (array_key_exists('HTTP_X_REQUESTED_WITH', $headers))
			if ($headers['HTTP_X_REQUESTED_WITH'] === 'dard_ajax')
				$ajax = TRUE;
		$this -> ajax = $ajax;
		return $ajax;
	}

	private function includeAjaxBody($tag) {
		if (file_exists($this -> page_file_path)) {
			$dard = $this;

			include_once $this -> page_file_path;
		} elseif (!file_exists($this -> page_file_path)) {
			array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
		}

	}

	private function printTopDoc($tag) {
		if (file_exists($this -> page_file_path)) {
			$this -> create_doc_top($tag);
			$dard = $this;

			include_once $this -> page_file_path;
		} elseif (!$this -> page_file_path) {
			array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
		}
	}

	private function get_js_files() {
		$doc = '';
		if ($this -> current_module_id !== null) {
			$query = "SELECT `file` FROM `js_files_script` WHERE `per_module` = " . $this -> current_module_id . " AND `active` = 1;";
			$result = $this -> selectDB('', $query, false, 'array');
			if (!empty($result)) {
				foreach ($result as $key => $value) {
					$doc .= "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . $value . "\"></script>\n";
				}
			}
		}
		return $doc;
	}

	private function wrap_dard_Statistics(){
		$doc = '';
		if($this -> cf_dard_statisctics){
			$doc = "\t\t<div>\n";
			foreach ($this -> dard_stats as $key => $value) {
				$doc.= "\t\t\t<p>". $key ." = ". $value ."</p>\n";
			}
			$doc .= "\t\t</div>\n";
		}
		return $doc;
	}

	private function printBottomDoc() {
		$doc = "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . "src/js/dard.js\"></script>\n";
		$doc .= "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . "src/js/dialog.js\"></script>\n";
		$doc .= "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . "src/js/main.live.js\"></script>\n";
		$doc .= $this -> get_js_files();
		$doc .= $this -> wrap_dard_Statistics();
		$doc .= "\t</body>\n";
		$doc .= "</html>\n";
		printf("%s", $doc);
	}

	private function sendDoc($tag) {
		$this -> sendHeaders();
		if ($this -> checkAjax()) {
			$this -> includeAjaxBody($tag);
		} else {
			$this -> printTopDoc($tag);
			$this -> printBottomDoc();
		}
	}

	private function sendHeaders() {
		foreach ($this->headers as $value) {
			$value;
		}
	}

	private function checkUserPriv() {
		$query = "SET @nobody = (SELECT HEX ( `priv_flag` ) FROM `user_group` WHERE `id`=1);";
		if(!$_SESSION['user_loged']){
			$query .= "SET @comp = (SELECT HEX ( `priv_flag` ) FROM `user_group` WHERE `id` = 2);";
			$user_id = '';
		}else{
			isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : '';
			$query .= "SET @comp = (SELECT HEX ( P.`user_priv` & U.`u_group` ) FROM `page` AS P, `user` AS U WHERE P.`id` = $this->current_page_id AND U.`id` = $user_id );";
		}
		$query .= "SELECT @nobody <> @comp AS access;";
		$result = $this -> selectDB($user_id, $query, TRUE, 'array');
		if ($result['access'] !== '1') {
				array_push($this -> headers, header('HTTP/1.0 403 Forbidden'));
				$this -> current_page_id = 6;
				$this -> top_page_name = 'forbidden';
		}
	}

	public function ifNoAjaxTop($tag) {
		$crumbs = $this -> crumbs();
		if (!$this -> ajax) {
			include_once 'template/module/main/layout/menu.php';
			printf("\t\t<div id=\"main\" class=\"section group\">\n");
			include_once 'template/module/main/layout/top-sticker.php';
		}
	}

	public function ifNoAjaxBottom() {
		if (!$this -> ajax) {
			printf("\t\t</div>\n");
		}
	}

	public function crumbs() {
		$crumb = '';
		foreach ($this->URI as $value) {
			$crumb .= $value . ' >> ';
		}
		return trim($crumb, ">");
	}

}// end of class
?>