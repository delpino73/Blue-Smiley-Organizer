<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_GET['blog_id'])) {

$blog_id=(int)$_GET['blog_id'];
$where='blog_id='.$blog_id.' AND user='.$userid;
$param='blog_id='.$blog_id;

} else {

$where=' user='.$userid;
$param='';

}

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'BLOG',
'SUBENTITY'=>'COMMENTS',
'MAXHITS'=>40,
'WHERE'=>"WHERE $where",
'SORTBAR'=>1,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'HEADER'=>'Blog Comments',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'

<script language="JavaScript" type="text/javascript">

function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();

function DelComment(item){
if (confirm("Delete Comment?")) { http.open(\'get\',\'delete-blog-comment.php?item=\'+item);
http.onreadystatechange=handleResponse;http.send(null);
}
}

function Publish(item){
http.open(\'get\',\'publish-blog-comment.php?item=\'+item);
http.onreadystatechange=handleResponse;
http.send(null);
}

function handleResponse(){
if(http.readyState==4){var response=http.responseText;
var update=new Array();
if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');
document.getElementById(res[0]).innerHTML=res[1];}}}
</script>'

));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No comments found for this blog post'); }

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$text=$data[$index]->text;
$title=$data[$index]->title;
$name=$data[$index]->name;
$email=$data[$index]->email;
$public=$data[$index]->public;
$datetime=$data[$index]->datetime;
$blog_id=$data[$index]->blog_id;

if ($public==0) {

$public='Not public yet';
$publish='<br><a href="javascript:Publish(\''.$ID.'\')">[Publish]</a>';

} else { $public=''; $publish=''; }

$datetime_converted=$base_instance->convert_date($datetime);

$text=convert_square_bracket($text);
$title=convert_square_bracket($title);

$text=$base_instance->insert_links($text);
$text=nl2br($text);

if ($email) { $email='('.$email.')'; }

$all_text.='<tr><td valign="top"><div id="item'.$ID.'">

<font size=1>ID:'.$ID.' / Added: '.$datetime_converted.'</font><p>

<strong>'.$title.$public.'</strong><p>'.$text.'<p><font size=1 color="#696969">By '.$name.' '.$email.'</font></div>

</td><td>

<a href="javascript:DelComment(\''.$ID.'\')">[Del]</a><br>
<a href="show-blog-public-permalink.php?blog_id='.$blog_id.'" target="_blank">[View]</a><br><a href="send-content.php?blog_comment_id='.$ID.'">[Send]</a>'.$publish.'

</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>