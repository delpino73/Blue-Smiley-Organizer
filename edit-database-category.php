<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	# check text

	if ($text) {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['DATABASE']['CATEGORY'].' SET title="'.sql_safe($title).'",text="'.sql_safe($text).'" WHERE user='.$userid.' AND ID='.$category_id);

$base_instance->show_message('Database Category updated','<a href="add-database-select-field.php?category_id='.$category_id.'">[Add Select Field]</a> &nbsp;&nbsp; <a href="add-database-checkbox-field.php?category_id='.$category_id.'">[Add Checkbox Field]</a> &nbsp;&nbsp; <a href="add-database-number-field.php?category_id='.$category_id.'">[Add Number field]</a><p><a href="add-database-data.php?category_id='.$category_id.'">[Add Data]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-database-category.php?category_id='.$category_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp; <a href="edit-database-category.php?category_id='.$category_id.'">[Edit]</a>');

	}

	else { $html_instance->error_message=$error; }

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	if (!$data) { $base_instance->show_message('Database Category not found'); exit; }

	$title=$data[1]->title;
	$text=$data[1]->text;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Database Category',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title.focus()"',
'BUTTON_TEXT'=>'Update Category'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>$category_id));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>45,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>45,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->process();

?>