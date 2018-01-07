<?php
include '../module/user/login.Class.php';
$new_login = new login($config, $DBconect, $tag);
?>

<div id="login">
    <div class="inner">
        <h1>Log in to Dard</h1>
        <?php $new_login->html_wrap_errors($config, $DBconect, $tag);
        ?>
        <form name='form-login' method="post" action="/login">
            <span class="user"></span>
            <input type="email" id="user" placeholder="Email" name="email">

            <span class="passwd"></span>
            <input type="password" id="pass" placeholder="Password" name="password">

            <span id="captcha"></span>
            <input type="text" id="security" placeholder="Left side number?" name="captcha">

            <input type="submit" value="Login">

        </form>
        <nav>
            <span class="left hr"> <a href="register">Register account</a> </span>
            <span class="right hr"> <a href="forgot-password">Forgot password</a> </span>
        </nav>
    </div>
</div>
