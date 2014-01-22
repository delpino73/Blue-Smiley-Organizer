<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'NEWS',
'MAXHITS'=>'20',
'HEADER'=>'News',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

if (!$data) { $base_instance->show_message('No news found',''); }

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$text=$data[$index]->text;
	$title=$data[$index]->title;
	$datetime=$data[$index]->datetime;

	$text=nl2br($text);

	if ($userid==_ADMIN_USERID) { $edit='<td><a href="edit-news.php?news_id='.$ID.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-news.php?news_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>'; } else { $edit=''; }

	$all_text.='<tr><td><strong>'.$datetime.' '.$title.'</strong><br>'.$text.'</font></td>'.$edit.'</tr>';

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>