<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'FILE',
'MAXHITS'=>40,
'WHERE'=>"WHERE public='2'",
'SORTBAR'=>2,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'SORTBAR_FIELD2'=>'title','SORTBAR_NAME2'=>'Title',
'HEADER'=>'Public Files',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No public files uploaded yet');

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
$user_id=$data[$index]->user;

$datetime_converted=$base_instance->convert_date($datetime);

$text=convert_square_bracket($text);
$title=convert_square_bracket($title);

$text=nl2br($text);

if ($title) { $title2="<b>$title</b><br>"; } else { unset($title2); }

$data2=$base_instance->get_data("SELECT title FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE ID=$category");

$category_text=$data2[1]->title;

#

$data3=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$user_id'");

$username=$data3[1]->username;

#

if (file_exists('./upload/'.$user_id.'/'.$filename)) {

$filesize=filesize('./upload/'.$user_id.'/'.$filename);

if ($filesize > 1048576) { $filesize2=round($filesize/1048576,1).' MB'; }
else if ($filesize > 1024) { $filesize2=round($filesize/1024,1).' KB'; }
else { $filesize2=$filesize.' Bytes'; }

$filesize2='<u>Filesize:</u> '.$filesize2;

$path=pathinfo($filename);
if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

if ($ext=='gif' or $ext=='png' or $ext=='jpg' or $ext=='jpeg') { $show_image='&nbsp;&nbsp; <a href="show-file.php?file_id='.$ID.'">[Show Image]</a>'; } else { $show_image=''; }

} else { $filesize2='<br><font color="#FF0000">File not found!</font>'; $options=''; $show_image=''; }

#

$all_text.='<tr><td valign="top"><div id="item'.$ID.'">

<strong>'.$title.'</strong><p>'.$text.'<p><u>Filename:</u> '.$filename.'<br>'.$filesize2.'<br>';

if ($token) { $all_text.='<u>Public Link:</u> <a href="'._HOMEPAGE.'/file-'.$token.'" target="_blank">'._HOMEPAGE.'/file-'.$token.'</a>'; }

$all_text.='<p><a href="download-file.php?file_id='.$ID.'">[Download]</a>';

if (!$token) { $all_text.='&nbsp;&nbsp; <a href="add-file-token.php?file_id='.$ID.'">[Make Public]</a>'; }

$all_text.=$show_image.'</div></td>

<td width="25%" valign="top" bgcolor="#fbfbfb">

<font size="1"><u>'.$category_text.'</u><br>ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'<p>

User: <a href="show-user.php?username='.$username.'"><font size="1">'.$username.'</font></a>

</font>

</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>