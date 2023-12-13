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
	protected $body_js_scripts = '';
	protected $head_js_sripts = array();
	protected $URI = array(); // $_SERVER["REQUEST_URI"] directories splitted in an array
	public $page = array();
	public $ajax = FALSE;
	public $url_arguments = array();
	public $headers = array();
	public $page_crumbs;
	public $dard_stats = array(); // statistics properties

	function __construct($tag) {
		$this -> getAllPage();
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
				if($i >= 1)
					$relative .= '../';
			}
		}
		$this -> relativePath = $relative;
	}

	private function set_up_error_page_headers($num) {
		$header;
		switch ($num) {
			case '404':
				$header = header('HTTP/1.0 404 Not Found');
				break;
			case '403':
				$header = header('HTTP/1.0 403 Forbidden');
				break;
			case '500':
				$header = header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
				break;
		}
		$this -> top_page_name = $num.'_error';
		array_push($this -> headers, $header);
	}

	private function prep_all_page_properties($arg) {
		if(is_array($arg)) {
			$this -> page['id'] = $arg['id'];
			$this -> page['module_id'] = $arg['module_id'];
			$this -> page['name'] = $arg['pagename'];
			$this -> page['accesskey'] = $arg['access'];
			$this -> page['type'] = $arg['type'];
			$this -> page['title'] = $arg['title'];
			$this -> page['file'] = $arg['file_path'];
			if ( isset($arg['template']) ) $this -> page['theme'] = $arg['template'];
			if( isset($arg['body']) ) $this -> page['themebody'] = $arg['body'];
			if(  isset( $arg['sassets'] ) ) $this -> secure_assets_fill( $arg['sassets']);
		}
	}

	private function get_error_page($num) {
		$error_page = $num.'_error';
		$query = "SELECT `id`, `pagename`, `type`, `title`, `access`, `file_path`, `module_id`  FROM `page` WHERE `pagename` = '$error_page';";
		$result = $this -> selectDB($num, $query, TRUE, 'array');
		$this -> prep_all_page_properties($result);
		$this -> set_up_error_page_headers($num);
	}

	/**
	 * undocumented function
	 *
	 * @return bool
	 * @author  Lorand Veres
	 */
	private function check_active_page_status($tatus) {
		$bool = FALSE;
		if(is_string($tatus))
			$tatus === "activ" ? $bool = TRUE : $bool = FALSE;
		return $bool;
	}

	private function sql_top_page() {
		$query = "SELECT `id`, `pagename`, `type`, `title`, HEX(`access`) AS access, `file_path`, `module_id`, `sassets`, `parentpage`, `status`, `template`, `body`  FROM `page` WHERE `pagename` = '$this->top_page_name' AND  `parentpage` IS NULL;";
		$result = $this -> selectDB($this -> top_page_name, $query, TRUE, 'array');
		if( !$result){
			$this -> get_error_page('404');
		} else if( $result['parentpage'] !== NULL && ($result['type'] != 'top' || $result['type'] != 'main')){
			$this -> get_error_page('500');
		} elseif(is_array($result) && !empty($result)) {
			$this -> check_active_page_status($result['status']) ? $this -> prep_all_page_properties($result) : $this -> get_error_page('404');
		}
	}

	private function sql_sub_page($sub_page) {
		$query = "SET @parent_id = (SELECT `parentpage` FROM `page` WHERE `pagename` = '$sub_page');";
		$query .= "SELECT S.`id`, S.`pagename`, S.`type`, S.`title`, HEX(S.`access`) AS access, S.`file_path`, S.`module_id`, S.`status`, S.`template`, S.`body`,  P.`pagename` AS top_page_name FROM `page` AS S, `page` AS P WHERE P.`id` = @parent_id AND S.`pagename` = '$sub_page';";
		$result = $this -> selectDB($sub_page, $query, TRUE, 'array');
		if (!$result || $result['top_page_name'] !== $this -> URI[0]) {
			$this -> get_error_page('404');
		} else if(is_array($result) && !empty($result)) {
			if($this -> check_active_page_status($result['status'])) {
				$this -> top_page_name = $this -> URI[0];
				$this -> prep_all_page_properties($result);
			}else{
				$this -> get_error_page('404');
			}
		}
	}

	private function get_top_page_from_url() {
		if ($this -> URI !== NULL) {
			$pos = strpos($this -> URI[0], '?');
			$pos ? $this -> top_page_name = substr($this -> URI[0], 0, $pos) : $this -> top_page_name = $this -> URI[0];
		} else if($this -> URI === NULL){
			$this -> top_page_name = 'home';
		}
		preg_match("/^[\w-]*$/u", $this -> top_page_name) ? $this -> sql_top_page() : $this -> get_error_page('404');
	}

	private function get_sub_page_from_url(){
		if (count($this -> URI) >= 2) {
			$pos = strpos($this -> URI[1], '?');
			$pos ? $sub_page = substr($this -> URI[1], 0, $pos) : $sub_page = $this -> URI[1];
			preg_match("/^[\w-]*$/u", $sub_page) ? $this -> sql_sub_page($sub_page) : $this -> get_error_page('404');
		}
	}

	private function chose_page_type() {
		$this -> genURI();
		$this -> generate_relative_path();
		if($this -> URI === NULL) {
			$this -> get_top_page_from_url();
		} else if(Count($this -> URI) === 1) {
			$this -> get_top_page_from_url();
		} else if(count($this -> URI) >=2 ) {
			$this -> get_sub_page_from_url();
		}
	}

	private function check_user_priv() {
		if( !isset( $this -> page['id'] ) || $this -> page['id'] === NULL ) {
			$this -> get_error_page('500');
		} else if( ( $this -> page['type'] === 'main' || $this -> page['type'] === 'main-sub') && ( !isset($_SESSION['user_loged'] ) || !$_SESSION['user_loged'] ) ) {
			//Preventing crawlers and boots from getting info about the admin side 
			$this -> get_error_page('404');
		} else {
			$params = array( $this -> page['id'] );
			if(!isset($_SESSION['user_loged']) || !$_SESSION['user_loged']){
				$params[1] = 0;
			}elseif(isset($_SESSION['user_id'])){
				$params[1] = $_SESSION['user_id'];
			}else{
				$this -> get_error_page('500');
			}
			$result = $this ->stmt ('', $params, 'checkUserPrivilegePerPage', $params);
			if ($result['access'] === NULL || $result['access'] === '0') {
					$this -> get_error_page('403');
			}
		}
	}

	private function set_snippet_page_head_properties() {
		// temporary patch 
		$responsive = false;
		$csspath = array();
		$href = ''; $newtags = array();
		
		if ( $this -> top_page_name === 'snippet' ) {
			if(!$this -> ajax && !isset($_SESSION['snippet']['project-name'])){
				$_SESSION['snippet']['project-name'] = 'sandbox';
			}
			// Reset for page reloads
			if (!$this -> ajax && !isset($this -> url_arguments['a']) &&  $_SERVER['REQUEST_METHOD']  === 'GET') {
				if( isset($_SESSION['snippet']['cssfiles']) )
					$_SESSION['snippet']['cssfiles'] = array();
				$_SESSION['snippet']['project-name'] = 'sandbox';
			}
			if(isset($this -> url_arguments['a'] ) && $this -> url_arguments['a'] === 'responsive')
				$responsive = true;
			
			// By default dard.js file it will be included
			// dard_snippet.js will go from secure assets directory. Consider removing it for responsive
			$dardjs = array( "file"=>"src/js/dard.js" , "script"=>NULL ,"type"=>"file", "placement"=>"body");
			$this -> all_js_scripts = array ();
			array_push( $this -> all_js_scripts, $dardjs );
			if( $responsive && !$this -> ajax && $_SERVER['REQUEST_METHOD']  === 'GET') {
				if ( isset( $this -> page['sassets']['js'] )) {
					foreach ( $this -> page['sassets']['js'] as $key => $value) {
						if( preg_match("/dard_snippet\.js/", $value) )
							unset(  $this -> page['sassets']['js'][$key] );
					}
				}
			}

			// Sorting css. Deleting unecesary potential general css included in main module
			foreach ($this -> link_tags as $key => $value) {
				if (isset($value['rel']) && $value['rel'] === 'stylesheet') {
					$href = $value['href'];
					if( $href === "/src/css/reset.css" || $href === "/src/css/dsn.css" || $href === "/src/css/ui.css"){
						if($responsive) {
							$newtags[] = $value;
						}else {
							$newtags[] = $value;
						}
					}
				}else {
					$newtags[] = $value;
				}
			}
			// Adding the default project css, just if the file exist
			if( isset($_SESSION['snippet']['project-name']) && is_array($csspath))
				if( file_exists( "/src/css/dsn/" .$_SESSION['snippet']['project-name'] . ".css" ))
					array_push( $csspath,  "/src/css/dsn/" .$_SESSION['snippet']['project-name'] . ".css");
			// Adding the css files set from snippet page for the active snippet in responsive mod
			if($responsive && isset( $_SESSION['snippet']['cssfiles'] ) && count($_SESSION['snippet']['cssfiles']) > 0) {
				if ( isset( $_SESSION['snippet']['cssfiles']['read'] ) ) {
					foreach ( $_SESSION['snippet']['cssfiles']['read'] as $value ) { 
						$this -> page['sassets']['css'][] = $value;
					}
				}
				if( count($_SESSION['snippet']['cssfiles']) > 0 ) {
					foreach ( $_SESSION['snippet']['cssfiles'] as $key => $value ) { 
						if( $key !== 'read')
						$csspath[] = $value;
					}
				}
			}
			// Finalizing the newtags array before pushing to $this -> link_tags 
			if(is_array($csspath) && count($csspath) >= 1 ){
				foreach ($csspath as $val) {
					array_push($newtags, array(
						"rel"=>"stylesheet",
						"type"=>"text/css",
						"href"=> $val
					));
				}
			}
			$this -> link_tags = $newtags;
		} 
	}
	
	private function set_page_head_properties($result) {
		count($result) >= 1 ? $this -> meta_tags = $result[0] : '';
		count($result) >= 2 ? $this -> link_tags = $result[1] : '';
		count($result) >= 3 ? $this -> all_js_scripts = $result[2] : '';
		$this -> set_snippet_page_head_properties();
	}
	
	private function set_js_script_tags() {
		$this -> secure_assets_js();
		$body = '';
		$head = array();
		$string = function($a, $path){
			$src = '';
			if($a["type"] === "file"){
				$src = "\n\t\t<script src=\"" . $path . $a["file"] . "\"></script>";
			}else if(($a["type"] === "script")){
				$src = "\n\t\t<script>". $a["script"] ."</script>";
			}
			return $src;
		};
		$thag = function($a, $path) use (&$head){
			$src = array();
			if($a["type"] === "file"){
				$src[] = "<script src=\"" . $path . $a["file"] . "\">";
				$src[] ="";
				$src[] ="</script>";
			}else if(($a["type"] === "script")){
				$src[] = "<script>";
				$src[] = $a["script"];
				$src[] ="</script>";
			}
			if(is_array($head))
				array_push($head, $src);
		};
		
		$head_fn = function($a, $p) use (&$thag){
			if($a['placement'] === "head")
				$thag($a, $p);
		};
		$body_fn = function($a, $path) use (&$string, &$body){
			if($a['placement'] === "body")
				$body .= $string($a, $path);
		};
		$loop = function($js, $path) use (&$head_fn, &$body_fn){
			foreach ($js as $value) {
				$head_fn($value, $path);
				$body_fn($value, $path);
			}
		};
		if (!empty($this ->all_js_scripts)) {
			$loop($this ->all_js_scripts, $this ->relativePath);
			$this ->head_js_sripts = $head;
			$this ->body_js_scripts = $body."\n";
		}
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

	private function getAllPage() {
		$start = microtime(1);
		$this -> chose_page_type();
		$this -> check_user_priv();
		$this -> set_url_arguments();
		$query = "SELECT `name`, `http-equiv`, `property`, `itemprop`, `content`, `charset`
					FROM `meta_tag`
					WHERE (`general` = 1 OR `per_module` = ".$this -> page['module_id']." OR `all_public` = 'Y') AND `active` = 1
					UNION
					SELECT M.`name`, M.`http-equiv`, M.`property`, M.`itemprop`, M.`content`, M.`charset`
					FROM `meta_tag` AS M, `page_resources` AS R
					WHERE R.`type`='meta' AND R.`page_id` = ".$this -> page['id']." AND M.`id` = R.`res_id`;";
		// Query for link tags
		$query .= "SELECT `rel`, `type`, `sizes`, `title`, `href`, `media`
					FROM `link_tag`
					WHERE (`general` = 1 OR `per_module` = ".$this -> page['module_id']." OR `all_public` = 'Y') AND `active` = 1
					UNION
					SELECT L.`rel`, L.`type`, L.`sizes`, L.`title`, L.`href`, L.`media`
					FROM `link_tag` AS L, `page_resources` AS R
					WHERE R.`type`='link' AND R.`page_id` = ".$this -> page['id']." AND L.`id` = R.`res_id`;";
		// Query for js scripts and files
		$query .= "SELECT `file`, `script`, `type`, `placement`
					FROM `js_files_script`
					WHERE (`general` = 1 OR `per_module` = ".$this -> page['module_id']." OR `all_public` = 'Y') AND `active` = 1
					UNION
					SELECT J.`file`, J.`script`, J.`type`, J.`placement`
					FROM `js_files_script` AS J, `page_resources` AS R
					WHERE R.`type`='js' AND R.`page_id` = ".$this -> page['id']." AND J.`id` = R.`res_id` AND J.`active` = 1";
		$result = $this -> selectDB($this -> page['id'], $query, TRUE, 'default');
		$this -> set_page_head_properties($result);
		$this ->set_js_script_tags();
		$result = array();
		unset($result);
		$end = microtime(1);
		$this -> dard_stats['get_from_db_all_page']= $end - $start;
	}
	private function create_doc_top($tag){
		
		$head = $tag -> tag('head', '', '');
		$prepare_attributes = function($value, $key){
			if(is_string($value) && isset( $key ) && is_string( $key )){
				return $key.'="'.$value.'" ';
			}
		};
		
		$elements = function($tag, $varr, $el) use (&$prepare_attributes, &$head){
			for ($i = 0 ; $i < count($varr); $i++) {
				$attr = '';
				foreach ($varr[$i] as $k => $val) {
					$attr .= $prepare_attributes ($val, $k);
				}
				$attr = substr_replace($attr, '', -1, strlen($attr));
				$tag -> append_tag($head, $tag -> tag($el, $attr, ''));
			}
		};
		
		$js = function($js_tags, $tag) use (&$head){
			if(!empty($js_tags)){
				for ($i=0; $i < count($js_tags); $i++) { 
					$tag ->append_tag($head, $js_tags[$i]);
				}
			}
		};
		
		$tag -> append_tag($head, $tag -> tag('title', '', $this -> page['title']));
		$elements($tag, $this -> meta_tags, 'meta');
		$elements($tag, $this -> link_tags, 'link');
		$js($this ->head_js_sripts, $tag);
		$this -> secure_assets_css( $head, $tag);
		
		
		$doc = "<!DOCTYPE html>\n";
		$doc .= "<html lang=" . $this -> cf_language . ">\n";
		printf("%s", $doc);
		$tag ->print_doc($head, 1);
		printf("%s", "\t<body>\n");
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
		if (file_exists($this -> page['file'])) {
			$dard = $this;

			include_once $this -> page['file'];
		} elseif (!file_exists($this -> page['file'])) {
			$this -> get_error_page('500');
		}

	}

	private function printTopDoc($tag) {
		if (file_exists($this -> page['file'])) {
			$this -> create_doc_top($tag);
			$dard = $this;

			include_once $this -> page['file'];
		} elseif (!file_exists($this -> page['file'])) {
			$this -> get_error_page('500');
			$this -> create_doc_top($tag);
		}
	}

	private function wrap_dard_Statistics(){
		$doc = '';
		if($this -> cf_dard_statisctics){
			$doc = "\t\t<div class=\"debug\">\n";
				foreach ($this ->dard_stats as $key => $value) {
					$doc .= "\t\t\t<p>". $key ." = ". $value ."</p>\n";
				}
			$doc .= "\t\t</div>\n";
		}
		return $doc;
	}

	private function printBottomDoc() {
		$doc = $this -> wrap_dard_Statistics();
		$doc .= $this -> body_js_scripts;
		$doc .= "\t</body>\n";
		$doc .= "</html>\n";
		printf("%s", $doc);
	}

	private function sendHeaders() {
		foreach ($this->headers as $value) {
			$value;
		}
	}

	private function sendDoc($tag) {
		$this -> sendHeaders();
		if ($this -> checkAjax()) {
			$this -> includeAjaxBody($tag);
		} else {
			if( ( $this -> page['type'] === 'blog' || $this -> page['file'] === NULL ) && isset($this -> page['theme']) && $this -> page['theme'] !== ''){
				$dard = $this;
				include '../lib/blog.Class.php';
				$this -> create_doc_top($tag);
				$blog -> print_page( $tag );
				$this -> printBottomDoc();
			} else { 
				if ( file_exists($this -> page['file'])) { 
					$this -> printTopDoc($tag);
					$this -> printBottomDoc();
				} else {
					$this -> get_error_page('500');
					$this -> create_doc_top($tag);
					include_once $this -> page['file'];
					$this -> printBottomDoc();
				}
			}
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
	
	private function secure_assets_fill(){
		$arg = func_get_args();
		if($arg[0] === NULL) {
			$this -> page['sassets'] = NULL;
			return;
		} else if(is_array($arg) && $arg[0] !== NULL){
			$arg = explode('|', $arg[0]);
			foreach($arg as $value) {
				$key = substr($value, 0, strpos($value, ':'));
				$this -> page['sassets'][$key] = explode( ',', substr($value, strpos($value, ':')+1));
			}
		}
	}
	
	private function secure_assets_css(&$head, $tag) {
		if(isset( $this -> page['sassets']['css'] )) {
			$string = '';
			foreach( $this -> page['sassets']['css'] as $val ) {
				if(is_file('../'.$val))
					$string .= read_file_to_variable( '../'.$val);
					$string = str_replace("\t", "",str_replace("\n", "", $string));
					$tag -> append_tag($head, $tag -> tag( 'style' , '', $string) );
			}
		}
	}
	
	private function secure_assets_js() {
		if(isset( $this -> page['sassets']['script'] )) {
			$string = '';
			foreach( $this -> page['sassets']['script'] as $val ) {
				if(is_file('../'.$val))
					$string .= read_file_to_variable( '../'.$val);
					array_push( $this -> all_js_scripts, array( 'file' => NULL, 'script' => $string, 'type' => 'script', 'placement' => 'body'));
			}
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