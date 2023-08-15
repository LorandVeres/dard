<?php
include '../module/user/login.Class.php';
$new_login = new login($dard, $tag);
include_once 'template/module/live/layout/menu.php';
?>
        <div id="main" class="section group">
        <?php include_once 'template/module/live/layout/top-sticker.php';  ?>

<div id="login">
    <div class="inner">
        <h1>Log in into Dard</h1>
        <?php $new_login->html_wrap_errors($tag);
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
        <div class="login-links">
            <span class="c-box c-text span-100 space-20">Don't you have an account? <br><a href="<?php mylink('register'); ?> ">Sign up</a> </span>
            <span class="c-box c-text span-100 b-space-20"> <a href="<?php mylink('forgot-password'); ?>">I forgot my password</a> </span>
        </div>
        <script>
    		(function(){
    			let el = document.getElementById("captcha");
    			el.style.backgroundImage  = "url('https://dard.dard/images/captcha.php')";
    			el.style.backgroundRepeat = "no-repeat";
    		}());
    	</script>
    </div>
</div>
        </div>
