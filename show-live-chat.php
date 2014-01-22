<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'LIVE_CHAT',
'SUBENTITY'=>'REQUEST',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid'",
'SORTBAR'=>1,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'HEADER'=>'Live Chats &nbsp;&nbsp; <a href="edit-online-status.php">[Change Status]</a>',
'INNER_TABLE_WIDTH'=>'80%',
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelLiveChat(item){if(confirm("Delete it?")){http.open(\'get\',\'delete-live-chat.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No live chats saved'); }

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$question=$data[$index]->question;
$name=$data[$index]->name;
$email=$data[$index]->email;
$referrer=$data[$index]->referrer;
$browser=$data[$index]->browser;
$IP=$data[$index]->IP;
$token=$data[$index]->token;
$datetime=$data[$index]->datetime;

if ($email) { $email_text='<u>Email:</u> <a href="mailto:'.$email.'">'.$email.'</a><br>'; } else { $email_text=''; }
if ($IP) { $IP_text='<u>IP:</u> '.$IP.'<br>'; } else { $IP_text=''; }
if ($browser) { $browser_text='<u>Browser:</u> '.$browser.'<br>'; } else { $browser_text=''; }

if ($referrer) {

$referrer=substr($referrer,0,100);
$referrer_text='<u>Referrer:</u> '.$referrer.'<br>';

} else { $referrer_text=''; }

$datetime_converted=$base_instance->convert_date($datetime);

$all_text.='<tr><td valign="top"><div id="item'.$ID.'">

<font size=1>ID:'.$ID.' / Date: '.$datetime_converted.'</font><p>

<u>Question:</u> '.$question.'<br>
<u>Name:</u> '.$name.'<br>
'.$email_text.'
'.$IP_text.'
'.$referrer_text.'
'.$browser_text.'

</div>
</td><td>

<a href="javascript:DelLiveChat(\''.$ID.'\')">[Del]</a><br><a href="show-live-chat-complete.php?token='.$token.'">[Show]</a>

</td>

</tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>