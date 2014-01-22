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

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE ID=$category_id");
$title=$data[1]->title;

$category_name='(Category '.$title.')';

} else { $category_name=''; }

#

if (isset($_GET['order_col'])) {

$order_col=$_GET['order_col'];
setcookie('oc_todo',$_GET['order_col'],time()+2592000);

} else { $order_col=isset($_COOKIE['oc_todo']) ? $_COOKIE['oc_todo'] : 'datetime'; }

#

if (isset($_GET['order_type'])) {

$order_type=$_GET['order_type'];
setcookie('ot_todo',$_GET['order_type'],time()+2592000);

} else { $order_type=isset($_COOKIE['ot_todo']) ? $_COOKIE['ot_todo'] : 'DESC'; }

#

if (isset($_GET['view_mode'])) {

$view_mode=$_GET['view_mode'];
setcookie('vm_todo',$view_mode,time()+2592000);

} else { $view_mode=isset($_COOKIE['vm_todo']) ? $_COOKIE['vm_todo'] : ''; }

#

if ($view_mode==1) { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=0">[Complete View]</a>'; }
else { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=1">[List View]</a>'; }

#

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'TO_DO',
'ORDER_COL'=>$order_col,
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'SORTBAR'=>2,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'SORTBAR_FIELD2'=>'priority','SORTBAR_NAME2'=>'Priority',
'HEADER'=>'To-Do '.$category_name.' &nbsp;&nbsp; '.$link.' &nbsp;&nbsp; <a href="show-to-do-print.php?'.$param.'" target="_blank">[Print]</a>  &nbsp;&nbsp; <a href="generate-rss-feed-to-do.php">[RSS Feed]</a> ',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelToDo(item){if(confirm("Delete To-Do?")){http.open(\'get\',\'delete-to-do.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

if ($text_search) {

if ($text_search) { $base_instance->show_message('Search Result','Nothing found for the entered search terms.<p><a href="javascript:history.go(-1)">[Go Back]</a>'); }

}

else { $base_instance->show_message('No to-do added yet','<a href="add-to-do.php">[Add To-do]</a>'); }

}

else {

$all_text='';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$priority=$data[$index]->priority;
$status=$data[$index]->status;
$category_id=$data[$index]->category;
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

<a href="javascript:DelToDo(\''.$ID.'\')">[Del]</a> &nbsp; <a href="edit-to-do.php?to_do_id='.$ID.'">[Edit]</a> &nbsp; <a href="send-content.php?to_do_id='.$ID.'">[Send]</a> &nbsp; <a href="show-to-do-print.php?to_do_id='.$ID.'" target="_blank">[Print]</a><p>';

if (empty($view_mode)) {

$data2=$base_instance->get_data("SELECT title FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE ID='$category_id'");

$category_text=$data2[1]->title;

$datetime_converted=$base_instance->convert_date($datetime);

$status_text=$base_instance->todo_status_array[$status];

$all_text.='<font size="1"><u>'.$category_text.'</u> &nbsp;&nbsp;&nbsp; Priority: '.$priority.'<br>
ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'<p>
<i><font size="2">'.$status_text.'</font></i></font>';

}

$all_text.='</td></tr></table></div>';

}

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>