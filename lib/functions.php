<?php
if (!defined('DARDSTATUS'))
	exit();

function check_and_do($to_be_checked, $do) {
	if ($to_be_checked) {
		$do;
	} else {
		return;
	}
}

// $http_status_code 301 Moved Permanently
// $http_status_code 302 Found
// $http_status_code 303 See Other
// $http_status_code 307 Temporary Redirect
// $http_status_code 403 Forbiden

function redirect() {
	$arg = func_get_args();
	if(func_num_args() === 1){
		header("Location:" . $arg[0]);
	}else{
		// $arg[1] = $myPage -> headers
		// $arg[1] = location address
		// $arg[2] = http status code
		array_push($arg[0], header("Location:" . $arg[1], TRUE, $arg[2]));
	}
}

function mylink($link) {
	if ($link === '/')
		$link = '';
	$url = '';
	$host = $_SERVER['HTTP_HOST'];
	$_SERVER["HTTPS"] == "on" ? $url .= 'https://' . $host : $url .= 'http://' . $host;
	$url .= '/' . $link;
	printf("%s", $url);

}

function postecho($value) {
	if (isset($_POST[$value])) {
		echo $_POST[$value];
	} else {
		return;
	}
}

function mysql_human_date($value, $date_type) {
	$date = '';
	//if (is_array($value)) {
		switch ($date_type) {
			case 'h' :
				$a = explode('-', $value);
				if(count($a) == 3)
					$date = $a[2] . '/' . $a[1] . '/' . $a[0];
				break;

			case 'm' :
				$a = explode('/', $value);
				if(count($a) == 3)
					$date = $a[2] . '-' . $a[1] . '-' . $a[0];
				break;
		}
	//}
	return $date;
}

function print_user_sign_in($tag) {
	$_SESSION['user_loged'] ? $user = $_SESSION['user_name'] : $user = 'Account';
	$login_div = $tag -> tag('div', 'class="col-r user-account-logins"', '');
	$tag -> append_tag($login_div, $tag -> tag('a', 'href="javascript:void(0)" class="user-account-name" onclick="toggle_login_menu()"', $user));
	$user_panel = $tag -> tag('div', 'class="user-account-logins-panel"', '');
	// If the user is not logged in
	if ($user === 'Account') {
		$tag -> append_tag($user_panel, $tag -> tag('a', 'href="/login"', 'Sign in'));
		$tag -> append_tag($user_panel, $tag -> tag('a', 'href="/register"', 'Register'));
	} else {
		$tag -> append_tag($user_panel, $tag -> tag('a', 'href="/account"', 'Account'));
		$tag -> append_tag($user_panel, $tag -> tag('a', 'href="/admin-dashboard"', 'Dashboard'));
		$tag -> append_tag($user_panel, $tag -> tag('hr', 'class="user-account-logins-panel-hr"', ''));
		$tag -> append_tag($user_panel, $tag -> tag('a', 'href="/logout"', 'Logout'));
	}
	//$tag -> append_tag($user_panel, $tag -> tag('a', 'href="recover-password"', 'Forgotten password'));
	$tag -> append_tag($login_div, $user_panel);
	$tag -> print_doc($login_div, 4);
}
?>