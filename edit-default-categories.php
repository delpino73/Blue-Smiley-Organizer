<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_POST['save'])) {

	$error='';

	if (!$error) {

	$link_category_id=isset($_POST['link_category_id']) ? (int)$_POST['link_category_id'] : 0;
	$know_category_id=isset($_POST['know_category_id']) ? (int)$_POST['know_category_id'] : 0;
	$contact_category_id=isset($_POST['contact_category_id']) ? (int)$_POST['contact_category_id'] : 0;
	$blog_category_id=isset($_POST['blog_category_id']) ? (int)$_POST['blog_category_id'] : 0;
	$note_category_id=isset($_POST['note_category_id']) ? (int)$_POST['note_category_id'] : 0;
	$file_category_id=isset($_POST['file_category_id']) ? (int)$_POST['file_category_id'] : 0;
	$todo_category_id=isset($_POST['todo_category_id']) ? (int)$_POST['todo_category_id'] : 0;
	$priority=isset($_POST['priority']) ? (int)$_POST['priority'] : 0;

	$base_instance->query("UPDATE organizer_user SET default_link_category='$link_category_id',default_file_category='$file_category_id',default_know_category='$know_category_id',default_todo_category='$todo_category_id',default_contact_category='$contact_category_id',default_blog_category='$blog_category_id',default_note_category='$note_category_id',default_todo_priority='$priority' WHERE ID=$userid");

	$base_instance->show_message('Default categories saved','<a href="edit-default-categories.php">[Edit Default Categories]</a>');

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$link_category_id=$data[1]->default_link_category;
$know_category_id=$data[1]->default_know_category;
$contact_category_id=$data[1]->default_contact_category;
$blog_category_id=$data[1]->default_blog_category;
$file_category_id=$data[1]->default_file_category;
$note_category_id=$data[1]->default_note_category;
$todo_category_id=$data[1]->default_todo_category;
$todo_priority=$data[1]->default_todo_priority;

}

# link category

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $select_box_link='<a href="add-link-category.php">[Add Link Category]</a>'; }

else {

$select_box_link='&nbsp;<select name="link_category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$link_category_id) { $select_box_link.="<option selected value=$ID>$category_title"; }
else { $select_box_link.="<option value=$ID>$category_title"; }

}

$select_box_link.='</select>';

}

# knowledge category

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $select_box_know='<a href="add-knowledge-category.php">[Add Knowledge Category]</a>'; }

else {

$select_box_know='&nbsp;<select name="know_category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$know_category_id) { $select_box_know.="<option selected value=$ID>$category_title"; }
else { $select_box_know.="<option value=$ID>$category_title"; }

}

$select_box_know.='</select>';

}

# todo category

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $select_box_todo='<a href="add-todo-category.php">[Add To-Do Category]</a>'; }

else {

$select_box_todo='&nbsp;<select name="todo_category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$todo_category_id) { $select_box_todo.="<option selected value=$ID>$category_title"; }
else { $select_box_todo.="<option value=$ID>$category_title"; }

}

$select_box_todo.='</select>';

}

# contact category

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $select_box_contact='<a href="add-contact-category.php">[Add Contact Category]</a>'; }

else {

$select_box_contact='&nbsp;<select name="contact_category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$contact_category_id) { $select_box_contact.="<option selected value=$ID>$category_title"; }
else { $select_box_contact.="<option value=$ID>$category_title"; }

}

$select_box_contact.='</select>';

}

# blog category

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $select_box_blog='<a href="add-blog-category.php">[Add Blog Category]</a>'; }

else {

$select_box_blog='&nbsp;<select name="blog_category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$blog_category_id) { $select_box_blog.="<option selected value=$ID>$category_title"; }
else { $select_box_blog.="<option value=$ID>$category_title"; }

}

$select_box_blog.='</select>';

}

# note category

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $select_box_note='<a href="add-note-category.php">[Add Note Category]</a>'; }

else {

$select_box_note='&nbsp;<select name="note_category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$note_category_id) { $select_box_note.="<option selected value=$ID>$category_title"; }
else { $select_box_note.="<option value=$ID>$category_title"; }

}

$select_box_note.='</select>';

}

# file category

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $select_box_file='<a href="add-file-category.php">[Add File Category]</a>'; }

else {

$select_box_file='&nbsp;<select name="file_category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$file_category_id) { $select_box_file.="<option selected value=$ID>$category_title"; }
else { $select_box_file.="<option value=$ID>$category_title"; }

}

$select_box_file.='</select>';

}

#

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Default Categories',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'400',
'TD_WIDTH'=>'40%',
'BUTTON_TEXT'=>'Save Categories'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Link:','TEXT2'=>"$select_box_link",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Knowledge:','TEXT2'=>"$select_box_know",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Contact:','TEXT2'=>"$select_box_contact",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Blog:','TEXT2'=>"$select_box_blog",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Notes:','TEXT2'=>"$select_box_note",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'To-Do:','TEXT2'=>"$select_box_todo",'SECTIONS'=>2));

if ($userid==_ADMIN_USERID or $base_instance->allow_file_upload==1) { $html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Files:','TEXT2'=>"$select_box_file",'SECTIONS'=>2)); }

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'priority','VALUE'=>"$todo_priority",'OPTION'=>'priority_array','TEXT'=>'To-Do Priority'));

$html_instance->process();

?>