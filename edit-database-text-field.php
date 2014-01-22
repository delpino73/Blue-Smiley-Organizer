<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_field_id=isset($_REQUEST['text_field_id']) ? (int)$_REQUEST['text_field_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];

	if (!$title) { $error.='<li> Category cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['DATABASE']['TEXT_FIELDS'].' SET title="'.sql_safe($title).'" WHERE user='.$userid.' AND ID='.$text_field_id);

$base_instance->show_message('Field updated','<a href="edit-database-text-field.php?text_field_id='.$text_field_id.'">[Edit Field]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-database-text-field.php?text_field_id='.$text_field_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete Field]</a>');

	}

	else { $html_instance->error_message=$error; }

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE user='$userid' AND ID=$text_field_id");

if (!$data) { $base_instance->show_message('Database field not found'); exit; }

$title=$data[1]->title;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Text Field',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update Field'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'text_field_id','VALUE'=>"$text_field_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>35,'TEXT'=>'Title'));

$html_instance->process();

?>