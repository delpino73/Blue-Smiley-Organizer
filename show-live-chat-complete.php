<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_GET['token'])) { $token=sql_safe($_GET['token']); }
else if (isset($_GET['chat_id'])) {

$chat_id=(int)$_GET['chat_id'];

$data=$base_instance->get_data("SELECT token FROM {$base_instance->entity['LIVE_CHAT']['REQUEST']} WHERE ID='$chat_id'");
$token=$data[1]->token;

}

else { exit; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'LIVE_CHAT',
'MAXHITS'=>40,
'WHERE'=>"WHERE token='$token'",
'HEADER'=>'Live Chat Transcript &nbsp;&nbsp; <a href="javascript:DelLiveChat()">[Delete]</a>',
'INNER_TABLE_WIDTH'=>'80%',
'ORDER_COL'=>'ID',
'ORDER_TYPE'=>'ASC',
'URL_PARAMETER'=>"token=$token",
'HEAD'=>'<script language="JavaScript" type="text/javascript">function DelLiveChat() {if (confirm("Delete Live Chat?")) {this.location.href="delete-live-chat.php?token='.$token.'&back=1";} else {}}</script>'
));

$data=$html_instance->get_items();

if (!$data) { $all_text='Live Chat not found'; }
else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$username=$data[$index]->username;
$message=$data[$index]->message;
$datetime=$data[$index]->datetime;

$message=convert_square_bracket($message);

$all_text.='<tr><td valign="top">'.$datetime.' <b>'.$username.':</b> '.$message.'</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>