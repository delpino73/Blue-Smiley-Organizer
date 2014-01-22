<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

$userid=isset($_REQUEST['userid']) ? (int)$_REQUEST['userid'] : exit;

if (isset($_POST['save'])) {

	if (!empty($userid)) { $user_instance->delete_user($userid); }

	$base_instance->show_message('User deleted','<a href="show-user-list.php">[Show all User]</a>'); exit;

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

	if (!$data) { $base_instance->show_message('User not found'); exit; }

	$username=$data[1]->username;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this user?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete User'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'userid','VALUE'=>"$userid"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"Are you sure you want to delete the user <b>$username</b> and all items belonging to this user?"));

$html_instance->process();

?>