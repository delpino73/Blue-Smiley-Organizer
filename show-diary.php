<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_search=isset($_REQUEST['text_search']) ? sql_safe($_REQUEST['text_search']) : '';
$whole_words=isset($_POST['whole_words']) ? 1 : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';

if ($text_search && $whole_words) { $query=" AND (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') "; $param='text_search='.$text_search.'&amp;'; }

else if ($text_search) { $query=" AND (text LIKE '%$text_search%' OR title LIKE '%$text_search%') "; $param='text_search='.$text_search.'&amp;'; }

else { $query=''; $param=''; }

#

if (isset($_GET['order_col'])) {

$order_col=$_GET['order_col'];
setcookie('oc_diary',$_GET['order_col'],time()+2592000);

} else { $order_col=isset($_COOKIE['oc_diary']) ? $_COOKIE['oc_diary'] : 'date'; }

#

if (isset($_GET['order_type'])) {

$order_type=$_GET['order_type'];
setcookie('ot_diary',$_GET['order_type'],time()+2592000);

} else { $order_type=isset($_COOKIE['ot_diary']) ? $_COOKIE['ot_diary'] : 'DESC'; }

#

if (isset($_GET['view_mode'])) {

$view_mode=$_GET['view_mode'];
setcookie('vm_diary',$view_mode,time()+2592000);

} else { $view_mode=isset($_COOKIE['vm_diary']) ? $_COOKIE['vm_diary'] : ''; }

#

if ($view_mode==1) { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=0">[Complete View]</a>'; }
else { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=1">[List View]</a>'; }

#

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'DIARY',
'ORDER_COL'=>$order_col,
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'SORTBAR'=>2,
'SORTBAR_FIELD1'=>'date','SORTBAR_NAME1'=>'Date added',
'SORTBAR_FIELD2'=>'shown','SORTBAR_NAME2'=>'Shown',
'HEADER'=>'Diary &nbsp;&nbsp; '.$link.' &nbsp;&nbsp; <a href="show-diary-print.php?'.$param.'" target="_blank">[Print]</a>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelDiary(item){if(confirm("Delete Diary Entry?")){http.open(\'get\',\'delete-diary.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

if ($text_search) {

$html_instance->add_parameter(
array(
'HEADER'=>'Nothing found (Diary)',
'TEXT'=>'<form action="show-diary.php" method="post"><center><table cellpadding=10 cellspacing=0 border=0 bgcolor="#ffffff" class="pastel2"><tr><td align="right"><b>Text:</b> &nbsp;<input type="text" name="text_search" size="30" value="'.$text_search.'"></td></tr><tr><td align="center"><input type="SUBMIT" value="Search Diary" name="save"></td></tr></table></center></form>'
));

$html_instance->process();

}

else { $base_instance->show_message('No diary entries added yet','<a href="add-diary.php">[Add Diary Entry]</a>'); }

}

else {

$all_text='';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$shown=$data[$index]->shown;
$date=$data[$index]->date;

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
$text=$base_instance->insert_links($text);
$text=nl2br($text);

if ($text_search) { $text=preg_replace("/($text_search)/i","<b>\\1</b>",$text); }

$all_text.='<p>'.$text;

}

$all_text.='</td>

<td width="25%" valign="top">

<a href="javascript:DelDiary(\''.$ID.'\')">[Del]</a> &nbsp; <a href="add-diary.php?diary_id='.$ID.'">[Edit]</a> &nbsp; <a href="send-content.php?diary_id='.$ID.'">[Send]</a> &nbsp; <a href="show-diary-print.php?diary_id='.$ID.'" target="_blank">[Print]</a><p>';

if (empty($view_mode)) {

$date_converted=$base_instance->convert_date($date.' 00:00:00');

$all_text.='<font size="1">Shown: '.$shown.'<br>ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$date_converted.'</font>';

}

$all_text.='</td></tr></table></div>';

}

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>