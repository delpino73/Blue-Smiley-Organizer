<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

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

	$title=sql_safe($title);

	$html_instance->check_for_duplicates_by_title('CONTACT','CATEGORY',$title,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['CONTACT']['CATEGORY'].' (title,user) VALUES ("'.$title.'",'.$userid.')');

	$contact_id=mysqli_insert_id($base_instance->db_link);

	$base_instance->show_message('Contact Category saved','<a href="add-contact.php?category_id='.$contact_id.'">[Add Contact]</a> &nbsp;&nbsp; <a href="add-contact-category.php">[Add Category]</a> &nbsp;&nbsp; <a href="edit-contact-category.php?category_id='.$contact_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-contact-category.php?category_id='.$contact_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a><p><a href="show-contact-categories.php">[Show Contact Categories]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);

	}

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'New Contact Category',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title.focus()"',
'INNER_TABLE_WIDTH'=>'400',
'TD_WIDTH'=>'20%',
'BUTTON_TEXT'=>'Save Category'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>'','SIZE'=>35,'TEXT'=>'Title'));

$html_instance->process();

?>