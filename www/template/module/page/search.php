<?php
include_once '../module/page/pages.Class.php';

$dard->ifNoAjaxTop($tag);

$setPage = new pages($dard, $tag);
$setPage->search($dard);




$dard->ifNoAjaxBottom();
?>