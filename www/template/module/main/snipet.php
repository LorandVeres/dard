<?php
include_once '../module/main/dsn_snipet.Class.php';

//$snipet = new dsn_snipet ($dard, $tag);


switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		
		if($dard -> ajax ) {
			$snipet = new dsn_snipet ($dard, $tag);
		}
		break;
	
	case 'GET':
		if($dard -> ajax ) {
			$snipet = new dsn_snipet ($dard, $tag);
		} else {
			if(isset($dard -> url_arguments['a']) ){
				$dard -> url_arguments['a'] !== 'responsive' ? include_once 'template/module/main/dsn.php' : $snipet = new dsn_snipet ($dard, $tag);
			}else{
				include_once 'template/module/main/dsn.php';
			}
		}
		
		break;
	default:
		// If no GET or POST method used
		// Alternative could be redirected to 500 Server error page
		// Loging of Request method could be implemented to be used in stats
		// code...
		break;
}

?>