<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : exit;

if (isset($_POST['add_fields'])) {

	$number_of_fields=(int)$_POST['number_of_fields'];

	$number_of_fields+=3;

	for ($index=1; $index <= $number_of_fields; $index++) {

	if (isset($_POST['name_select_field_item_'.$index])) {

	$item_value[$index]=sql_safe($_POST['name_select_field_item_'.$index]);

	}

	else { $item_value[$index]=''; }

	}

}

else if (isset($_POST['save_it'])) {

	$title_select_field=sql_safe($_POST['title_select_field']);
	$number_of_fields=(int)$_POST['number_of_fields'];

	$base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['SELECT_FIELDS']} (user,title,category_id) VALUES ($userid,'$title_select_field',$category_id)");

	$select_field_id=mysqli_insert_id($base_instance->db_link);

	for ($index=1; $index <= $number_of_fields; $index++) {

		$name_item=sql_safe($_POST['name_select_field_item_'.$index]);

		if ($name_item) {

			$base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['SELECT_ITEMS']} (title,user,select_field_id) VALUES ('$name_item',$userid,$select_field_id)");

		}

	}

$base_instance->show_message('Field saved','<a href="add-database-number-field.php?category_id='.$category_id.'">[Add Number Field]</a>&nbsp;&nbsp; <a href="add-database-text-field.php?category_id='.$category_id.'">[Add Text Field]</a><p>
<a href="add-database-select-field.php?category_id='.$category_id.'">[Add Select Field]</a> &nbsp;&nbsp; <a href="add-database-checkbox-field.php?category_id='.$category_id.'">[Add Checkbox Field]</a><p><a href="add-database-data.php?category_id='.$category_id.'">[Add Data]</a> &nbsp;&nbsp; <a href="edit-database-select-field.php?select_field_id='.$select_field_id.'">[Edit Field]</a> &nbsp;&nbsp; <a href="show-database-data.php?category_id='.$category_id.'">[Show all Data]</a>');

}

if (empty($number_of_fields)) { $number_of_fields=5; }

if (isset($_POST['title_select_field'])) {

$title_select_field=sql_safe($_POST['title_select_field']);

}

else { $title_select_field=''; }

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Select Fields',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'400',
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Save Field'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'save_it','VALUE'=>1));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'number_of_fields','VALUE'=>"$number_of_fields"));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>"$category_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title_select_field','VALUE'=>"$title_select_field",'SIZE'=>35,'TEXT'=>'Name of Field'));

for ($index=1; $index <= $number_of_fields; $index++) {

$name='name_select_field_item_'.$index;

if (isset($item_value[$index])) { $value=$item_value[$index]; } else { $value=''; }

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>$name,'VALUE'=>$value,'SIZE'=>35,'TEXT'=>'Name Item '.$index));

}

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<input type="submit" name="add_fields" value="More Item Fields">'));

$html_instance->process();

?>