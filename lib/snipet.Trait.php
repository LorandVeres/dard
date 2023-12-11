<?php
/**
*
*   Conventions
*   data attribute
*   @aAttribute data-dsninclude ="sandbox:snippet body"             @sandbox the project name and @snippet-body is the snippet name delimited by a (:) colon character
*   @aAttribute data-dsnthemebody ="true"                           Used only by the blog class to look for the blog theme and themebody. Otherwise consider data-dsninclude
*
*/

trait snipetHandler{

    /**
	 * undocumented function
	 *
	 * @return array
	 * @author  Lorand Veres
	 */
    function snippet_json_to_html($snippet, $tag) {
        $new_tag = array();
        $block = array();
        // Just for Blog.class, otherwise no checks on themebody, data-dsninclude should be considered instead of data-dsnthemebody
        // However for testing in the snippet creator when data-dsnthemebody is used consider keeping while testing the data-dsninclude
        isset( $this -> themebody ) ? $themebody = true : $themebody = false;
        $set_attributes = function($snippet_attr){
            $attr = '';
            foreach ($snippet_attr as $key => $value) {
                $attr .= ' '.$key.'="'.$value.'"';
            }
            return $attr;
        };
        
        $loop_snippet = function ($snipp, $tag) use ( &$set_attributes, &$new_tag) {
            $new_tag = $tag -> tag(strtolower($snipp['e_name']), $set_attributes($snipp['e_attr']), '');
			foreach ($snipp['e_content'] as $value) {
				if(is_array($value)){
					if(count($value) >=1){
						$tag -> append_tag($new_tag, $this -> snippet_json_to_html($value, $tag));
					}
				}else{
					if (is_string($value))
						$tag ->append_tag($new_tag, $tag -> tag('', '', $snipp['e_content']));
				}
			}
        };
        
        $dsn_include_append_on_array = function ($snipp, $tag, $new_tag, $included) use (&$set_attributes, &$themebody){
			$new_t = $new_tag;
			// Geting the included snippet from data base
			$temp_holder = $this -> get_include_snippet($included);
			// Hide the attribute from public
			if( isset( $snipp['e_attr']['data-dsninclude'] ) )
				unset( $snipp['e_attr']['data-dsninclude'] );
			if( isset( $snipp['e_attr']['data-dsnthemebody'] ) ){
				unset( $snipp['e_attr']['data-dsnthemebody'] );
			}
			// Creating the include container tag
			// Future implemetation to consider: A structure, for templating, without a real existing element
			$new_t = $tag -> tag(($snipp['e_name'] !== '#text' ? strtolower($snipp['e_name']) : ''), (isset($snipp['e_attr']) ? $set_attributes($snipp['e_attr']) : ''), '');
			if(count($snipp['e_content']) >= 1) {
				// Pushing up the existing elements before the included snippet
				foreach( $snipp['e_content'] as $key => $value) {
					if(is_array($value) && count($value) >=1)
						$tag -> append_tag($new_t , $this -> snippet_json_to_html($value, $tag));
					if (is_string($value))
						$tag ->append_tag($new_t, $tag -> tag('', '', $snipp['e_content']));
				}
				// Pushing up the included snippet
				if( $temp_holder && is_array($temp_holder))
					$tag -> append_tag($new_t, $this -> snippet_json_to_html($temp_holder, $tag));
			}
			return $new_t;
        };
        
        $tag_block = function ($snippet, $tag) use (&$set_attributes, &$new_tag, &$loop_snippet, &$themebody, &$dsn_include_append_on_array, &$dsn_include_append_on_text){
            if(isset($snippet['e_type'])){
				if(is_string($snippet['e_content'])){
					if(isset($snippet['e_attr']['data-dsnthemebody']) && $themebody ) {
						$new_tag = $dsn_include_append_on_array ($snippet, $tag, $new_tag, $this -> themebody);
					} else if(isset($snippet['e_attr']['data-dsninclude'])){
						$new_tag = $dsn_include_append_on_array ($snippet, $tag, $new_tag, $snippet['e_attr']['data-dsninclude']);
					} else {
						$new_tag = $tag -> tag(($snippet['e_name'] !== '#text' ? strtolower($snippet['e_name']) : ''), ( isset($snippet['e_attr']) ? $set_attributes($snippet['e_attr']) : '' ), $snippet['e_content']);
					}
				}elseif(is_array($snippet['e_content'])){
					if( $themebody ) {
						if( isset($snippet['e_attr']['data-dsnthemebody']) && $themebody ) {
							$themebody = false;
							$new_tag = $dsn_include_append_on_array ($snippet, $tag, $new_tag, $this -> themebody);
						} else {
							$loop_snippet($snippet, $tag);
						}
					} else if(isset($snippet['e_attr']['data-dsninclude'])){
						$new_tag = $dsn_include_append_on_array ($snippet, $tag, $new_tag, $snippet['e_attr']['data-dsninclude']);
					} else {
						$loop_snippet($snippet, $tag);
					}
				}
			}
        };
        if(isset($snippet['e_type'])){
            if( isset($snippet['e_content']))
                $tag_block($snippet, $tag);
            $block = $new_tag;
        }else{
            foreach ( $snippet as $value){
				if( isset($value['e_content']))
					$tag_block($value, $tag);
				$block[] = $new_tag;
            }
        }
        return $block;
    }
    
    function snippet_json_print($snippet, $tag) {
        $tag -> print_doc ( $this -> snippet_json_to_html($snippet, $tag), 1);
    }
    
    function get_include_snippet ($data) {
        $arr = explode(':', $data);
        if(count($arr) === 1) {
            $arr[1] = $arr[0];
            $arr[0] = $this -> snippet_project;
        }
        $query = "SELECT `body` FROM `dsn_".$arr[0]."` WHERE `name` = '".$arr[1]."';";
		$res = json_decode(trim($this ->selectDB('', $query, TRUE, 'string'), " ,\'\n\r\t\v\0"), TRUE);
        return $res;
    }
}

?>
