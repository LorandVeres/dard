<?php
if(!defined('DARDSTATUS') ) exit();


function check_and_do($to_be_checked, $do){
    if($to_be_checked){
        $do;
    }else{
        return ;
    }
}

// $http_status_code 301 Moved Permanently
// $http_status_code 302 Found
// $http_status_code 303 See Other
// $http_status_code 307 Temporary Redirect

function redirect($where, $http_status_code){
    header("Location:".$where, TRUE, $http_status_code);
}


function postecho($value){
	if(isset($_POST[$value])){
		echo $_POST[$value];
	}else{
		return;
	}
}

?>