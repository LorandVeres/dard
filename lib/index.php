<?php
$Forbid = explode('/', $_SERVER["REQUEST_URI"]);
foreach ($Forbid as $key => $value) {
	if ($Forbid[$key]=='lib') {
		exit();
	}
}

?>