<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$new_category=(int)$_POST['new_category'];

	if (!$new_category) { $error.='<li> Category cannot be left blank'; }

	if (!$error) {

	$base_instance->query("UPDATE {$base_instance->entity['NOTE']['MAIN']} SET category='$new_category' WHERE user='$userid' AND category=$category_id");

	$base_instance->query("DELETE FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	$base_instance->show_message('Categories have been merged','<a href="show-note-categories.php">[Show all Categories]</a>');

	}

	else { $base_instance->error_message=$error; }

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

if (!$data) { $base_instance->show_message('Text Category not found'); exit; }

$title=$data[1]->title;

# build category select box

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

$select_box='&nbsp;<select name="new_category"><option selected value=0>-- Choose Category --';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID!=$category_id) { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select>';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Merge Category',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'400',
'TD_WIDTH'=>'40%',
'BUTTON_TEXT'=>'Merge Category'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>"$category_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"Into which category do you want to merge the '$title' category?"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>"$select_box",'SECTIONS'=>2));

$html_instance->process();

?>