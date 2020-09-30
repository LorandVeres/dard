<?php

/**
 * 
 */
class FileManager {
	
	function __construct($file) {
		
	}
    
    public function fileBuffer($file){
        ob_start();
        if(is_array($file)){
            foreach ($file as $value) {
                include $value;
            }
        }elseif(is_string($file)){
            include $file;
        }
        $out =  ob_get_contents();
        ob_end_clean();
        return $out;
    }
}



?>