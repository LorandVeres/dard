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

function server() {
    foreach ($_SERVER as $key => $value) {
        echo "$key : $value <br>\n";
    }
}

function pregMatchChecker(){
    $result = 'No data yet.';
    if(isset($_POST['string']) && isset($_POST['regex'])){
        $regex = "/^".$_POST['regex']."$/u";
        $string = $_POST['string'];
        $result = '';
        if (preg_match($regex, $string)) {
            $result = "Success for | $string | against | $regex ";
        } else {
            $result = "Failed for | $string | against | $regex |";
        }
    }
    return $result;
}

?>
<div id="content">
    <div class="row_6 center_box">
        <div>
            <h1>Regex Playground</h1>
        </div>
        <div class="form_row">
            <form action="/pages/regex-checker" method="post">
                <div>
                    <p><?php echo pregMatchChecker();?></p>
                </div>
                <div>
                    <label for="regex">Regex patern</label>
                    <input type="text" name="regex" id="regex" />
                </div>
                <div>
                    <label for="string">String</label>
                    <input type="text" name="string" id="string" />
                </div>
                <div class="center">
                    <input type="submit" value="send" />
                </div>
            </form>
        </div>
    </div>
</div>





<?php $myPage->ifNoAjaxBottom(); ?>