<?php
include_once '../module/user/Register.Class.php';
$new_user = new RegisterUser($config, $DBconect, $tag);
include_once 'template/module/live/layout/menu.php';
?>
        <div id="main" class="section group">
        <?php include_once 'template/module/live/layout/top-sticker.php';  ?>


<div id="login">
    <div class="inner">
        <?php $new_user->html_wrap_errors($config, $DBconect, $tag)?>
        <h1> Register Account </h1>
        <form name='form-register' method="post" action="/register">
            <span class="user"></span>
            <input type="email" id="email" placeholder="Email" name="email">

            <span class="passwd"></span>
            <input type="password" id="pass" placeholder="Password" name="password">
            
            <span class="passwd"></span>
            <input type="password" id="pass1" placeholder=" Re type Password" name="password1">

            <span id="captcha"></span>
            <input type="text" id="security" placeholder="Left side number?" name="captcha">

            <input type="submit" value="Register">

        </form>
    </div>
</div>
        </div>
