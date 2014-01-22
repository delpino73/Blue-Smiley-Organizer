<?php

require 'config.php';

$name=isset($_REQUEST['name']) ? sql_safe($_REQUEST['name']) : '';
$token=isset($_REQUEST['token']) ? sql_safe($_REQUEST['token']) : '';
$usertoken=isset($_REQUEST['usertoken']) ? $_REQUEST['usertoken'] : '';
$msg=isset($_REQUEST['msg']) ? sql_safe($_REQUEST['msg']) : '';

$conn=@mysql_connect(_DB_HOST,_DB_USER,_DB_PW);

if (!$conn) {

echo '<head><meta http-equiv="refresh" content="5; URL=chat-input.php?token=',$token,'&usertoken=',$usertoken,'&name=',$name,'&msg=',$msg,'"><link rel="stylesheet" type="text/css" href="style.css"></head>[Connecting to database .. please wait]'; exit;

}

mysql_select_db(_DB_NAME) or die('Could not find database');

if ($msg) {

$datetime=date('Y-m-d H:i:s');

mysql_query("INSERT INTO organizer_chat (datetime,username,message,token) VALUES ('$datetime','$name','$msg','$token')");

}

echo '<head><script language="JavaScript" type="text/javascript"> function setFocus() { document.form1.msg.focus() } </script></head>

<body onLoad="setFocus()">

<table cellspacing=0 cellpadding=0 border=0 width="100%" height="100%">

<tr><td style="background-color:#919bf9;"><img height=1></td></tr>

<tr><td align=center bgcolor="#dae4fe" valign="top"><br>

<table border=0 cellpadding=0 cellspacing=0 align=center>

<tr><td>

<form action="chat-input.php" method="post" name="form1">
<input type="hidden" name="token" value="',$token,'">
<input type="hidden" name="usertoken" value="',$usertoken,'">
<input type="hidden" name="name" value="',$name,'">
<input type="text" name="msg" size="50" height="50">
<input type="submit" value="Send Message">
</form>

</td></tr>

<tr><td align="left"> &nbsp;&nbsp;&nbsp; <font face="Verdana" size="1" color="#636363">Switch Autoscroll:</font>

<a href="chat.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=0" style="font-family: Verdana;text-decoration:none;color:#636363;font-size: 11px;" target="main">[Off]</a>

<a href="chat.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=1" style="font-family: Verdana;text-decoration:none;color:#636363;font-size: 11px;" target="main">[On]</a>

&nbsp;&nbsp;&nbsp;&nbsp;

<a href="chat-end.php?token=',$token,'" style="font-family: Verdana;text-decoration:none;color:#636363;font-size: 11px;" target="_top">[End Live Chat]</a>

</td></tr>

</table><br>

</td></tr>

</table>';

function sql_safe($value) {

	if (get_magic_quotes_gpc()) { $value=stripslashes($value); }
	if (function_exists('mysql_real_escape_string')) { $value=mysql_real_escape_string($value); }
	else { $value=addslashes($value); }

	return $value;

}

?>