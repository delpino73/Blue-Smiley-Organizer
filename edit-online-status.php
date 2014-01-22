<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$userid=$base_instance->get_userid();

if (isset($_POST['save'])) {

	$error='';

	$online_status=(int)$_POST['online_status'];

	if (!$online_status) { $error.='<li> Online Status cannot be left blank'; }

	if (!$error) {

	$sid=(int)$_COOKIE['sid'];

	$base_instance->query("UPDATE {$base_instance->entity['SESSION']['MAIN']} SET online_status=$online_status WHERE session_id=$sid");

	if ($online_status==3) { $base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET online_status=$online_status WHERE ID=$userid"); } else { $base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET online_status=1 WHERE ID=$userid"); }

	$base_instance->show_message('Online Status changed','<a href="edit-online-status.php">[Edit Online Status]</a>');

	}

	else { $html_instance->error_message=$error; }

}


$data=$base_instance->get_data("SELECT online_status FROM {$base_instance->entity['SESSION']['MAIN']} WHERE user=$userid ORDER BY ID DESC LIMIT 1");
$online_status=$data[1]->online_status;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Change Online Status &nbsp;&nbsp; <a href="help-live-help.php">[Help]</a>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'TD_WIDTH'=>'50%',
'BUTTON_TEXT'=>'Save Status'
));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'online_status','VALUE'=>"$online_status",'OPTION'=>'online_status_array','TEXT'=>'Online Status'));

$html_instance->process();

?>