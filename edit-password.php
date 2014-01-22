<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_POST['save'])) {

	$error='';

	$password_old=sha1($_POST['password_old']);
	$password=sha1($_POST['password']);
	$password2=sha1($_POST['password2']);

	$data=$base_instance->get_data("SELECT user_password,username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

	$password_old_db=$data[1]->user_password;
	$username=$data[1]->username;

	if ($password_old_db <> $password_old) { $error.='<li> Old Password is incorrect (Make sure Caps Lock is off)'; }

	if (!$password) { $error.='<li> Password cannot be left blank'; }

	if ($password<>$password2) { $error.='<li> Password (Not the same)'; $password=''; $password2=''; }

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['USER']['MAIN'].' SET user_password="'.sql_safe($password).'" WHERE ID='.$userid);

	$url=$username.'/'.$password;
	$encoded_url=base64_encode($url);

	if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/login-'.$encoded_url; }
	else { $url=_HOMEPAGE.'/autologin.php?code='.$encoded_url; }

	$msg='<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff"><tr><td>

	To autologin with one click, drag and drop the following link onto your toolbar:<p>

	<strong><a href="'.$url.'"><u>Organizer</u></a></strong><p>

	Or bookmark the following link:<p>

	<strong>'.$url.'</strong>

	</td></tr><tr><td>

	To quickly add links, drag and drop the following link onto your toolbar:<p></p>

	<strong><a href="javascript:void(window.open(\''._HOMEPAGE.'/autolink.php?code='.$encoded_url.'&title=\'+encodeURIComponent(document.title)+\'&url=\'+location.href,\'_blank\',\'width=550,height=525,status=no,resizable=yes,scrollbars=auto\'))"><u>Add Link</u></a></strong><p>

	If you want to bookmark the website you are currently on just click this link.

	</td></tr></table>';

	$base_instance->show_message('Password changed',$msg);

	}

	else { $html_instance->error_message=$error; }

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Change Password',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.password_old.focus()"',
'TD_WIDTH'=>'40%',
'BUTTON_TEXT'=>'Change Password'
));

$html_instance->add_form_field(array('TYPE'=>'password','NAME'=>'password_old','VALUE'=>'','SIZE'=>20,'TEXT'=>'Old Password'));

$html_instance->add_form_field(array('TYPE'=>'password','NAME'=>'password','VALUE'=>'','SIZE'=>20,'TEXT'=>'New Password'));

$html_instance->add_form_field(array('TYPE'=>'password','NAME'=>'password2','VALUE'=>'','SIZE'=>20,'TEXT'=>'Repeat New Password'));

$html_instance->process();

?>