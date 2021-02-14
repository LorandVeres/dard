<?php

class GetMyPage {

	protected $links;
	protected $top_page_name;
	protected $page_file_path;
	protected $relativePath;
	protected $sub_page_id;
	protected $class;
	protected $Meta;
	protected $linkTag;
	protected $URI;
	protected $isPage = 1;
	protected $allPage;
	protected $userPriv;
	protected $current_module_id;
	public $ajax = FALSE;
	public $arg = array();
	public $headers = array();
	public $page_crumbs;

	function __construct($config, $DBconect, $_DARDSESSI, $tag) {
		$_DARDSESSI -> init_user_session($config, $DBconect);
		$this -> getAllPage($config, $DBconect);
		$this -> generate_relative_path();
		$this -> sendDoc($config, $DBconect, $tag, $_DARDSESSI);
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

	private function setIfTopPage($config, $DBconect) {
		if (preg_match("/^[\w-]*$/", $this -> top_page_name)) {
			$this -> sqlTopPage($config, $DBconect);
		} else {
			$this -> isPage = 1;
			$this -> top_page_name = 'error';
		}
	}

	private function sqlTopPage($config, $DBconect) {
		$arg = $this -> top_page_name;
		isset($_SESSION['user_priv']) ? $user = $_SESSION['user_priv'] : $user = FALSE;
		$query = "SELECT `id`, `type`, `user_priv`, `parentpage`, `module_id` FROM `page` WHERE `pagename` = '$arg';";
		$result = $DBconect -> selectDB($arg, $config, $query, TRUE, 'array');
		if (!$result) {
			$this -> isPage = 1;
			$this -> top_page_name = 'error';
		} elseif ($result['id']) {
			if ($result['parentpage'] && ($result['type'] !== 'top' || $result['type'] !== 'main')) {
				$this -> isPage = 1;
			} else {
				$this -> isPage = $result['id'];
				$this -> userPriv = $result['user_priv'];
				$this -> current_module_id = $result['module_id'];
			}
		}
	}

	private function setIfSubPage($config, $DBconect) {
		$pos = strpos($this -> URI[1], '?');
		if ($pos !== false) {
			$sub_page = substr($this -> URI[1], 0, $pos);
			preg_match("/^[\w-]*$/", $sub_page) ? $this -> sqlSubPage($config, $DBconect, $sub_page) : $this -> sub_page_id = 1;
		} else {
			preg_match("/^[\w-]*$/", $this -> URI[1]) ? $this -> sqlSubPage($config, $DBconect, $this -> URI[1]) : $this -> sub_page_id = 1;
		}
	}

	private function sqlSubPage($config, $DBconect, $sub_page) {
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
		$result = $DBconect -> selectDB($arg, $config, $query, TRUE, 'array');
		if (!$result || $result['top_page_name'] !== $this -> top_page_name) {
			$this -> sub_page_id = 1;
			$this -> top_page_name = 'error';
		} elseif ($result) {
			if ($result['top_page_name'] !== $this -> top_page_name) {
				$this -> sub_page_id = 1;
				$this -> top_page_name = 'error';
			} else {
				$this -> sub_page_id = $result['id'];
				$this -> userPriv = $result['user_priv'];
				$this -> current_module_id = $result['module_id'];
			}
		}
	}

	//
	//Need attention
	//
	private function setIsPage($config, $DBconect) {
		$this -> genURI();
		$this -> getTopPageName();
		if (is_array($this -> URI) ? count($this -> URI) <= 1 || $this -> URI === null : FALSE) {
			$this -> setIfTopPage($config, $DBconect);
		} elseif (is_array($this -> URI) ? count($this -> URI) == 2 : FALSE) {
			$this -> setIfSubPage($config, $DBconect);
		} else {
			$this -> isPage = 1;
		}
	}

	//
	//Need attention
	//
	private function prepAllPage($config, $DBconect) {
		$this -> setIsPage($config, $DBconect);
		if (is_array($this -> URI) ? count($this -> URI) > 1 && $this -> sub_page_id > 1 : FALSE) {
			$this -> isPage = $this -> sub_page_id;
		} elseif (is_array($this -> URI) ? count($this -> URI) <= 1 && $this -> isPage > 1 : FALSE) {
			$this -> isPage;
		} else {
			$this -> isPage = 1;
			$this -> top_page_name = 'error';
		}
		if ($this -> isPage === 1)
			array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
	}

	private function getAllPage($config, $DBconect) {
		$this -> setLinkArguments();
		$this -> prepAllPage($config, $DBconect);
		$this -> checkUserPriv();
		$arg = $this -> isPage;
		$query = " 
            SELECT
                `pagename`,
                `title`,
                `pageURI`
            FROM
                `page`
            WHERE
                `id` = '$arg'
            ;";
		$query .= "
            SELECT
                `rel`,
                `type`,
                `href`
            FROM
                `css`
            WHERE
                `id` =(
                SELECT
                    `css`
                FROM
                    `page`
                WHERE
                    `id` = '$arg'
                ) OR `general` = 1;";
		$query .= "
            SELECT
                `name`, 
                `content`
            FROM 
                `pagemeta`
            WHERE 
                `active` = 1 AND (`general` = 1 OR `pageid` = '$this->isPage')
            ;";
		$result = $DBconect -> selectDB($arg, $config, $query, TRUE, 'default');
		$this -> allPage = $result[0];
		$this -> Meta = $result[2];
		$this -> linkTag = $result[1];
		$this -> setPageUri();
		$result = array();
		unset($result);
	}

	private function setPageUri() {
		$this -> page_file_path = $this -> allPage['pageURI'];
	}

	private function setLinkArguments() {
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
			$this -> arg = $arg;
		}
	}

