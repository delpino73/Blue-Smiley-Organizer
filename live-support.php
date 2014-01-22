<?php

$flush=1;

require 'class.base.php';
$base_instance=new base();

require 'plugin.js';

$title='Bookmark Manager - Live Support';

$name=isset($_POST['name']) ? $_POST['name'] : '';
$email=isset($_POST['email']) ? $_POST['email'] : '';
$question=isset($_POST['question']) ? $_POST['question'] : '';
$referrer=isset($_POST['referrer']) ? $_POST['referrer'] : '';
$browser=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$userid=isset($_REQUEST['userid']) ? (int)$_REQUEST['userid'] : _ADMIN_USERID;

$data=$base_instance->get_data("SELECT username FROM organizer_user WHERE ID='$userid'");

if ($data) { $username2=$data[1]->username; } else { exit; }

if (isset($_POST['save'])) {

$datetime=date('Y-m-d H:i:s');

$IP=$base_instance->get_ip();

$token=md5(uniqid(rand(),true));

$base_instance->query('INSERT INTO '.$base_instance->entity['LIVE_CHAT']['REQUEST'].' (datetime,name,name2,email,question,user,token,IP,referrer,browser) VALUES ("'.$datetime.'","'.sql_safe($name).'","'.sql_safe($username2).'","'.sql_safe($email).'","'.sql_safe($question).'",'.$userid.',"'.sql_safe($token).'","'.sql_safe($IP).'","'.sql_safe($referrer).'","'.sql_safe($browser).'")');

echo '<head><link rel="stylesheet" type="text/css" href="style.css"></head>
<body bgcolor="#fbfbfb"><br><br><br><div align="center"><table cellpadding="20" border=1 class="pastel" bgcolor="#ffffff"><tr><td align="center" style="font-family:verdana;color:#191970;font-size:medium;font-weight:bold;letter-spacing:.3em;">Calling Live Support, please wait<p><img src="pics/progress.gif"></td></tr><tr><td align="center"><font face="Verdana" size="1" color="#636363">Powered by <a href="http://www.bookmark-manager.com/" target="_blank"><font color="#191970">Blue Smiley Organizer</font></a></font></td></tr></table></div>';

for ($index=1; $index <= 20; $index++) {

	echo str_pad('',4096);

	$data2=$base_instance->get_data('SELECT accepted FROM '.$base_instance->entity['LIVE_CHAT']['REQUEST'].' WHERE token="'.sql_safe($token).'"');

	$accepted=$data2[1]->accepted;

	$usertoken=md5(uniqid(rand(),true));

	if ($accepted==1) { echo '<meta http-equiv="refresh" content="0; URL=chat-frame-customer.php?email=',$email,'&usertoken=',$usertoken,'&token=',$token,'">'; exit; }

	sleep(3);

}

echo '<meta http-equiv="refresh" content="0; URL=live-support-email.php?userid=',$userid,'&name=',$name,'&email=',$email,'">'; exit;

}

else {

$data=$base_instance->get_data("SELECT last_active FROM organizer_session WHERE user=$userid ORDER BY ID DESC LIMIT 1");

if ($data) {

	$last_active=$data[1]->last_active;
	$now=time();
	$diff=$now-$last_active;

} else { $diff=99; }

if ($diff > 30) { echo '<meta http-equiv="refresh" content="0; URL=live-support-email.php?userid=',$userid,'&name=',$name,'&email=',$email,'">'; exit; }

if (isset($_SERVER['HTTP_REFERER'])) { $referrer=$_SERVER['HTTP_REFERER']; } else { $referrer=''; }

$header='Live Support';

$main='<form action="live-support.php" method="post">
<input type="Hidden" name="referrer" value="'.$referrer.'">
<input type="Hidden" name="userid" value='.$userid.'>

<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff">

<tr><td align="right"><b>Your Name:</b></td><td align="left">&nbsp;<input type="text" name="name" size="35" value=""></td></tr>

<tr><td align="right"><b>Email (optional):</b></td><td align="left">&nbsp;<input type="text" name="email" size="35" value=""></td></tr>

<tr><td colspan=2 align="center"><b>Initial Question:</b><br><textarea rows=4 cols=55 name="question" wrap></textarea></td></tr>

<tr><td colspan=2 align="center"><input type="Submit" value="Start Live Support" name="save"></td></tr></form></td></tr></table>

</form>';

}

if (stristr($browser,'Mac_PowerPC')) {

setcookie('js','',mktime(12,0,0,1,1,1990),'/'); # delete cookie
$js=''; $onload='';

} # Mac IE

else if (stristr($browser,'iCab')) {

setcookie('js','',mktime(12,0,0,1,1,1990),'/'); # delete cookie
$js=''; $onload='';

} # Mac iCab

else {

$js='<script language="JavaScript" type="text/javascript">

function set_cookie() {
if (window.XMLHttpRequest) { document.cookie="js=1"; }
else if (window.ActiveXObject) { document.cookie="js=1"; }
}

</script>';

$onload='onload="javascript:set_cookie();"';

}

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
',$js,'
<title>Live Support</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body bgcolor="#fbfbfb" ',$onload,'>

<div align="center"><h1>Live Support</h1><table border=0 cellpadding="0" cellspacing="0"><tr><td>',$main,'</td></tr></table></div>';

?>