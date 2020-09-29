<?php
$myPage->ifNoAjaxTop($tag);
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
function server() {
    foreach ($_SERVER as $key => $value) {
        echo "$key : $value <br>\n";
    }
}

function increment(&$var)
{
    $var++;
}


?>
<input type="button" name="dialog" value="dialog" id="dialog"/>





<?php $myPage->ifNoAjaxBottom(); ?>