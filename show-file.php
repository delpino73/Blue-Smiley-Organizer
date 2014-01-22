<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$file_id=isset($_REQUEST['file_id']) ? (int)$_REQUEST['file_id'] : exit;

if (isset($_GET['save'])) {

$data=$base_instance->get_data("SELECT filename,user FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID='$file_id' AND (user=$userid OR public=2)");

if (!$data) { echo 'File not found'; exit; }

$filename=$data[1]->filename;
$file_userid=$data[1]->user;

$url='./upload/'.$file_userid.'/'.$filename;

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

} else { $base_instance->show_message('File not found'); }

}

if ($file_id) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID='$file_id' AND (user=$userid OR public=2)");

if (!$data) { $base_instance->show_message('File not found','',1); }

$file_id=$data[1]->ID;
$filename=$data[1]->filename;
$title=$data[1]->title;
$text=$data[1]->text;
$file_userid=$data[1]->user;

$filesize=filesize('./upload/'.$file_userid.'/'.$filename);

if ($filesize > 1048576) { $filesize2=round($filesize/1048576,1).' MB'; }
else if ($filesize > 1024) { $filesize2=round($filesize/1024,1).' KB'; }
else { $filesize2=$filesize.' Bytes'; }

$path=pathinfo($filename);
if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

if ($ext=='gif' or $ext=='png' or $ext=='jpg' or $ext=='jpeg') { $show_image='&nbsp;&nbsp; <img src="get-image.php?id='.$file_id.'" border="0" alt="'.$title.'">'; } else { $show_image=''; }

if (!$title) { $title='File'; }

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
',_CSS,'
<title>',$title,'</title>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelFile(item){if(confirm("Delete File?")){http.open(\'get\',\'delete-file.php?item=\'+item); http.send(null);}}</script>

</head>

<body ',_BACKGROUND,'><br>

<div align="center">

<table width=400 cellpadding=20 cellspacing=0 class="pastel2" bgcolor="#ffffff">

<tr><td align="center">

<span class="header">'.$title.'</span><p>

'.$show_image.'<p>

<table><tr><td><strong>Filesize:</strong></td><td>'.$filesize2.'</td></tr><tr><td><strong>Filename:</strong></td><td>'.$filename.'</td></tr>';

if ($text) { echo '<tr><td valign="top"><strong>Description:</strong></td><td>'.$text.'</td></tr>'; }

echo '</table><br>

<a href="javascript:history.go(-1)">[Back]</a> &nbsp;&nbsp; <a href="show-file.php?save=1&amp;file_id='.$file_id.'">[Download]</a> &nbsp;&nbsp; ';

if ($userid==$file_userid) { echo '<a href="javascript:DelFile(\''.$file_id.'\')">[Delete File]</a>'; }

echo '</td></tr></table>

</div>';

}

?>