<?php
include '../lib/FormCleaner.Class.php';


/**
 * 
 */
class User extends FormCleaner{
	    
    
	function __construct($dard) {
		parent::__construct($dard);
	}
    
    
    protected function passw_hassh($passw){
        $options = array ('cost' => $this -> cf_password_cost );
        return password_hash($passw, PASSWORD_BCRYPT,  $options);
    }
        
}


?>