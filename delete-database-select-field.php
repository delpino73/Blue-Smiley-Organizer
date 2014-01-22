<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$select_field_id=isset($_REQUEST['select_field_id']) ? (int)$_REQUEST['select_field_id'] : exit;

if (isset($_POST['save'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE ID=$select_field_id AND user='$userid'");

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE select_field_id=$select_field_id AND user='$userid'");

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE select_field_id=$select_field_id AND user='$userid'");

	header('Location: close-me.php'); exit;

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE ID=$select_field_id");

if (!$data) { $base_instance->show_message('Database field not found'); exit; }

$title=$data[1]->title;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this field?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete Field'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'select_field_id','VALUE'=>"$select_field_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<div align="center"><b>'.$title.'</b></div>'));

$html_instance->process();

?>