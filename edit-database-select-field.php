<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$select_field_id=isset($_REQUEST['select_field_id']) ? (int)$_REQUEST['select_field_id'] : exit;

if (isset($_POST['add_fields'])) {

	$title_field=$_POST['title_field'];
	$number_of_fields=$_POST['number_of_fields'];

	$number_of_fields+=3;

	for ($index=1; $index <= $number_of_fields; $index++) {

	if (isset($_POST['name_select_field_item_'.$index])) {

	$item_name[$index]=$_POST['name_select_field_item_'.$index];

	} else { $item_name[$index]=''; }

	#

	if (isset($_POST['id_select_field_item_'.$index])) {

	$item_id[$index]=$_POST['id_select_field_item_'.$index];

	} else { $item_id[$index]=''; }

	}

}

else if (isset($_POST['save'])) {

	$error='';

	$title_field=$_POST['title_field'];
	$number_of_fields=$_POST['number_of_fields'];

	if (!$title_field) { $error.='<li> Category cannot be left blank'; }
	else {

	$title_field=trim($title_field);

	$title_field=str_replace('"','&quot;',$title_field);

	if (strlen($title_field)>100) { $error.='<li> Title too long'; }

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['DATABASE']['SELECT_FIELDS'].' SET title="'.sql_safe($title_field).'" WHERE user='.$userid.' AND ID='.$select_field_id);

	#

	for ($index=1; $index <= $number_of_fields; $index++) {

	$title_var=$_POST['name_select_field_item_'.$index];
	$id_var=(int)$_POST['id_select_field_item_'.$index];

	if ($title_var) {

		if ($id_var) {

		$base_instance->query('UPDATE '.$base_instance->entity['DATABASE']['SELECT_ITEMS'].' SET title="'.sql_safe($title_var).'" WHERE user='.$userid.' AND ID='.$id_var);

		}

		else {

		$base_instance->query('INSERT INTO '.$base_instance->entity['DATABASE']['SELECT_ITEMS'].' (title,user,select_field_id) VALUES ("'.sql_safe($title_var).'",'.$userid.','.$select_field_id.')');

		}

	}

	}

$base_instance->show_message('Field upated','<a href="edit-database-select-field.php?select_field_id='.$select_field_id.'">[Edit Field]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-database-select-field.php?select_field_id='.$select_field_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete Field]</a>');

	}

	else { $html_instance->error_message=$error; }

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE user='$userid' AND ID='$select_field_id'");

	if (!$data) { $base_instance->show_message('Database field not found'); exit; }

	$title_field=$data[1]->title;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE user='$userid' AND select_field_id=$select_field_id ORDER BY ID");

	$number_of_fields=sizeof($data)+3;

	for ($index=1; $index <= sizeof($data); $index++) {

		$item_id[$index]=$data[$index]->ID;
		$item_name[$index]=$data[$index]->title;

	}

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Select Field',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'TEXT_CENTER'=>'You can rename field names here, do not change the position of field items.<p>',
'BUTTON_TEXT'=>'Update Field'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'number_of_fields','VALUE'=>"$number_of_fields"));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'select_field_id','VALUE'=>"$select_field_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title_field','VALUE'=>"$title_field",'SIZE'=>30,'TEXT'=>'Name of Field'));

for ($index=1; $index <= $number_of_fields; $index++) {

if (empty($item_id[$index])) { $item_id[$index]=''; }
if (empty($item_name[$index])) { $item_name[$index]=''; }

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'name_select_field_item_'.$index,'VALUE'=>$item_name[$index],'SIZE'=>30,'TEXT'=>'Name Item '.$index));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'id_select_field_item_'.$index,'VALUE'=>$item_id[$index]));

}

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<input type="submit" name="add_fields" value="More Item Fields">'));

$html_instance->process();

?>