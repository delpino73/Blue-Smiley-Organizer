<?php

require 'class.base.php';
require 'class.html.php';
require 'class.misc.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$checkbox_field_id=isset($_REQUEST['checkbox_field_id']) ? (int)$_REQUEST['checkbox_field_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$field_item1=(int)$_POST['field_item1'];
	$field_item2=(int)$_POST['field_item2'];

	if ($field_item1 && $field_item2==$field_item1) { $error.='<li> Cannot choose the same field items'; }

	if (!$field_item1) { $error.='<li> Choose field item which you want to delete'; }

	if (!$field_item2) { $error.='<li> Choose field item into which you want to merge into'; }

	if (!$error) {

	$base_instance->query("UPDATE {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} SET value='$field_item2' WHERE user='$userid' AND value='$field_item1'");

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE user='$userid' AND ID='$field_item1'");

	$base_instance->show_message('Merged Field Items');

	}

	else { $html_instance->error_message=$error; }

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE user='$userid' AND checkbox_field_id='$checkbox_field_id'");

if (!$data) { $base_instance->show_message('Checkbox items not found'); exit; }

$checkbox1='&nbsp;<select name="field_item1">';

for ($index=1; $index <= sizeof($data); $index++) {

$title=$data[$index]->title;
$ID=$data[$index]->ID;

$checkbox1.="<option value=$ID>$title";

}

$checkbox1.='</select>';

#

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE user='$userid' AND checkbox_field_id='$checkbox_field_id'");

$checkbox2='&nbsp;<select name="field_item2">';

for ($index=1; $index <= sizeof($data); $index++) {

$title=$data[$index]->title;
$ID=$data[$index]->ID;

$checkbox2.="<option value=$ID>$title";

}

$checkbox2.='</select>';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Delete Field Items',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Merge Field Item'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'checkbox_field_id','VALUE'=>"$checkbox_field_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'Choose field item you want to delete:'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Field Item:','TEXT2'=>"$checkbox1",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'Into which field item do you want to merge this field?'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Field Item:','TEXT2'=>"$checkbox2",'SECTIONS'=>2));

$html_instance->process();

?>