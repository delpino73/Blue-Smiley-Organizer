<?php

setcookie('sid','','631213200','/'); # delete cookie

date_default_timezone_set('Europe/London');

require 'class.base.php';
$base_instance=new base();

if (isset($_GET['guest'])) {

$data=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['USER']['MAIN'].' WHERE ID='._GUEST_USERID);

$username=$data[1]->username;
$pw=$data[1]->user_password;

}

else {

if (isset($_REQUEST['username'])) { $username=sql_safe($_REQUEST['username']); } else { $username=''; }
if (isset($_REQUEST['pw'])) { $pw=sql_safe($_REQUEST['pw']); } else { $pw=''; }
if (isset($_GET['secure_pw'])) { $secure_pw=sql_safe($_GET['secure_pw']); } else { $secure_pw=''; }

}

if (empty($username) && empty($pw)) { header('Location: sign-up.php'); exit; }

if ($username && ($pw or $secure_pw)) {

if (isset($_GET['guest'])) { $pw_sha1=$pw; } else {

if (isset($_GET['secure_pw'])) { $pw_sha1=$_GET['secure_pw']; }
else { $pw_sha1=sha1($pw); }

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE username='$username' AND user_password='$pw_sha1'");

if (!$data) { sleep(3); header('Location: login.php?invalid_password=1'); exit; }

$userid=$data[1]->ID;
$lastlogin=$data[1]->lastlogin;
$logins=$data[1]->logins;
$timezone=$data[1]->timezone;
$dateformat=$data[1]->dateformat;
$online_status=$data[1]->online_status;

if (isset($_GET['guest'])) {

$font_face_main=1;
$font_face_navigation=1;
$font_size=2;
$color_main=1;
$color_navigation=1;
$background=14;
$allow_file_upload=2;

}

else {

$font_face_main=$data[1]->font_face_main;
$font_face_navigation=$data[1]->font_face_navigation;
$font_size=$data[1]->font_size;
$color_main=$data[1]->color_main;
$color_navigation=$data[1]->color_navigation;
$background=$data[1]->background;
$allow_file_upload=$data[1]->allow_file_upload;

setcookie('remember_username',$username,time()+2592000);

}

$datetime=date('Y-m-d H:i:s');

# set new login number and logins

$base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET logins=logins+1,lastlogin='$datetime' WHERE ID=$userid");

$sid=mt_rand(1000000,9999999);
$timestamp=time();

# delete old session id of same user

$base_instance->query("DELETE FROM {$base_instance->entity['SESSION']['MAIN']} WHERE user=$userid");

# new session id

$base_instance->query("INSERT INTO {$base_instance->entity['SESSION']['MAIN']} (session_id, create_time, last_active, user, lastlogin, background, color_main, color_navigation, font_face_main, font_face_navigation, font_size, timezone, dateformat, online_status, allow_file_upload) VALUES ($sid, $timestamp, $timestamp, $userid, '$lastlogin', $background, $color_main, $color_navigation, $font_face_main, $font_face_navigation, $font_size, $timezone, $dateformat, $online_status, $allow_file_upload)");

if (substr($lastlogin,0,10)!=substr($datetime,0,10)) { # do this just once a day, not for every login

$base_instance->query("UPDATE {$base_instance->entity['LINK']['MAIN']} SET popularity=popularity*0.995 WHERE user='$userid'");

}

setcookie('sid',$sid);

if (($logins==10 or $logins==200 or $logins==1000 or $logins==1500 or $logins==2000) && empty($_GET['guest']) && $userid!=_ADMIN_USERID && _ASK_FEEDBACK==1) { header('Location: feedback.php'); exit; }
else { header('Location: start.php'); exit; }

}

?>