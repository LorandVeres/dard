<?php


if(session_status() === 1){
	include_once '../../config.php';
	include_once '../../lib/dbConect.Class.php';
	include '../../lib/DardSession.Class.php';
	$_DARDSESSI = new DardSession();
}

function GenerateCaptcha(){
        $i = 0; $captcha ='';
        while($i < 4){
            $captcha .= chr(rand(ord('0'), ord('9')));
            $i++;
        }
        return $_SESSION['captcha'] = $captcha;
    }

function captcha($string){
    header('Content-type: image/jpeg');
    $img = imagecreate(50, 50);
    $background_color = imagecolorallocate($img, 54, 59, 65);
    $color = imagecolorallocate($img, 113, 121, 130);
    $fontfile = '/usr/share/fonts/truetype/freefont/FreeSans.ttf';
    imagettftext ($img, 13, 0, 7, 30, $color, $fontfile, $string);
    imagejpeg($img);
    imagedestroy($img);
}

captcha(GenerateCaptcha());

?>