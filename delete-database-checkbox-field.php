<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$checkbox_field_id=isset($_REQUEST['checkbox_field_id']) ? (int)$_REQUEST['checkbox_field_id'] : exit;

if (isset($_POST['save'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE ID='$checkbox_field_id' AND user='$userid'");

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE checkbox_field_id='$checkbox_field_id' AND user='$userid'");

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE checkbox_field_id='$checkbox_field_id' AND user='$userid'");

	header('Location: close-me.php'); exit;

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE ID='$checkbox_field_id'");

if (!$data) { $base_instance->show_message('Database fields not found'); exit; }

$title=$data[1]->title;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this Field?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete Field'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'checkbox_field_id','VALUE'=>"$checkbox_field_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<div align="center"><b>'.$title.'</b></div>'));

$html_instance->process();

?>