<?php

session_start();

$rand=rand(10000,99999);

$_SESSION['image_random_value']=md5($rand);
$image=imagecreate(60,30);
imagecolorallocate($image,250,250,250);
$textcolor=imagecolorallocate($image,0,0,0);

imagestring($image,5,5,8,$rand,$textcolor);

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0',false);
header('Pragma: no-cache');
header('Content-type: image/jpeg');

imagejpeg($image);

imagedestroy($image);

?>