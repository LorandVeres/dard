<?php
include '../lib/FormCleaner.Class.php';


/**
 * 
 */
class User extends FormCleaner{
	    
    
	function __construct($config, $DBconect) {
		parent::__construct($config);
	}
    
    
    protected function passw_hassh($passw, $config){
        $options = array ('cost' => $config->password_cost );
        return password_hash($passw, PASSWORD_BCRYPT , $options);
    }
        
}


?>