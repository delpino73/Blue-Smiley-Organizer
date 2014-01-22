<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_GET['unread'])) {

$query='AND popup=0';
$link='<a href="show-instant-messages.php">[Show all]</a>';

} else {

$query='';
$link='<a href="show-instant-messages.php?unread=1">[Show only Unread]</a>';

}

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'INSTANT_MESSAGE',
'ORDER_COL'=>'ID',
'MAXHITS'=>50,
'WHERE'=>"WHERE (user='$userid' OR receiver='$userid') $query",
'HEADER'=>'Instant Messages &nbsp;&nbsp; '.$link,
'INNER_TABLE_WIDTH'=>'95%'
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No Instant Messages yet',''); }

else {

$all_text='<table width="100%" cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$datetime=$data[$index]->datetime;
$text=$data[$index]->text;
$user=$data[$index]->user;
$receiver=$data[$index]->receiver;
$popup=$data[$index]->popup;

if ($popup==1) { $status='Read'; } else { $status='<b>Unread</b>'; }

$data2=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$user");
if (!empty($data2)) { $username=$data2[1]->username; } else { $username='[delete]'; }

$data3=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$receiver");
if (!empty($data3)) { $receiver=$data3[1]->username; } else { $receiver='[delete]'; }

$datetime_converted=$base_instance->convert_date($datetime);

$all_text.='<tr><td width="80" align="center">'.$datetime_converted.'</td><td>'.$status.'</td><td><a href="show-user.php?username='.$username.'">'.$username.'</a> to <a href="show-user.php?username='.$receiver.'">'.$receiver.'</a>: '.$text.'</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>