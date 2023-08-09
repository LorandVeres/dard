<?php
/**
*
*
*/

trait snipetHandler{

    /**
	 * undocumented function
	 *
	 * @return array
	 * @author  Lorand Veres
	 */
    function snipet_json_to_html($snipet, $tag) {
        $new_tag = array();
        $set_attributes = function($snipet_attr){
            $attr = '';
            foreach ($snipet_attr as $key => $value) {
                $attr .= ' '.$key.'="'.$value.'"';
            }
            return $attr;
        };
        
        $tag_block = function ($snipet, $tag) use (&$set_attributes, &$new_tag){
            if(isset($snipet['e_type'])){
				if(is_string($snipet['e_content'])){
					$new_tag = $tag -> tag(($snipet['e_name'] !== '#text' ? strtolower($snipet['e_name']) : ''), (isset($snipet['e_attr']) ? $set_attributes($snipet['e_attr']) : ''), $snipet['e_content']);
				}elseif(is_array($snipet['e_content'])){
					$new_tag = $tag -> tag(strtolower($snipet['e_name']), $set_attributes($snipet['e_attr']), '');
					foreach ($snipet['e_content'] as $value) {
						if(is_array($value)){
							if(count($value) >=1){
								$tag -> append_tag($new_tag, $this -> snipet_json_to_html($value, $tag));
							}
						}else{
							if (is_string($value))
								$tag ->append_tag($new_tag, $tag -> tag('', '', $snipet['e_content']));
						}
					}
				}
			}
        };
        if(isset($snipet['e_type'])){
            $tag_block($snipet, $tag);
        }else{
            foreach ( $snipet as $value){
				$tag_block($value, $tag);
            }
        }
        return $new_tag;
    }
}

?>
