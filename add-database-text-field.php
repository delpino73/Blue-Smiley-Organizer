<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if (isset($_POST['save_it'])) {

$title_text_field=sql_safe($_POST['title_text_field']);

$base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['TEXT_FIELDS']} (user,title,category_id) VALUES ($userid,'$title_text_field',$category_id)");

$field_id=mysql_insert_id();

$base_instance->show_message('Field saved','<a href="add-database-number-field.php?category_id='.$category_id.'">[Add Number Field]</a>&nbsp;&nbsp; <a href="add-database-text-field.php?category_id='.$category_id.'">[Add Text Field]</a><p>
<a href="add-database-select-field.php?category_id='.$category_id.'">[Add Select Field]</a> &nbsp;&nbsp; <a href="add-database-checkbox-field.php?category_id='.$category_id.'">[Add Checkbox Field]</a><p><a href="add-database-data.php?category_id='.$category_id.'">[Add Data]</a> &nbsp;&nbsp; <a href="edit-database-text-field.php?text_field_id='.$field_id.'">[Edit Field]</a> &nbsp;&nbsp; <a href="show-database-data.php?category_id='.$category_id.'">[Show all Data]</a>');

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Text Field',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'400',
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Save Field'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'save_it','VALUE'=>1));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>"$category_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title_text_field','VALUE'=>'','SIZE'=>35,'TEXT'=>'Name of Field'));

$html_instance->process();

?>