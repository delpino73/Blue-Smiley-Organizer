<?php

require 'class.base.php';
$base_instance=new base();

$token=isset($_REQUEST['token']) ? sql_safe($_REQUEST['token']) : '';
$usertoken=isset($_REQUEST['usertoken']) ? sql_safe($_REQUEST['usertoken']) : '';
$email=isset($_REQUEST['email']) ? sql_safe($_REQUEST['email']) : '';

#

$data=$base_instance->get_data("SELECT name,name2 FROM organizer_chat_request WHERE token='$token'");

if ($data) {

$name=$data[1]->name;
$name2=$data[1]->name2;

}

if (empty($name)) { $name='User'; }
if (empty($name2)) { $name2='User'; }

if (empty($_REQUEST['input_message'])) {

$timestamp=time();

$base_instance->query("INSERT INTO {$base_instance->entity['LIVE_CHAT']['USER']} (user_token,chat_token,last_active) VALUES ('$usertoken','$token','$timestamp')");

} else if ($token!='') {

$datetime=date('Y-m-d H:i:s');
$message=sql_safe($_REQUEST['input_message']);

$res=mysql_query("INSERT INTO organizer_chat (datetime,token,username,message) VALUES ('$datetime','$token','$name','$message')");

}

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

$cookie_rt=isset($_COOKIE['rt']) ? $_COOKIE['rt'] : 0;
$cookie_qt=isset($_COOKIE['qt']) ? $_COOKIE['qt'] : 0;

if ($cookie_rt==1) { echo '<script src="play2.js" language="JavaScript" type="text/javascript"></script>'; }
else if ($cookie_qt==1) { echo '<script src="play.js" language="JavaScript" type="text/javascript"></script>'; }

?>
<script language="JavaScript" type="text/javascript">

var last_id=0;
var stime;
var typing=0;
var last_state=0;

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

function update_chat(who) {

<?php if ($cookie_rt==1 or $cookie_qt==1) { echo 'if (who==1) active=1; else active=0;'; } ?>

if (http.readyState==0 || http.readyState==4) {

var param='last='+last_id+'&token=<?php echo $token;?>&usertoken=<?php echo $usertoken?>&new_messages=1&typing='+typing+'&last='+last_id;

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

document.getElementById('status_div').innerHTML='<img src="pics/ball.gif"> Connection problems, please wait ..';

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

<?php if ($cookie_rt==1 or $cookie_qt==1) { echo 'if (typing==0 && active==0) { play_sound(); }'; } ?>

}

else if (msg_id==-1) { document.getElementById('status_div').innerHTML='<img src="pics/ball.gif">  <?php echo $name2?> has left'; }

else if (msg_id==-2) { document.getElementById('status_div').innerHTML='<img src="pics/ball.gif">  <?php echo $name2?> seems to have left'; }

else if (msg_id==-3) { document.getElementById('status_div').innerHTML='<?php echo $name2?> has not entered chat room yet'; }

else if (msg_id==-4) { document.getElementById('status_div').innerHTML=''; }

else if (msg_id==-5) { document.getElementById('status_div').innerHTML='<img src="pics/typing.gif">  <?php echo $name2?> is typing'; }

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

<?php if ($cookie_rt==1 or $cookie_qt==1) { echo 'document.onmousemove=moving; function moving() { active=1; }'; } ?>

</script>
</head>

<body onload="javascript:initialise()" bgcolor="#dae4fe">

<table border="0" cellpadding="6">
<tr>
<td width="30%" bgcolor="#6495ed"><span style="font-family:verdana;color:#ffffff;font-size:medium;font-weight:bold;letter-spacing:.3em;">Live Support</span></td>
<td width="20%">&nbsp;</td>
<td width="45%"><font face="Verdana" size="1" color="#636363">Powered by <a href="http://www.bookmark-manager.com/" target="_blank"><font color="#191970">Blue Smiley Organizer</font></a></font></td>
</tr>
</table><br>

<div id="chat_div" style="height: 300px; width: 500px; overflow: auto; background-color:#ffffff; border: 1px solid #555555;"></div>

<p>
<form name="form1" onsubmit="return submit_it()">
<input type="hidden" name="token" value="<?php echo $token?>">
<input type="hidden" name="usertoken" value="<?php echo $usertoken?>">
<input type="hidden" name="name" value="<?php echo $name?>">
<input type="text" size="70" id="input_message" name="input_message" onkeydown="is_typing()">
<input type="button" name="sb" value="Send" onclick="javascript:send_message()">
</form>

<a href="javascript:initialise();" style="font-family: Verdana;text-decoration:none;color:#636363;font-size:11px;">[Refresh Window]</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="chat-end.php?token=<?php echo $token?>&email=<?php echo $email?>" style="font-family: Verdana;text-decoration:none;color:#636363;font-size:11px;" target="_top">[End Live Chat]</a> &nbsp;&nbsp;&nbsp;&nbsp; <span id="status_div" style="font-family:Verdana;text-decoration:none;color:#000066;font-size:12px;"></span>

<div id="sound_div"></div>

</body> 