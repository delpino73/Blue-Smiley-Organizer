<?php

require 'class.base.php';
require 'class.html.php';
require 'class.misc.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$new_category=(int)$_POST['new_category'];

	if (!$new_category) { $error.='<li> Category cannot be left blank'; }

	if (empty($error)) {

	$base_instance->query("UPDATE {$base_instance->entity['LINK']['MAIN']} SET category='$new_category' WHERE user='$userid' AND category=$category_id");

	$base_instance->query("DELETE FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	$base_instance->show_message('Categories have been merged','<a href="show-link-categories.php">[Show all Categories]</a>');

	}

	else { $html_instance->error_message=$error; }

}

$category_id=$_GET['category_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

if (!$data) { $base_instance->show_message('Link Category not found'); exit; }

$title=$data[1]->title;

$select_box='&nbsp;<select name="new_category"><option selected value=0>-- Choose Category --';

$select_box.=$misc_instance->build_category_select_box(0,$userid,0,$category_id);

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