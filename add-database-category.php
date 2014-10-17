<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';

if (isset($_POST['save'])) {

	$error='';

	if (!$title) { $error.='<li> Category cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>50) { $error.='<li> Category too long (Max. 50 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	$title=sql_safe($title);

	$html_instance->check_for_duplicates_by_title('DATABASE','CATEGORY',$title,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['DATABASE']['CATEGORY'].' (title,user,text) VALUES ("'.$title.'",'.$userid.',"'.sql_safe($text).'")');

	$cat_id=mysqli_insert_id($base_instance->db_link);

	$base_instance->show_message('Database Category saved','<a href="add-database-number-field.php?category_id='.$cat_id.'">[Add Number Field]</a>&nbsp;&nbsp; <a href="add-database-text-field.php?category_id='.$cat_id.'">[Add Text Field]</a><p>
<a href="add-database-select-field.php?category_id='.$cat_id.'">[Add Select Field]</a> &nbsp;&nbsp; <a href="add-database-checkbox-field.php?category_id='.$cat_id.'">[Add Checkbox Field]</a><p><a href="add-database-data.php?category_id='.$cat_id.'">[Add Data]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-database-category.php?category_id='.$cat_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp; <a href="edit-database-category.php?category_id='.$cat_id.'">[Edit]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);

	}

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'New Database Category &nbsp;&nbsp; <a href="help-database.php">[Help]</a>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title.focus()"',
'BUTTON_TEXT'=>'Save Category'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->process();

?>