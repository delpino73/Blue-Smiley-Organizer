<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if (isset($_POST['save'])) {

	if (!$category_id) { exit; }

	$base_instance->query("DELETE FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE user='$userid' AND category='$category_id'");

	$base_instance->query("DELETE FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	header('Location: close-me.php'); exit;

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	if (!$data) { $base_instance->show_message('To-Do Category not found'); exit; }

	$title=$data[1]->title;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this category?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>"$category_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"Are you sure you want to delete the <b>'$title'</b> category AND the items of this category?"));

$html_instance->process();

?>