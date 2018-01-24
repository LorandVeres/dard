<?php //include_once 'template/layout/main/menu.php'; ?>

<?php //include_once 'template/layout/main/top-sticker.php'; ?>

<?php
function params() {
    echo '<br><br>';
    foreach ($myPage->pageArguments as $key => $val) {
        if (is_array($val)) {
            foreach ($val as $k => $value) {
                echo $k . ':' . $value . "\n";
            }
        } else {
            echo $key . ':' . $val . "\n";
        }
    }
}

function myheaders() {
    $headers = apache_request_headers();
    echo '<br><br>';
    foreach ($headers as $header => $value) {
        echo "$header: $value <br>\n";
    }
    echo '<br><br>';
}

//myheaders();
function sserver() {
    foreach ($_SERVER as $key => $value) {
        echo "$key : $value <br>\n";
    }
}


if(isset($_POST)){
    //var_dump($_POST);
}else{
    //echo 'POST not set';
    //var_dump($_GET);
}
?>













