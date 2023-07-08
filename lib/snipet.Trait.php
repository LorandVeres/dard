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
        if(isset($snipet['e_type'])){
            if(is_string($snipet['e_content'])){
                $new_tag = $tag -> tag(($snipet['e_name'] !== '#text' ? strtolower($snipet['e_name']) : ''), (isset($snipet['e_attr']) ? $set_attributes($snipet['e_attr']) : ''), $snipet['e_content']);
            }elseif(is_array($snipet['e_content'])){
                $new_tag = $tag -> tag(strtolower($snipet['e_name']), $set_attributes($snipet['e_attr']), '');
                foreach ($snipet['e_content'] as $value) {
                    if(is_array($value)){
                        if(count($value) >=1){
                            $tag -> append_tag($new_tag, $this -> snipet_json_to_html($value, $tag));
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
    }
}

?>
