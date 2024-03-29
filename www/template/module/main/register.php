<?php
include_once '../module/user/Register.Class.php';
$new_user = new RegisterUser($dard, $tag);
include_once 'template/module/live/layout/menu.php';
?>
        <div id="main" class="section group">
        <?php include_once 'template/module/live/layout/top-sticker.php';  ?>


<div id="login">
    <div class="inner">
        <?php $new_user->html_wrap_errors($tag)?>
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
        <div class="login-links">
			<span class="c-box c-text span-100 space-20">By signing up you agree with our<br><a href="<?php mylink('terms-and-conditions'); ?> ">Terms and conditions</a> </span>
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
