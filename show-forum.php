<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$lastlogin=$base_instance->lastlogin;

$del_thread=isset($_GET['del_thread']) ? (int)$_GET['del_thread'] : '';
$text_search=isset($_POST['text_search']) ? $_POST['text_search'] : '';

if ($userid==_ADMIN_USERID && $del_thread) {

$base_instance->query("DELETE FROM {$base_instance->entity['FORUM']['MAIN']} WHERE ID='$del_thread'");
$base_instance->query("DELETE FROM {$base_instance->entity['FORUM']['MAIN']} WHERE followup='$del_thread'");

}

if ($text_search) { $query=' WHERE text LIKE "%'.sql_safe($text_search).'%" OR title LIKE "%'.sql_safe($text_search).'%"'; $param='text_search='.$text_search.'&amp;'; }
else { $query='WHERE followup=0'; $param=''; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'FORUM',
'MAXHITS'=>40,
'TEXT'=>'<table width="100%"><tr><td width="5%"></td><td width="30%"><a href="add-forum-message.php"><b>[Post new Message]</b></a></td><td width="45%"></td><td width="20%"><a href="search-forum.php"><strong>[Search Forum]</strong></a></td></tr></table><p>',
'INNER_TABLE_WIDTH'=>'90%',
'WHERE'=>"$query",
'ORDER_COL'=>'updated',
'ORDER_TYPE'=>'DESC',
'HEADER'=>'Forum'
));

$data=$html_instance->get_items();

$all_text='';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$text=$data[$index]->text;
$title=$data[$index]->title;
$user=$data[$index]->user;
$datetime=$data[$index]->datetime;

$text=convert_square_bracket($text);
$title=convert_square_bracket($title);

$username=$base_instance->get_username($user);

if (strlen($text)>300) { $text=substr($text,0,300); $text=$text.'...'; }

#

$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['FORUM']['MAIN']} WHERE followup='$ID'");

$followups=$data2[1]->total;

if ($followups==0) { $answer='No Answer'; }
else if ($followups==1) { $answer='1 Answer'; }
else { $answer=$followups.' Answers'; }

#

$data3=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['FORUM']['MAIN']} WHERE followup='$ID' AND datetime>'$lastlogin'");

$new_followups=$data3[1]->total;

if ($new_followups==1) { $new_answer='1 new'; }
else if ($new_followups > 1) { $new_answer=$new_followups.' new'; }
else { $new_answer=''; }

if ($userid==_ADMIN_USERID) { $delete_link='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?del_thread='.$ID.'"><font color="#ff0000">Del</font></a>'; } else { $delete_link=''; }

$datetime_converted=$base_instance->convert_date($datetime);

$all_text.='<table bgcolor="#f1f1f1" width="100%">

<tr><td>

<strong><a href="show-forum-thread.php?id='.$ID.'">'.$title.'</a></strong> from <a href="show-user.php?username='.$username.'">'.$username.'</a> <font size="1">'.$datetime_converted.'</font> '.$delete_link.' <p>'.$text.'<p>
<a href="show-forum-thread.php?id='.$ID.'">'.$answer.'</a> <i>'.$new_answer.'</i><br>

</td></tr>

</table>

<table><tr bgcolor="#ffffff"><td width=5></td></tr></table>';

}

$content_array[1]=array('MAIN'=>"$all_text",'NO_EDIT_AND_DELETE'=>1);

$html_instance->content=$content_array;

$html_instance->process();

?>