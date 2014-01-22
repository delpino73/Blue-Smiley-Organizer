<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

$param='';

if (isset($_GET['view_mode'])) {

$view_mode=$_GET['view_mode'];
setcookie('vm_newsletter',$view_mode,time()+2592000);

} else { $view_mode=isset($_COOKIE['vm_newsletter']) ? $_COOKIE['vm_newsletter'] : ''; }

#

if ($view_mode==1) { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=0">[Complete View]</a>'; }
else { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=1">[List View]</a>'; }

#

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'NEWSLETTER',
'ORDER_COL'=>'ID',
'ORDER_TYPE'=>'DESC',
'MAXHITS'=>40,
'HEADER'=>'Newsletter Overview &nbsp;&nbsp; '.$link,
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelNewsletter(item){if(confirm("Delete Newsletter?")){http.open(\'get\',\'delete-newsletter.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No newsletter added yet','<a href="add-newsletter.php">[Add Newsletter]</a>');

}

else {

$all_text='';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->subject;
$datetime=$data[$index]->datetime;

if ($index%2==1) { $bg='#fbfbfb'; } else { $bg='#ffffff'; }

$all_text.='<div id="item'.$ID.'">

<table width="100%" cellspacing=1 cellpadding=0 bgcolor="#e9e9e9"><tr><td></td></tr></table>

<table width="100%" cellspacing=0 cellpadding=5><tr bgcolor="'.$bg.'">

<td valign="top">';

if (!empty($title)) {

$title=convert_square_bracket($title);
$all_text.='<strong>'.$title.'</strong>';

}

#

if (empty($view_mode)) {

$text=$data[$index]->text;

$text=convert_square_bracket($text);
$text=nl2br($text);

$all_text.='<p>'.$text;

}

$all_text.='</td>

<td width="25%" valign="top">

<a href="javascript:DelNewsletter(\''.$ID.'\')">[Del]</a> &nbsp; <a href="edit-newsletter.php?newsletter_id='.$ID.'">[Edit]</a><p>

<a href="send-newsletter.php?newsletter_id='.$ID.'&test=1">[Send Test]</a><p>
<a href="send-newsletter.php?newsletter_id='.$ID.'&subscribed=1">[Send to subscribed]</a><p>
<a href="send-newsletter.php?newsletter_id='.$ID.'&all=1">[Send to all]</a><p>

';

if (empty($view_mode)) {

$datetime_converted=$base_instance->convert_date($datetime.' 00:00:00');

$all_text.='<font size="1">ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'</font>';

}

$all_text.='</td></tr></table></div>';

}

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>