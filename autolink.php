<?php

$code=isset($_GET['code']) ? $_GET['code'] : exit;
$url=isset($_GET['url']) ? $_GET['url'] : '';

$decoded=base64_decode($code);
list($username,$pw)=explode('/',$decoded);

if ($username && $pw) {

	require 'config.php';

	@mysql_connect(_DB_HOST,_DB_USER,_DB_PW) or die('Could not connect to database');
	mysql_select_db(_DB_NAME) or die('Could not find database');

	$username=sql_safe($username);
	$pw=sql_safe($pw);

	$res=mysql_query("SELECT * FROM organizer_user WHERE username='$username' AND user_password='$pw'");
	$data=mysql_fetch_object($res);

	if (!$data) { echo 'Wrong password, update your "Add Link" URL. Go to <strong>Misc > Help > Misc</strong> to get updated links.'; exit; }
	else {

	$userid=$data->ID;
	$lastlogin=$data->lastlogin;
	$timezone=$data->timezone;
	$font_face_main=$data->font_face_main;
	$font_face_navigation=$data->font_face_navigation;
	$font_size=$data->font_size;
	$color_main=$data->color_main;
	$color_navigation=$data->color_navigation;
	$background=$data->background;

	# check if session exists

	$res=mysql_query('SELECT session_id FROM organizer_session WHERE user='.$userid);
	$data=mysql_fetch_object($res);

	if (!empty($data)) { $sid=$data->session_id; } else { $sid=''; }

	if ($sid < 1) {

	$sid=mt_rand(1000000,9999999);
	$timestamp=time();

	$res=mysql_query("INSERT INTO organizer_session (session_id,create_time,last_active,user,lastlogin,background,color_navigation,color_main,font_face_main,font_face_navigation,font_size,timezone) VALUES ($sid,$timestamp,$timestamp,$userid,'$lastlogin',$background,$color_navigation,$color_main,$font_face_main,$font_face_navigation,$font_size,$timezone)");

	}

	if (!$res) { echo 'sql error'; exit; }

	setcookie('sid',$sid);

	preg_match("/http:\/\/([a-z0-9]+).([.a-z0-9-]+)/",$url,$dd);

	#if ($dd[1]!='www') { $title=$dd[1].'.'.$dd[2]; } else { $title=$dd[2]; }
	if (empty($dd)) { $title=''; } else if ($dd[1]!='www') { $title=$dd[1].'.'.$dd[2]; } else { $title=$dd[2]; }

	$title=ucwords($title);

	$e1=base64_encode($title);
	$e2=base64_encode($url);

	header("Location: add-link.php?e1=$e1&e2=$e2");

	}

} else { echo 'error, contact admin'; }

#

function sql_safe($value) {

	if (get_magic_quotes_gpc()) { $value=stripslashes($value); }
	if (function_exists('mysql_real_escape_string')) { $value=mysql_real_escape_string($value); }
	else { $value=addslashes($value); }

	return trim($value);

}

?>