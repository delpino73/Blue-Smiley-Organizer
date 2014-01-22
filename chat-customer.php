<?php

require 'config.php';

@putenv('TZ=Europe/London');

set_time_limit(0);

$conn=@mysql_connect(_DB_HOST,_DB_USER,_DB_PW);

$token=isset($_GET['token']) ? sql_safe($_GET['token']) : '';
$usertoken=isset($_GET['usertoken']) ? sql_safe($_GET['usertoken']) : '';
$autoscroll=isset($_GET['autoscroll']) ? sql_safe($_GET['autoscroll']) : '';

if (!$conn) {

echo '<head><meta http-equiv="refresh" content="5; URL=chat-customer.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=',$autoscroll,'"><link rel="stylesheet" type="text/css" href="style.css"></head>[Connecting to database .. please wait]'; exit;

}

mysql_select_db(_DB_NAME) or die('Could not find database');

$last_id=0;

if ($autoscroll==1) { echo '<script language="JavaScript" type="text/javascript">
function up() { scroll(1,10000000); }
var abc=window.setInterval("up();",1000);
</script>'; }

echo '<head><meta http-equiv="content-type" content="text/html;charset=utf-8"><meta http-equiv="refresh" content="0; URL=chat-customer.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=',$autoscroll,'"><link rel="stylesheet" type="text/css" href="style.css"></head>

<body style="background-image:url(pics/live-bg.jpg); background-repeat:repeat-y;background-color:#ffffff;">
<h3>Live Support</h3>';

for ($index=1; $index <= 999999; $index++) {

echo str_pad('',4096);

$timestamp=time();

$res=mysql_query("UPDATE organizer_chat_user SET last_active='$timestamp' WHERE user_token='$usertoken'");

$data2=get_data("SELECT * FROM organizer_chat WHERE ID > $last_id AND token='$token' ORDER BY datetime");

	for ($index2=1; $index2 <= sizeof($data2); $index2++) {

	$ID=$data2[$index2]->ID;
	$message=$data2[$index2]->message;
	$username=$data2[$index2]->username;

	$message=str_replace('<','&lt;',$message);
	$message=str_replace('>','&gt;',$message);

	$message=str_replace(':-)','<img src="pics/smiley.gif">',$message);
	$message=str_replace(':)','<img src="pics/smiley.gif">',$message);

	$message=str_replace(';-)','<img src="pics/smiley_wink.gif">',$message);
	$message=str_replace(';)','<img src="pics/smiley_wink.gif">',$message);

	$message=str_replace(';',',',$message);

	echo '<b>',$username,':</b> ',$message,'<br>';

	$last_id=$ID;

	}

sleep(2);

if ($index > 5) { # wait a while before checking if user active so time to enter chat

	$data3=get_data("SELECT * FROM organizer_chat_user WHERE user_token!='$usertoken' AND chat_token='$token'");

	if (!$data3) {

	if (empty($finished)) { echo '<br>[User has stopped chat request]<br>'; $finished=1; }

	}

	else {

	$timestamp2=time()-15;

	$last_active=$data3[1]->last_active;
	$chat_ended=$data3[1]->chat_ended;

	if ($chat_ended==1) {

	if (empty($finished)) { echo '<br>[User has ended live chat]<br>'; $finished=1; }

	}

	if ($last_active < $timestamp2) {

	if (empty($finished)) { echo '<br>[User has left or connection lost]<br>'; $finished=1; }

	}

	}

	}

}

#

function get_data($statement) {

$res=mysql_query($statement);

if (!$res) { echo 'Error: '.$statement; exit; }

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {
$data[$index]=mysql_fetch_object($res);
}

mysql_free_result($res);

if (isset($data)) { return $data; } else { return; }

}

function sql_safe($value) {

	if (get_magic_quotes_gpc()) { $value=stripslashes($value); }
	if (function_exists('mysql_real_escape_string')) { $value=mysql_real_escape_string($value); }
	else { $value=addslashes($value); }

	return trim($value);

}

?>