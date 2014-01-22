<?php

require 'class.base.php';
$base_instance=new base();

if (isset($_REQUEST['token'])) { $token=sql_safe($_REQUEST['token']); } else { exit; }

if (isset($_POST['save'])) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE token='$token'");

if (!$data) { echo 'File not found / not public anymore'; exit; }

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

@readfile($url) or die();

} else {

header('HTTP/1.1 404 Not Found');

$base_instance->show_message('File not found');

}

}

if ($token) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE token='$token'");

if (!$data) {

header('HTTP/1.1 404 Not Found');

$base_instance->show_message('File not found','<font face="Verdana" size="1" color="#636363">Powered by <a href="http://www.bookmark-manager.com/" target="_blank"><font color="#191970">Blue Smiley Organizer</font></a></font>');

}

$file_id=$data[1]->ID;
$filename=$data[1]->filename;
$title=$data[1]->title;
$text=$data[1]->text;
$userid=$data[1]->user;

$filesize=filesize('./upload/'.$userid.'/'.$filename);

if ($filesize > 1048576) { $filesize2=round($filesize/1048576,1).' MB'; }
else if ($filesize > 1024) { $filesize2=round($filesize/1024,1).' KB'; }
else { $filesize2=$filesize.' Bytes'; }

#

$path=pathinfo($filename);
if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

if ($ext=='gif' or $ext=='png' or $ext=='jpg' or $ext=='jpeg') { $show_image='&nbsp;&nbsp; <img src="get-image.php?id='.$file_id.'" border="0" alt="'.$title.'"><p><a href="get-image.php?id='.$file_id.'">[Printable version]</a>'; } else { $show_image=''; }

#

$all_text='

'.$show_image.'

<div align="center">

<table>';

if (!$title) { $title='File Download'; }

$all_text.='<tr><td><strong>Filesize:</strong></td><td>'.$filesize2.'</td></tr>
<tr><td><strong>Filename:</strong></td><td>'.$filename.'</td></tr>';

$text=$base_instance->insert_links($text);

if ($text) { $all_text.='<tr><td valign="top"><strong>Description:</strong></td><td>'.$text.'</td></tr>'; }

$all_text.='

</table><br>

<form action="show-file-public.php" method="post">
<input type="hidden" name="token" value="'.$token.'">
<input type="SUBMIT" value="Download" name="save">
</form>

</div><br>

<font face="Verdana" size="1" color="#636363">Powered by <a href="http://www.bookmark-manager.com/" target="_blank"><font color="#191970">Blue Smiley Organizer</font></a></font>

<br><br>

<table cellspacing="0" cellpadding="5" class="pastel"><tr><td>'._BANNER_AD_DOWNLOAD.'</td></tr></table>';

$base_instance->show_message($title,$all_text); exit;

}

?>