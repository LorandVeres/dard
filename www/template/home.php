<?php

//check_and_do((!isset($_SESSION['user'])), redirect('login', 307));

?>

<div>
    <ul>
        <li><a href="<?php mylink('login') ?>">login</a></li>
        <li><a href="<?php mylink('register') ?>">register</a></li>
        <li><a href="<?php mylink('login/name') ?>">name</a></li>
    </ul>
</div>
