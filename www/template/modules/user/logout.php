<?php

include_once '../lib/functions.php';

if (session_status() === 1) {
    include '../../lib/DardSession.Class.php';
    $_DARDSESSI = new DardSession();
}

$_DARDSESSI -> logout();

if (isset($_SERVER['HTTP_REFERER'])) {
    redirect($_SERVER['HTTP_REFERER'], 301);
} else {
    redirect($_SERVER['HTTP_HOST'], 301);
}
?>