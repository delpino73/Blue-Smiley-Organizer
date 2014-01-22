<?php

require 'class.base.php';
$base_instance=new base();

$token=isset($_REQUEST['token']) ? sql_safe($_REQUEST['token']) : '';
$usertoken=isset($_REQUEST['usertoken']) ? sql_safe($_REQUEST['usertoken']) : '';

if (empty($_REQUEST['input_message'])) {

$base_instance->query("UPDATE organizer_chat_request SET accepted=1 WHERE token='$token'");

$base_instance->query("INSERT INTO {$base_instance->entity['LIVE_CHAT']['USER']} (user_token,chat_token) VALUES ('$usertoken','$token')");

$userid=$base_instance->get_userid();

$data=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$userid");
$name=$data[1]->username;

} else if ($token!='') {

$datetime=date('Y-m-d H:i:s');
$message=sql_safe($_REQUEST['input_message']);

$res=mysql_query("INSERT INTO organizer_chat (datetime, token, username, message) VALUES ('$datetime','$token','$name','$message')");

}

#

$data=$base_instance->get_data("SELECT * FROM organizer_chat_request WHERE token='$token'");

$question=$data[1]->question;
$customer_name=$data[1]->name;
$email=$data[1]->email;
$referrer=$data[1]->referrer;
$browser=$data[1]->browser;
$IP=$data[1]->IP;

$details='';

if ($customer_name) { $details.='<strong>Name:</strong> '.$customer_name.' '; }
if ($email) { $details.=' ('.$email.')<br>'; }
if ($referrer) { $details.='<strong>Referrer:</strong> '.$referrer.'<br>'; }
if ($browser) { $details.='<strong>Browser:</strong> '.$browser.'<br>'; }
if ($IP) { $details.='<strong>IP:</strong> '.$IP.'<br>'; }
if ($question) { $details.='<strong>Question:</strong> '.$question.'<br>'; }

$details.='<br>';

if (empty($customer_name)) { $customer_name='User'; }

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Chat Window - Powered by Blue Smiley Organizer</title>
<script src="play.js" language="JavaScript" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">

var last_id=0;
var stime;
var typing=0;
var last_state=0;
var was_down=0;

var http=createRequestObject();
var http2=createRequestObject();

function createRequestObject() {
if (window.XMLHttpRequest) { return new XMLHttpRequest(); }
else if (window.ActiveXObject) { return new ActiveXObject("Microsoft.XMLHTTP"); }
else { alert('Your browser does not support XMLHTTP'); }
}

function initialise() {
document.getElementById('input_message').focus();
update_chat();
}

function pausecomp(millis)
{
var date = new Date();
var curDate = null;

do { curDate = new Date(); }
while(curDate-date < millis);
} 

function update_chat(who) {

if (who==1) active=1; else active=0;

if (http.readyState==0 || http.readyState==4) {

var param='last='+last_id+'&token=<?php echo $token?>&usertoken=<?php echo $usertoken?>&new_messages=1&typing='+typing+'&last='+last_id;

http.open('POST','chat-advanced-actions.php?new_messages=1&last='+last_id,true);
http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
http.setRequestHeader('Content-length',param.length);
http.setRequestHeader('Connection','close');

http.onreadystatechange=function() {

if (http.readyState==4) {

var chat_div = document.getElementById('chat_div');
var xmldoc = http.responseXML;

if (xmldoc!=null) {

var msg_tag = xmldoc.getElementsByTagName('message');
var total = msg_tag.length

} else {

document.getElementById('status_div').innerHTML='<img src="pics/ball.gif"> Connection problems, trying to reconnect';

}

for (i = 0; i < total; i++) {

var id_tag = msg_tag[i].getElementsByTagName('msg_id');
var text_tag = msg_tag[i].getElementsByTagName('text');
var user_tag = msg_tag[i].getElementsByTagName('username');

msg_id=id_tag[0].firstChild.nodeValue;

if (msg_id > 0) {

last_id=msg_id;

document.getElementById('chat_div').innerHTML+='<b>'+user_tag[0].firstChild.nodeValue+':</b> '+text_tag[0].firstChild.nodeValue+'<br>';

chat_div.scrollTop=chat_div.scrollHeight;

document.getElementById('status_div').innerHTML='';

if (typing==0 && active==0) { play_sound(); }

}

else if (msg_id==-1) { document.getElementById('status_div').innerHTML='<img src="pics/ball.gif">  <?php echo $customer_name?> has left'; }

else if (msg_id==-2) { document.getElementById('status_div').innerHTML='<img src="pics/ball.gif">  <?php echo $customer_name?> seems to have left'; }

else if (msg_id==-3) { document.getElementById('status_div').innerHTML='<?php echo $customer_name?> has not entered chat room yet'; }

else if (msg_id==-4) { document.getElementById('status_div').innerHTML=''; }

else if (msg_id==-5) { document.getElementById('status_div').innerHTML='<img src="pics/typing.gif">  <?php echo $customer_name?> is typing'; }

}

if (last_id==-1) {}
else { stime=setInterval('update_chat()',3000); }

}
}

http.send(param);

typing=0;

}

}

function send_message() {

if (http2.readyState==0 || http2.readyState==4) {

if (document.getElementById('input_message').value=='') { return; }

document.getElementById('input_message').focus();

var msg=document.getElementById('input_message').value;

var msg=encodeURIComponent(msg);

var param='message='+msg+'&name=<?php echo $name?>&token=<?php echo $token?>&usertoken=<?php echo $usertoken?>&new_messages=1';

http2.open('POST','chat-advanced-actions.php?new_messages=1&last='+last_id,true);
http2.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
http2.setRequestHeader('Content-length',param.length);
http2.setRequestHeader('Connection','close');

http2.onreadystatechange = function() {

if (http2.readyState==4 && last_state==2) { document.getElementById('status_div').innerHTML='Connection Problems'; }
else { last_state=http2.readyState; } // for FF

if (http2.readyState==4) {

if (http2.status==200) { document.getElementById('input_message').value=''; clearInterval(stime); update_chat(1); }
else if (http2.status==12007) { document.getElementById('status_div').innerHTML='Connection Problems'; }

}

}

http2.send(param);

}

}

function submit_it() { send_message(); return false; }

function is_typing() { typing=1; }

document.onmousemove=moving;

function moving() { active=1; }
</script>
</head>
<body onload="javascript:initialise()" bgcolor="#dae4fe">
<div id="chat_div" style="height: 300px; width: 500px; overflow: auto; background-color:#ffffff; border: 1px solid #555555;"><?php echo $details?></div>

<p>
<form name="form1" onsubmit="return submit_it()">
<input type="hidden" name="token" value="<?php echo $token?>">
<input type="hidden" name="usertoken" value="<?php echo $usertoken?>">
<input type="hidden" name="name" value="<?php echo $name?>">
<input type="text" size="70" id="input_message" name="input_message" onkeydown="is_typing()">
<input type="button" name="sb" value="Send" onclick="javascript:send_message()">
</form>

<a href="javascript:initialise();" style="font-family: Verdana;text-decoration:none;color:#636363;font-size:11px;">[Refresh Window]</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="chat-end.php?token=<?php echo $token?>" style="font-family: Verdana;text-decoration:none;color:#636363;font-size: 11px;" target="_top">[End Live Chat]</a> &nbsp;&nbsp;&nbsp;&nbsp; <span id="status_div" style="font-family:Verdana;text-decoration:none;color:#000066;font-size:12px;"></span>

<div id="sound_div"></div>

</body>
