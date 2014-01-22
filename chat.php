<?php

require 'config.php';

$token=sql_safe($_GET['token']);
$usertoken=sql_safe($_GET['usertoken']);
$autoscroll=$_GET['autoscroll'];

@putenv('TZ=Europe/London');

set_time_limit(0);

$conn=@mysql_connect(_DB_HOST,_DB_USER,_DB_PW);

if (!$conn) {

echo '<head><meta http-equiv="refresh" content="5; URL=chat.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=',$autoscroll,'"><link rel="stylesheet" type="text/css" href="style.css"></head>[Connecting to database .. please wait]'; exit;

}

mysql_select_db(_DB_NAME) or die('Could not find database');

$res=mysql_query("UPDATE organizer_chat_request SET accepted=1 WHERE token='$token'");

#

$data=get_data("SELECT * FROM organizer_chat_request WHERE token='$token'");

$question=$data[1]->question;
$name=$data[1]->name;
$email=$data[1]->email;
$referrer=$data[1]->referrer;
$IP=$data[1]->IP;

if ($name) { echo '<strong>Name:</strong> '.$name.' '; }
if ($email) { echo ' ('.$email.')'; }
if ($referrer) { echo '<br><strong>Referrer:</strong> '.$referrer.'<br>'; }
if ($IP) { echo '<strong>IP:</strong> '.$IP.'<br>'; }
if ($question) { echo '<strong>Question:</strong> '.$question; }

echo '<p>';

#

$last_id=0;

if ($autoscroll==1) { echo '<script language="JavaScript" type="text/javascript">
function up() { scroll(1,10000000); }
var abc=window.setInterval("up();",1000);
</script>'; }

echo '<head><meta http-equiv="refresh" content="0; URL=chat.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=',$autoscroll,'"><link rel="stylesheet" type="text/css" href="style.css"></head>

<body style="background-image:url(pics/live-bg.jpg); background-repeat:repeat-y;background-color:#ffffff;">';

for ($index=1; $index <= 999999; $index++) {

echo str_pad('',4096);

$timestamp=time();

$res=mysql_query("UPDATE organizer_chat_user SET last_active='$timestamp' WHERE user_token='$usertoken'");

$data2=get_data("SELECT * FROM organizer_chat WHERE ID > $last_id AND token='$token' ORDER BY datetime");

	for ($index2=1; $index2 <= sizeof($data2); $index2++) {

	$message=$data2[$index2]->message;
	$username=$data2[$index2]->username;
	$ID=$data2[$index2]->ID;

	$message=str_replace(';',',',$message);
	$message=str_replace('<','&lt;',$message);
	$message=str_replace('>','&gt;',$message);

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

if (!$res) { echo 'error: '.$statement; exit; }

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