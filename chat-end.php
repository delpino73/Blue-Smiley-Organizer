<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$token=isset($_REQUEST['token']) ? sql_safe($_REQUEST['token']) : '';

if ($token) { $base_instance->query("UPDATE organizer_chat_user SET chat_ended=1 WHERE chat_token='$token'"); }

if (isset($_POST['save'])) {

	$error='';

	$email=trim($_POST['email']);

	if (!$email) { $error.='<li> Email cannot be left blank'; }
	else if (substr_count($email,'@')!=1 or !stristr($email,'.') or stristr($email,' ') or stristr($email,':') or stristr($email,',')) { $error.='<li> Email Address is not valid'; }

	if (!$error) {

	$date=date('l, j. F Y');

	$msg='Chat Transcript from '.$date.'<p>';

	$data=$base_instance->get_data("SELECT * FROM organizer_chat WHERE token='$token'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$msg.=$data[$index]->username.': '.$data[$index]->message.'<br>';

	}

	$mailheaders='From: '._ADMIN_SENDER_NAME.' <'._ADMIN_EMAIL.'>'."\n";
	$mailheaders.='Reply-To: '._ADMIN_EMAIL."\n";
	$mailheaders.='Content-Type: text/html; charset=utf-8'."\n";

	$msg.='<p>';
	$msg.=_SEPARATOR.'<br>';
	$msg.=_EMAIL_ADVERT_TEXT.'<br>';
	$msg.=_SEPARATOR.'<br>';
	$msg.=_SLOGAN.'<br>';
	$msg.=_HOMEPAGE.'<br>';
	$msg.='Email: '._ADMIN_EMAIL.'<br>';

	mail($email,'Chat Transcript',$msg,$mailheaders);

	$base_instance->show_message('Chat Transcript sent');

	}

	else { $html_instance->error_message=$error; }

}

if (!empty($_GET['email'])) {

$text='Submit form to get chat transcript by email.';
$email=$_GET['email'];

}

else {

$text='If you would like a Chat Transcript enter your email and send.';
$email='';

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Chat ended',
'TEXT_CENTER'=>$text.'<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Send Transcript'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'token','VALUE'=>$token));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'email','VALUE'=>$email,'SIZE'=>30,'TEXT'=>'Email'));

$html_instance->process();

?>