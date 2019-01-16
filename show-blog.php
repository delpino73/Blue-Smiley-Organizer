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

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE ID=$category_id");
$title=$data[1]->title;

$category_name='(Category '.$title.')';

} else { $category_name=''; }

#

if (isset($_GET['order_type'])) {

$order_type=$_GET['order_type'];
setcookie('ot_blog',$_GET['order_type'],time()+2592000);

} else { $order_type=isset($_COOKIE['ot_blog']) ? $_COOKIE['ot_blog'] : 'DESC'; }

#

if (isset($_GET['view_mode'])) {

$view_mode=$_GET['view_mode'];
setcookie('vm_blog',$view_mode,time()+2592000);

} else { $view_mode=isset($_COOKIE['vm_blog']) ? $_COOKIE['vm_blog'] : ''; }

#

if ($view_mode==1) { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=0">[Complete View]</a>'; }
else { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=1">[List View]</a>'; }

#

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'BLOG',
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'SORTBAR'=>1,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'HEADER'=>'Blog '.$category_name.'&nbsp;&nbsp; '.$link.' &nbsp;&nbsp; <a href="show-blog-print.php?'.$param.'" target="_blank">[Print]</a>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelBlog(item){if(confirm("Delete Blog Post?")){http.open(\'get\',\'delete-blog.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

if ($text_search) {

$html_instance->add_parameter(
array(
'HEADER'=>'Nothing found (Blog)',
'TEXT'=>'<form action="show-blog.php" method="post"><center><table cellpadding=10 cellspacing=0 border=0 bgcolor="#ffffff" class="pastel2"><tr><td align="right"><b>Text:</b> &nbsp;<input type="text" name="text_search" size="30" value="'.$text_search.'"></td></tr><tr><td align="center"><input type="SUBMIT" value="Search Blog" name="save"></td></tr></table></center></form>'
));

$html_instance->process();

}

else { $base_instance->show_message('No blog posts added yet','<a href="add-blog.php">[Add Blog Post]</a>'); }

}

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$category_id=$data[$index]->category;
$datetime=$data[$index]->datetime;

$all_text.='<tr bgcolor="#ffffff" onMouseOver=\'this.style.background="#fbfbfb"\' onMouseOut=\'this.style.background="#ffffff"\'>

<td valign="top"><div id="item'.$ID.'">';

if (!empty($title)) {

$title=convert_square_bracket($title);
$all_text.='<strong>'.$title.'</strong>';

}

#

if (empty($view_mode)) {

$text=$data[$index]->text;

$text=convert_square_bracket($text);

$text=nl2br($text);

$text=preg_replace_callback('/\[url=http:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!,: ]+)\]([a-zA-Z0-9_\-.\/~?=#&+%!,: ]+)\[\/url\]/',function($m) { return '<a href=\"http://'.$m[1].'\" target=\"_blank\">'.$m[2].'</a>';},$text);

$text=preg_replace_callback('/\[image-([a-zA-Z0-9]+)\]/',function($m) { return '<img src="get-image.php?id='.$m[1].'" alt="">';},$text);

if ($text_search) { $text=preg_replace("/($text_search)/i","<b>\\1</b>",$text); }

$all_text.='<p>'.$text;

}

if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/permalink-'.$ID; }
else { $url=_HOMEPAGE.'/show-blog-public-permalink.php?blog_id='.$ID; }

$all_text.='</div></td>

<td width="25%" valign="top" bgcolor="#fbfbfb">

<a href="javascript:DelBlog(\''.$ID.'\')">[Del]</a> &nbsp; <a href="edit-blog.php?blog_id='.$ID.'">[Edit]</a> &nbsp; <a href="send-content.php?blog_id='.$ID.'">[Send]</a> &nbsp; <a href="show-blog-print.php?blog_id='.$ID.'" target="_blank">[Print]</a><p>';

if (empty($view_mode)) {

$data2=$base_instance->get_data("SELECT title FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE ID='$category_id'");

$category_text=$data2[1]->title;

$datetime_converted=$base_instance->convert_date($datetime);

$all_text.='<font size="1"><u>'.$category_text.'</u><br>
ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'</font><p><a href="'.$url.'" target="_blank">[View]</a> &nbsp; <a href="show-blog-comments.php?blog_id='.$ID.'">[Comments]</a>';

}

$all_text.='</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>