<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$file_id=isset($_REQUEST['file_id']) ? (int)$_REQUEST['file_id'] : '';

$data=$base_instance->get_data("SELECT filename,user FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID='$file_id' AND (user=$userid OR public=2)");

if (empty($data)) { $base_instance->show_message('File entry not found!'); exit; }

$filename=$data[1]->filename;
$userid=$data[1]->user;

$url='./upload/'.$userid.'/'.$filename;

if (file_exists($url)) {

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename="'.$filename.'";');
#header('Content-Length: '.filesize($url));

set_time_limit(0);

readfile($url) or die();

} else { $base_instance->show_message('File not found'); }

?>