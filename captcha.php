<?php
	$random = md5(rand());
	$captcha = substr($random, 0, 6);
	setcookie('captcha',$captcha);
	$layer = imagecreatetruecolor(70,30);
	$bg = imagecolorallocate($layer, 255,240,240);
	imagefill($layer,0,0,$bg);
	$text_color =  imagecolorallocate($layer,0,0,0);
	imagestring($layer,5,5,5,$captcha,$text_color);
	header("Content-type: image/jpeg");
	imagejpeg($layer);
?>
