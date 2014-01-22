<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_search=isset($_POST['text_search']) ? $_POST['text_search'] : '';
$user_search=isset($_POST['user_search']) ? $_POST['user_search'] : '';

$param=''; $all_text='';

if ($text_search) { $where=" WHERE text LIKE '%$text_search%' OR title LIKE '%$text_search'"; $param.="text_search=$text_search&"; }

else if ($user_search) {

$data=$base_instance->get_data("SELECT ID FROM {$base_instance->entity['USER']['MAIN']} WHERE username='$user_search'");
$ID=$data[1]->ID;

$where=" WHERE user='$ID'"; $param.="user_search=$user_search&";

}

else { $base_instance->show_message('Error','Enter search text',1); }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'FORUM',
'MAXHITS'=>40,
'INNER_TABLE_WIDTH'=>'90%',
'WHERE'=>"$where",
'ORDER_COL'=>'updated',
'ORDER_TYPE'=>'DESC',
'HEADER'=>'Forum',
'URL_PARAMETER'=>$param
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('Nothing found','',1); }

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$text=$data[$index]->text;
$title=$data[$index]->title;
$user=$data[$index]->user;
$datetime=$data[$index]->datetime;
$followup=$data[$index]->followup;

if ($followup==0) { $thread_id=$ID; } else { $thread_id=$followup; }

$username=$base_instance->get_username($user);

$text=trim($text);

if ($text_search) {

$text=preg_replace("/($text_search)/i", "<b>\\1</b>" , $text);
$title=preg_replace("/($text_search)/i", "<i>\\1</i>" , $title);

}

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$datetime,$dd);
$datetime_converted=("$dd[3].$dd[2].$dd[1] ($dd[4]:$dd[5])");

$all_text.='<table bgcolor="#f1f1f1" width="100%">

<tr><td>

<strong><a href="show-forum-thread.php?id='.$ID.'">'.$title.'</a></strong> from <a href="show-user.php?username='.$username.'">'.$username.'</a> <font size="1">'.$datetime_converted.'</font><p>'.$text.'<p>
<a href="show-forum-thread.php?id='.$thread_id.'">[Complete Thread]</a> <br>

</td></tr>

</table>

<table><tr bgcolor="#ffffff"><td width=5></td></tr></table>';

}

$content_array[1]=array('MAIN'=>$all_text,'NO_EDIT_AND_DELETE'=>1);

$html_instance->content=$content_array;

$html_instance->process();

?>