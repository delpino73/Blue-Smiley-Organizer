<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if ($userid!=_ADMIN_USERID && $base_instance->allow_file_upload==2) { $base_instance->show_message(_NO_FILE_UPLOAD_MSG,''); }

$text_search=isset($_REQUEST['text_search']) ? sql_safe($_REQUEST['text_search']) : '';
$whole_words=isset($_POST['whole_words']) ? 1 : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';

if ($text_search && $whole_words) { $query=" AND (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR filename REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') "; $param='text_search='.$text_search.'&amp;'; }

else if ($text_search) { $query=" AND (text LIKE '%$text_search%' OR title LIKE '%$text_search%' OR filename LIKE '%$text_search%') "; $param='text_search='.$text_search.'&amp;'; }

else { $query=''; $param=''; }

if ($category_id) {

$query.=" AND (category=$category_id) "; $param="category_id=$category_id";

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE ID=$category_id");
$title=$data[1]->title;

$category_name='(Category '.$title.')';

} else { $category_name=''; }

#

if (!empty($_GET['public'])) {

$query.=" AND (public=2) "; $param="public=1";
$link='<a href="show-files.php">[Show all]</a>';

}

else {

$link='<a href="show-files.php?public=1">[Only Public]</a>';

}

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'FILE',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'SORTBAR'=>2,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'SORTBAR_FIELD2'=>'title','SORTBAR_NAME2'=>'Title',
'HEADER'=>'Uploaded Files '.$category_name.' &nbsp;&nbsp; '.$link,
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelFile(item){if(confirm("Delete File?")){http.open(\'get\',\'delete-file.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

if ($text_search) {

$html_instance->add_parameter(
array(
'HEADER'=>'Nothing found (Files)',
'TEXT'=>'<form action="show-files.php" method="post"><center><table cellpadding=10 cellspacing=0 border=0 bgcolor="#ffffff" class="pastel2"><tr><td align="right"><b>Text:</b> &nbsp;<input type="text" name="text_search" size="30" value="'.$text_search.'"></td></tr><tr><td align="center"><input type="SUBMIT" value="Search Files" name="save"></td></tr></table></center></form>'
));

$html_instance->process();

}

else { $base_instance->show_message('No files uploaded yet','<a href="add-file.php">[Upload File]</a>'); }

}

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$datetime=$data[$index]->datetime;
$text=$data[$index]->text;
$title=$data[$index]->title;
$category=$data[$index]->category;
$filename=$data[$index]->filename;
$token=$data[$index]->token;

$datetime_converted=$base_instance->convert_date($datetime);

$text=convert_square_bracket($text);
$title=convert_square_bracket($title);

$text=nl2br($text);

if ($text_search) { $text=preg_replace("/($text_search)/i","<b>\\1</b>",$text); }

if ($title) { $title2="<b>$title</b><br>"; }
else { unset($title2); }

$data2=$base_instance->get_data("SELECT title FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE ID=$category");

$category_text=$data2[1]->title;

if (file_exists('./upload/'.$userid.'/'.$filename)) {

$filesize=filesize('./upload/'.$userid.'/'.$filename);

if ($filesize > 1048576) { $filesize2=round($filesize/1048576,1).' MB'; }
else if ($filesize > 1024) { $filesize2=round($filesize/1024,1).' KB'; }
else { $filesize2=$filesize.' Bytes'; }

$filesize2='<u>Filesize:</u> '.$filesize2;

$path=pathinfo($filename);

if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

if ($ext=='gif' or $ext=='png' or $ext=='jpg' or $ext=='jpeg') { $is_image=1; }
else { $is_image=0; }

} else { $filesize2='<br><font color="#FF0000">File not found!</font>'; }

#

$all_text.='<tr><td valign="top"><div id="item'.$ID.'">

<strong>'.$title.'</strong><p>'.$text.'<p><u>Filename:</u> '.$filename.'<br>'.$filesize2.'<br>';

if ($is_image) {

$all_text.='<u>Link to Image:</u> [i'.$ID.']<br>
<u>Display Image:</u> [image-'.$ID.']<br>';

} else {

$all_text.='<u>Link to File:</u> [f'.$ID.']<br>';

}

if ($token) { $all_text.='
<u>Public Link:</u> <a href="'._HOMEPAGE.'/file-'.$token.'" target="_blank">'._HOMEPAGE.'/file-'.$token.'</a><p><a href="remove-file-token.php?token='.$token.'"><font color="#cc0033">[Make Private again]</font></a>'; }

$all_text.='<p><a href="download-file.php?file_id='.$ID.'">[Download]</a>';

if (!$token) { $all_text.='&nbsp;&nbsp; <a href="add-file-token.php?file_id='.$ID.'">[Make Public]</a>'; }

if ($is_image) {

$all_text.='&nbsp;&nbsp; <a href="show-file.php?file_id='.$ID.'">[Show Image]</a>';

}

$all_text.='</div></td>

<td width="25%" valign="top" bgcolor="#fbfbfb">

<a href="edit-file.php?file_id='.$ID.'">[Edit]</a> &nbsp; <a href="javascript:DelFile(\''.$ID.'\')">[Del]</a> &nbsp; <a href="send-content.php?file_id='.$ID.'">[Send]</a><p>

<font size="1"><u>'.$category_text.'</u><br>ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'</font>

</td>

</tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>