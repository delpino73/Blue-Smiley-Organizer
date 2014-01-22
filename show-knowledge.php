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

if ($category_id) {

$query.=" AND (category=$category_id) "; $param.='category_id='.$category_id.'&amp;';

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE ID=$category_id");
$title=$data[1]->title;

$category_name='(Category '.$title.')';

} else { $category_name=''; }

#

if (isset($_GET['order_col'])) {

$order_col=$_GET['order_col'];
setcookie('oc_know',$_GET['order_col'],time()+2592000);

} else { $order_col=isset($_COOKIE['oc_know']) ? $_COOKIE['oc_know'] : 'datetime'; }

#

if (isset($_GET['order_type'])) {

$order_type=$_GET['order_type'];
setcookie('ot_know',$_GET['order_type'],time()+2592000);

} else { $order_type=isset($_COOKIE['ot_know']) ? $_COOKIE['ot_know'] : 'DESC'; }

#

if (isset($_GET['view_mode'])) {

$view_mode=$_GET['view_mode'];
setcookie('vm_know',$view_mode,time()+2592000);

} else { $view_mode=isset($_COOKIE['vm_know']) ? $_COOKIE['vm_know'] : ''; }

#

if ($view_mode==1) { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=0">[Complete View]</a>'; }
else { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=1">[List View]</a>'; }

#

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'KNOWLEDGE',
'ORDER_COL'=>$order_col,
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'SORTBAR'=>3,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'SORTBAR_FIELD2'=>'value','SORTBAR_NAME2'=>'Value',
'SORTBAR_FIELD3'=>'shown','SORTBAR_NAME3'=>'Shown',
'HEADER'=>'Knowledge '.$category_name.' &nbsp;&nbsp; '.$link.' &nbsp;&nbsp; <a href="show-knowledge-print.php?'.$param.'" target="_blank">[Print]</a>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelKnow(item){if(confirm("Delete Knowledge?")){http.open(\'get\',\'delete-knowledge.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

if ($text_search) {

if ($text_search) { $base_instance->show_message('Search Result','Nothing found for the entered search terms.<p><a href="javascript:history.go(-1)">[Go Back]</a>'); }

}

else { $base_instance->show_message('No knowledge added yet','<a href="add-knowledge.php">[Add Knowledge]</a>'); }

}

else {

$all_text='';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$value=$data[$index]->value;
$shown=$data[$index]->shown;
$category=$data[$index]->category;
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
$text=$base_instance->insert_links($text);
$text=nl2br($text);

if ($text_search) { $text=preg_replace("/($text_search)/i","<b>\\1</b>",$text); }

$all_text.='<p>'.$text;

}

$all_text.='</td>

<td width="25%" valign="top">

<a href="javascript:DelKnow(\''.$ID.'\')">[Del]</a> &nbsp; <a href="edit-knowledge.php?knowledge_id='.$ID.'">[Edit]</a> &nbsp; <a href="send-content.php?knowledge_id='.$ID.'">[Send]</a> &nbsp; <a href="show-knowledge-print.php?knowledge_id='.$ID.'" target="_blank">[Print]</a><p>';

if (empty($view_mode)) {

$data2=$base_instance->get_data("SELECT title FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE ID=$category");

$category_text=$data2[1]->title;

$datetime_converted=$base_instance->convert_date($datetime);

$all_text.='<font size="1"><u>'.$category_text.'</u> &nbsp;&nbsp;&nbsp; Value: '.$value.' &nbsp;&nbsp;&nbsp; Shown: '.$shown.'<br>ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'</font>';

}

$all_text.='</td></tr></table></div>';

}

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>