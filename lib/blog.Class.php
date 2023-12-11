<?php
/**
*
*
*
*
*/

class blog extends dbConect{
	
	use snipetHandler;
	private $append_tag;
	private $theme;
	private $themebody;
	private $snippet_project;
	
	function __construct($tag, $dard) {
		if(isset( $dard -> page['theme']) && $dard -> page['theme'] !== '') {
			$this -> theme = trim(substr( $dard -> page['theme'], stripos($dard -> page['theme'], ':')+1));
			$this -> snippet_project = trim(substr( $dard -> page['theme'], 0, stripos($dard -> page['theme'], ':')));
		}
		if(isset( $dard -> page['themebody']) && $dard -> page['themebody'] !== ''){
			$this -> themebody = $dard -> page['themebody'];
		}
	}
	
	private function blog_get_print_snippet($project, $snippet, $tag) {
		$query = "SELECT `body` FROM `dsn_".$project."` WHERE `name` = '".$snippet."';";
		$res = json_decode(trim($this ->selectDB('', $query, TRUE, 'string'), " ,\'\n\r\t\v\0"), TRUE);
		$this -> snippet_json_print( $res, $tag );
	}
	
	public function print_page( $tag ) {
		$this -> blog_get_print_snippet($this -> snippet_project, $this -> theme, $tag);
	}

}
$blog = new blog($tag, $dard);
?>