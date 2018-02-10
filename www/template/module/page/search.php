<?php
include_once '../module/page/pages.Class.php';

$myPage->ifNoAjaxTop();

$setPage = new pages($config, $DBconect, $myPage, $tag);
$setPage->search($config, $DBconect, $myPage);




$myPage->ifNoAjaxBottom();
?>