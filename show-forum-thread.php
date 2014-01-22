<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$id=isset($_GET['id']) ? (int)$_GET['id'] : exit;
$del_message=isset($_GET['del_message']) ? (int)$_GET['del_message'] : '';

if ($userid==_ADMIN_USERID && $del_message) {

$base_instance->query("DELETE FROM {$base_instance->entity['FORUM']['MAIN']} WHERE ID=$del_message");

}

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'FORUM',
'WHERE'=>"WHERE ID=$id OR followup=$id",
'ORDER_COL'=>'followup,datetime',
'ORDER_TYPE'=>'DESC',
'MAXHITS'=>40,
'TEXT_CENTER'=>'<center><a href="javascript:history.go(-1)">[Go Back]</a></center>',
'INNER_TABLE_WIDTH'=>'90%',
'HEADER'=>'Thread',
'URL_PARAMETER'=>"id=$id"
));

$data=$html_instance->get_items();

$all_text='';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$text=$data[$index]->text;
	$title=$data[$index]->title;

	$text=convert_square_bracket($text);
	$title=convert_square_bracket($title);

	if ($title) { $html_instance->para['HEADER']=$title; }

	$userid_message=$data[$index]->user;
	$followup=$data[$index]->followup;
	$datetime=$data[$index]->datetime;

	$datetime_converted=$base_instance->convert_date($datetime);

	if ($followup==0) { $color='#dedfdf'; } else { $color='#f1f1f1'; }

	$username=$base_instance->get_username($userid_message);

	$text=trim($text); $text=nl2br($text);

	if ($userid==_ADMIN_USERID && $followup!=0) { $delete_link='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?del_message='.$ID.'&id='.$id.'"><font color="#ff0000">Del</font></a>'; }

	else if ($userid==_ADMIN_USERID && $followup==0) { $delete_link='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="show-forum.php?del_thread='.$ID.'"><font color="#ff0000">Del</font></a>'; } else { $delete_link=''; }

	$text=$base_instance->insert_links($text);

	$all_text.='<table bgcolor="'.$color.'" width="100%" cellspacing=0><tr><td>from <a href="show-user.php?username='.$username.'">'.$username.'</a> <font size=1>'.$datetime_converted.'</font>'.$delete_link.'<br>'.$text.'</font></td></tr><tr bgcolor="#ffffff"><td height=3></td></tr></table>';

	if ($index==1) { $all_text.=_BANNER_AD_FORUM; }

}

$datetime=date('Y-m-d H:i:s');

$all_text.='<center><a href="javascript:history.go(-1)">[Go Back]</a></center><br>

<strong>Add Message:</strong>
<form action="add-forum-message.php" method="POST">
<input type="Hidden" name="datetime" value="'.$datetime.'">
<input type="Hidden" name="followup" value="'.$id.'">
<textarea rows=3 cols=90 name="text" wrap></textarea><br>
<input type="SUBMIT" value="Submit Reply" name="save"></form>';

$content_array[1]=array('MAIN'=>"$all_text",'NO_EDIT_AND_DELETE'=>1);

$html_instance->content=$content_array;

$html_instance->process();

?>