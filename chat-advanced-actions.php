<?php

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml; charset=utf-8');

require 'config.php';
$conn=@mysql_connect(_DB_HOST,_DB_USER,_DB_PW);

if (!$conn) { exit; }

mysql_select_db(_DB_NAME) or die;

#

function sql_safe($value) {

	if (get_magic_quotes_gpc()) { $value=stripslashes($value); }
	if (function_exists('mysql_real_escape_string')) { $value=mysql_real_escape_string($value); }
	else { $value=addslashes($value); }

	return trim($value);

}

function get_data($statement) {

	$res=mysql_query($statement);

	if (!$res) { exit; }

	$rows=mysql_num_rows($res);

	for ($index=1; $index <= $rows; $index++) {
	$data[$index]=mysql_fetch_object($res);
	}

	mysql_free_result($res);

	if (isset($data)) { return $data; } else { return; }

}

$message=isset($_REQUEST['message']) ? sql_safe($_REQUEST['message']) : '';
$token=isset($_REQUEST['token']) ? sql_safe($_REQUEST['token']) : '';
$usertoken=isset($_REQUEST['usertoken']) ? sql_safe($_REQUEST['usertoken']) : '';
$name=isset($_REQUEST['name']) ? sql_safe($_REQUEST['name']) : '';
$typing=isset($_REQUEST['typing']) ? (int)$_REQUEST['typing'] : '';
$leave=isset($_REQUEST['leave']) ? 1 : '';
$new_messages=isset($_REQUEST['new_messages']) ? 1 : '';

$timestamp=time();

# post a new message

if ($message!='' && $name!='' && $token!='') {

$datetime=date('Y-m-d H:i:s');

$res=mysql_query("INSERT INTO organizer_chat (datetime,token,username,message) VALUES ('$datetime','$token','$name','$message')");

}

# I'm still here

if ($usertoken!='') {

$res=mysql_query("UPDATE organizer_chat_user SET last_active='$timestamp',typing='$typing' WHERE user_token='$usertoken'");

}

# get messages

$msg='<?xml version="1.0"?><messages>';

if ($new_messages==1 && $token!='') {

	if (isset($_GET['last']) && $_GET['last']!='') { $last=$_GET['last']; } else { $last=0; }

	$data=get_data("SELECT ID,message,username FROM organizer_chat WHERE token='".$token."' AND ID > ".$last);

	for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$message=$data[$index]->message;
	$username=$data[$index]->username;

	$message=str_replace('<','&lt;',$message);
	$message=str_replace('>','&gt;',$message);

	$message=str_replace(':-)','<img src="pics/smiley.gif">',$message);
	$message=str_replace(':)','<img src="pics/smiley.gif">',$message);

	$message=str_replace(';-)','<img src="pics/smiley_wink.gif">',$message);
	$message=str_replace(';)','<img src="pics/smiley_wink.gif">',$message);

	$message=str_replace(';',',',$message);

	$msg.='<message><msg_id>'.$ID.'</msg_id><username>'.htmlspecialchars($username).'</username><text>'.htmlspecialchars($message).'</text></message>';

	}

}

# check other user

if ($token!='' && $usertoken!='') {

$data=get_data("SELECT * FROM organizer_chat_user WHERE chat_token='".$token."' AND user_token!='".$usertoken."'");

if (isset($data)) {

$chat_ended=$data[1]->chat_ended;
$last_active=$data[1]->last_active;
$typing=$data[1]->typing;

$timestamp2=time()-15;

if ($chat_ended==1) {

$msg.='<message><msg_id>-1</msg_id><username></username><text></text></message>'; } # other user has left

else if ($last_active < $timestamp2) {

$msg.='<message><msg_id>-2</msg_id><username></username><text></text></message>'; } # seems to have left

else if ($typing==1) {

$msg.='<message><msg_id>-5</msg_id><username></username><text></text></message>'; } # is typing

else { $msg.='<message><msg_id>-4</msg_id><username></username><text></text></message>'; } # everything ok

} else {

$msg.='<message><msg_id>-3</msg_id><username></username><text></text></message>'; } # chat room not entered yet

}

$msg.='</messages>';

echo $msg;

?>