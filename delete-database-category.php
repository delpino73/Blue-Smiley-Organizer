<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if (isset($_POST['delete_it'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['MAIN']} WHERE user='$userid' AND category_id='$category_id'");

	# delete checkbox records

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE user='$userid' AND category_id='$category_id'");

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$checkbox_field_id=$data[$index]->ID;

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE user='$userid' AND checkbox_field_id='$checkbox_field_id'");

	}

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	# delete select records

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE user='$userid' AND category_id='$category_id'");

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$select_field_id=$data[$index]->ID;

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE user='$userid' AND select_field_id='$select_field_id'");

	}

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	# delete number records

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['NUMBER_VALUES']} WHERE user='$userid' AND category_id='$category_id'");

	header('Location: close-me.php'); exit;

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	if (!$data) { $base_instance->show_message('Database Category not found'); exit; }

	$title=$data[1]->title;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this category?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete Category'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>"$category_id"));
$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'delete_it','VALUE'=>1));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"Are you sure you want to delete the <b>'$title'</b> category AND the items of this category?"));

$html_instance->process();

?>