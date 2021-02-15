<?php
$start = microtime(1);
define('DARDSTATUS', 'ON');
//ini_set('xdebug.var_display_max_depth', 100);
//ini_set('xdebug.var_display_max_children', 256);
//ini_set('xdebug.var_display_max_data', 1024);

include_once '../config.php';

include_once '../lib/dbConect.Class.php';

include_once '../lib/DardSession.Class.php';
$_DARDSESSI = new DardSession();

include_once '../lib/GetMyPage.Class.php';

include_once '../lib/simpleTag.Class.php';
$tag = new simpleTag();

include_once '../lib/fileManager.Class.php';


include_once '../lib/functions.php';

$myPage = new GetMyPage($tag);


// Below this point all processing what is not
// important for data output

$myPage -> gc_session();

$end = microtime(1);
$load_time = $end - $start;
//echo $load_time;
?>
