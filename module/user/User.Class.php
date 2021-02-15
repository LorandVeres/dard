<?php
include '../lib/FormCleaner.Class.php';


/**
 * 
 */
class User extends FormCleaner{
	    
    
	function __construct($myPage) {
		parent::__construct($myPage);
	}
    
    
    protected function passw_hassh($passw){
        $options = array ('cost' => $myPage -> cf_password_cost );
        return password_hash($passw, PASSWORD_BCRYPT,  $options);
    }
        
}


?>