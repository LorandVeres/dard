<?php
include_once '../module/page/pages.Class.php';

$myPage->ifNoAjaxTop($tag);

$setPage = new pages($myPage, $tag);
$setPage->search($myPage);




$myPage->ifNoAjaxBottom();
?>