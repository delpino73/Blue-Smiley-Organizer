<?php

require 'class.base.php';
$base_instance=new base();

if (isset($_GET['id'])) {

	$file_id=sql_safe($_GET['id']);

	if (isset($base_instance->user)) {

	$userid=$base_instance->get_userid();

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID='$file_id' AND (user=$userid OR public=2)");

	}

	else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID='$file_id' AND public=2");

	}

}

else { exit; }

if (!$data) { $base_instance->show_message('File not found','',1); }

$filename=$data[1]->filename;
$title=$data[1]->title;
$text=$data[1]->text;
$userid=$data[1]->user;

$path=pathinfo($filename);

if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

if ($ext=='gif' or $ext=='png' or $ext=='jpg' or $ext=='jpeg') {

$url='./upload/'.$userid.'/'.$filename;

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type: image/jpeg');

readfile($url);

}

?>