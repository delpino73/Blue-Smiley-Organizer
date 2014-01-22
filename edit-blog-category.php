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

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>50) { $error.='<li> Title is too long (Max. 50 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['BLOG']['CATEGORY'].' SET title="'.sql_safe($title).'" WHERE user='.$userid.' AND ID='.$category_id);

	$base_instance->show_message('Blog Category updated','<a href="add-blog.php?category_id='.$category_id.'">[Add Blog Post]</a> &nbsp;&nbsp; <a href="add-blog-category.php">[Add Category]</a> &nbsp;&nbsp; <a href="edit-blog-category.php?category_id='.$category_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-blog-category.php?category_id='.$category_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a><p><a href="show-blog-categories.php">[Show Blog Categories]</a>');

	}

	else { $html_instance->error_message=$error; }

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

	if (!$data) { $base_instance->show_message('Blog Category not found'); exit; }

	$title=$data[1]->title;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Blog Category',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title.focus()"',
'INNER_TABLE_WIDTH'=>'400',
'TD_WIDTH'=>'20%',
'BUTTON_TEXT'=>'Update Category'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>$category_id));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>35,'TEXT'=>'Title'));

$html_instance->process();

?>