	private function createDocTop($config) {
		$doc = "<!DOCTYPE html>\n";
		$doc .= "<html lang=" . $config -> language . ">\n";
		$doc .= $this -> createHtmlHead();
		$doc .= "\t<body>\n";
		return $doc;
	}

	private function createHtmlHead() {
		$head = "\t<head>\n";
		$head .= $this -> createHtmlMetaTags();
		$head .= $this -> createHtmlLinkTags();
		$head .= $this -> insertFavicon();
		$head .= "\t</head>\n";
		return $head;
	}

	private function createHtmlMetaTags() {
		$meta = "\t\t<meta charset=\"UTF-8\">\n";
		$meta .= "\t\t<title>" . $this -> allPage['title'] . "</title>\n";
		foreach ($this->Meta as $key => $value) {
			$meta .= "\t\t<meta name=\"" . $this -> Meta[$key]['name'] . "\" content=\"" . $this -> Meta[$key]['content'] . "\">\n";
		}
		return $meta;
	}

	private function createHtmlLinkTags() {
		$link = "";
		foreach ($this->linkTag as $key => $value) {
			$rel = $this -> linkTag[$key]['rel'];
			$type = $this -> linkTag[$key]['type'];
			$href = $this -> relativePath . $this -> linkTag[$key]['href'];
			$link .= "\t\t<link rel=\"" . $rel . "\" type=\"" . $type . "\" href=\"" . $href . "\" />\n";
		}
		return $link;
	}
	
	private function insertFavicon(){
		$favicon = "\t\t".'<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">'."\n";
		$favicon .= "\t\t".'<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">'."\n";
		$favicon .= "\t\t".'<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">'."\n";
		$favicon .= "\t\t".'<link rel="manifest" href="/site.webmanifest">'."\n";
		return $favicon;
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

	private function includeAjaxBody($config, $DBconect, $tag, $_DARDSESSI) {
		if (file_exists($this -> page_file_path)) {
			$myPage = $this;

			include_once $this -> page_file_path;
		} elseif (!file_exists($this -> page_file_path)) {
			array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
		}

	}

	private function printTopDoc($config, $DBconect, $tag, $_DARDSESSI) {
		if (printf("%s", $this -> createDocTop($config))) {
			if (file_exists($this -> page_file_path)) {
				$myPage = $this;

				include_once $this -> page_file_path;
			} elseif (!$this -> page_file_path) {
				array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
			}
		}
	}

	private function get_js_files($DBconect, $config) {
		$doc = '';
		if ($this -> current_module_id !== null) {
			$query = "SELECT `file` FROM `js_files` WHERE `module` = " . $this -> current_module_id . " AND `active` = 1;";
			$result = $DBconect -> selectDB('', $config, $query, false, 'array');
			if (!empty($result)) {
				foreach ($result as $key => $value) {
					$doc .= "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . $value . "\"></script>\n";
				}
			}
		}
		return $doc;
	}

	private function printBottomDoc($DBconect, $config) {
		$doc = "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . "src/js/dard.js\"></script>\n";
		$doc .= "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . "src/js/dialog.js\"></script>\n";
		$doc .= "\n\t\t<script type=\"text/javascript\" src=\"" . $this -> relativePath . "src/js/main.live.js\"></script>\n";
		$doc .= $this -> get_js_files($DBconect, $config);
		$doc .= "\t</body>\n";
		$doc .= "</html>\n";
		printf("%s", $doc);
	}

	private function sendDoc($config, $DBconect, $tag, $_DARDSESSI) {
		$this -> sendHeaders();
		if ($this -> checkAjax()) {
			$this -> includeAjaxBody($config, $DBconect, $tag, $_DARDSESSI);
		} else {
			$this -> printTopDoc($config, $DBconect, $tag, $_DARDSESSI);
			$this -> printBottomDoc($DBconect, $config);
		}
	}

	private function sendHeaders() {
		foreach ($this->headers as $value) {
			$value;
		}
	}

	private function checkUserPriv() {
		if ($this -> userPriv) {
			$priv = gmp_and(gmp_import($_SESSION['user_priv']), gmp_import($this -> userPriv));
			if (!gmp_strval($priv)) {
				array_push($this -> headers, header('HTTP/1.0 403 Forbidden'));
				$this -> isPage = 6;
				$this -> top_page_name = 'forbidden';
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

	public function crumbs() {
		$crumb = '';
		foreach ($this->URI as $value) {
			$crumb .= $value . ' >> ';
		}
		return trim($crumb, ">");
	}

}// end of class
?>