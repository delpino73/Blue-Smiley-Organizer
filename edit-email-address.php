<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$userid=$base_instance->get_userid();

$new_email=isset($_POST['new_email']) ? trim($_POST['new_email']) : '';

if (isset($_POST['save'])) {

	$error='';

	if (!$new_email) { $error.='<li> Email Address cannot be left blank'; }
	else {

	$pw=sha1($_POST['pw']);

	$data=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['USER']['MAIN'].' WHERE ID='.$userid.' AND user_password="'.sql_safe($pw).'"');

	if (!$data) { $error.='<li> Password not correct'; }

	if (!stristr($new_email,'@')) { $error.='<li> Email Address is not valid'; }

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['USER']['MAIN'].' SET email="'.sql_safe($new_email).'" WHERE ID='.$userid);

	$base_instance->show_message('Email Address updated to '.$new_email,'');

	}

	else { $html_instance->error_message=$error; }

}

$data=$user_instance->get_userinfo($userid);
$email=$data[1]->email;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Update Email Address',
'INNER_TABLE_WIDTH'=>'60%',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.new_email.focus()"',
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Update Email'
));

if (empty($error)) { $html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Current Email Address:','TEXT2'=>$email,'SECTIONS'=>2)); }

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'new_email','VALUE'=>$new_email,'SIZE'=>35,'TEXT'=>'New Email Address'));

$html_instance->add_form_field(array('TYPE'=>'password','NAME'=>'pw','VALUE'=>'','SIZE'=>20,'TEXT'=>'Password'));

$html_instance->process();

?>