<?php

require 'class.base.php';
require 'class.user.php';
require 'class.html.php';

$base_instance=new base();
$user_instance=new user();
$html_instance=new html();

$user_instance->check_for_admin();

$userid=isset($_REQUEST['userid']) ? (int)$_REQUEST['userid'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$email=$_POST['email'];
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$country=(int)$_POST['country'];
	$allow_file_upload=(int)$_POST['allow_file_upload'];

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['USER']['MAIN'].' SET email="'.sql_safe($email).'",firstname="'.sql_safe($firstname).'",lastname="'.$lastname.'",country='.$country.',allow_file_upload='.$allow_file_upload.' WHERE ID='.$userid);

	$base_instance->query('UPDATE '.$base_instance->entity['SESSION']['MAIN'].' SET allow_file_upload='.$allow_file_upload.' WHERE user='.$userid);

	$base_instance->show_message('User updated','<a href="show-user.php?userid='.$userid.'">[View User Profile]</a> &nbsp;&nbsp; <a href="edit-user.php?userid='.$userid.'">[Edit User]</a>');

	}

	else {

	$html_instance->error_message=$error;

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

if (!$data) { $base_instance->show_message('User not found','',1); }

$about_me=$data[1]->about_me;
$email=$data[1]->email;
$firstname=$data[1]->firstname;
$lastname=$data[1]->lastname;
$country=$data[1]->country;
$allow_file_upload=$data[1]->allow_file_upload;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit User',
'TEXT_CENTER'=>'',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'userid','VALUE'=>"$userid"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'email','VALUE'=>"$email",'SIZE'=>35,'TEXT'=>'Email'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'firstname','VALUE'=>"$firstname",'SIZE'=>35,'TEXT'=>'Firstname'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'lastname','VALUE'=>"$lastname",'SIZE'=>35,'TEXT'=>'Lastname'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'country','VALUE'=>"$country",'OPTION'=>'country_array','TEXT'=>'Country'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'allow_file_upload','VALUE'=>"$allow_file_upload",'OPTION'=>'allow_file_upload_array','TEXT'=>'File Upload Allowed'));

$html_instance->process();

?>