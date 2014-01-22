<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$reminder_id=isset($_REQUEST['reminder_id']) ? (int)$_REQUEST['reminder_id'] : exit;

if (isset($_POST['save'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['REMINDER']['HOURS']} WHERE ID='$reminder_id' AND user='$userid'");

	header('Location: close-me.php'); exit;

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['HOURS']} WHERE ID='$reminder_id' AND user='$userid'");

if (!$data) { $base_instance->show_message('Reminder not found'); exit; }

$title=$data[1]->title;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this Reminder?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete Reminder'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'reminder_id','VALUE'=>"$reminder_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"<div align=\"center\">$title</div>"));

$html_instance->process();

?>