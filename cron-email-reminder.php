<?php

require 'config.php';

@mysql_connect(_DB_HOST,_DB_USER,_DB_PW) or die('Could not connect to database');
mysql_select_db(_DB_NAME) or die('Could not find database');

$day=date('j',mktime(0,0,0,date('m'),date('d'),date('Y')));
$month=date('n',mktime(0,0,0,date('m'),date('d'),date('Y')));
$year=date('Y',mktime(0,0,0,date('m'),date('d'),date('Y')));

$res=mysql_query("SELECT * FROM organizer_reminder_date WHERE (day=$day OR day=0) AND (month=$month OR month=0) AND (year=$year OR year=0) AND email_alert=1");

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);

$title=$data[$index]->title;
$text=$data[$index]->text;
$user=$data[$index]->user;

send_email($title,$text,$user);

}

function send_email($title,$text,$user) {

$res=mysql_query("SELECT * FROM organizer_user WHERE ID=$user");
$data=mysql_fetch_object($res);

$username=$data->username;
$email=$data->email;

$msg='Hello '.$username.'!'.'<p>';
$msg.='You wanted to receive a reminder about the following event:'.'<p>';
$msg.=$title.'<br>';

if ($email) {

$mailheaders='From: '._ADMIN_SENDER_NAME.' <'._ADMIN_EMAIL.'>'."\n"; 
$mailheaders.='Reply-To: '._ADMIN_EMAIL."\n";
$mailheaders.='Content-Type: text/html; charset=utf-8'."\n";

$msg.=$text.'<br>';
$msg.=_SEPARATOR.'<br>';
$msg.=_EMAIL_ADVERT_TEXT.'<br>';
$msg.=_SEPARATOR.'<br>';
$msg.=_SLOGAN.'<br>';
$msg.=_HOMEPAGE.'<br>';
$msg.='Email: '._ADMIN_EMAIL;

mail($email,'Reminder Email',$msg,$mailheaders);

}

}

?>