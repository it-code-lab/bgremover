<?php
session_start();
$code = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
$_SESSION["captcha_code"] = $code;

// Create image
header("Content-type: image/png");
$image = imagecreatetruecolor(120, 40);
$bg = imagecolorallocate($image, 240, 240, 240);
$fg = imagecolorallocate($image, 33, 33, 33);
imagefilledrectangle($image, 0, 0, 120, 40, $bg);
imagestring($image, 5, 30, 10, $code, $fg);
imagepng($image);
imagedestroy($image);
