<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (!empty($_GET['category_id'])) { $category_id=(int)$_GET['category_id']; } else { exit; }

# get checkbox fields

$all_text='<table border=1 cellspacing=0 cellpadding=10 bgcolor="#ffffff" class="pastel"><tr><td><strong>Checkbox Fields</strong> <a href="add-database-checkbox-field.php?category_id='.$category_id.'">[Add New]</a><br><br>';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE category_id='$category_id' AND user='$userid'");

$all_text.='<table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;

$all_text.='<tr><td bgcolor="#dedede"><b>'.$title.'</b></td>
<td><a href="edit-database-checkbox-field.php?checkbox_field_id='.$ID.'">[Edit]</a></td>
<td><a href="javascript:void(window.open(\'delete-database-checkbox-field.php?checkbox_field_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete Field]</a></td><td><a href="delete-database-checkbox-field-items.php?checkbox_field_id='.$ID.'">[Delete Field Items]</a></td></tr>';

}

$all_text.='</table></td></tr></table><p>';

# get select fields

$all_text.='<table border=1 cellspacing=0 cellpadding=10 bgcolor="#ffffff" class="pastel"><tr><td>';

$all_text.='<strong>Select Fields</strong> <a href="add-database-select-field.php?category_id='.$category_id.'">[Add New]</a><br><br>';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE category_id='$category_id' AND user='$userid'");

$all_text.='<table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;

$all_text.='<tr><td bgcolor="#dedede"><b>'.$title.'</b></td>
<td><a href="edit-database-select-field.php?select_field_id='.$ID.'">[Edit]</a></td>
<td><a href="javascript:void(window.open(\'delete-database-select-field.php?select_field_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete Field]</a></td><td><a href="delete-database-select-field-items.php?select_field_id='.$ID.'">[Delete Field Items]</a></td></tr>';

}

$all_text.='</table></td></tr></table><p>';

# get number fields

$all_text.='<table border=1 cellspacing=0 cellpadding=10 bgcolor="#ffffff" class="pastel"><tr><td>';

$all_text.='<strong>Number Fields</strong> <a href="add-database-number-field.php?category_id='.$category_id.'">[Add New]</a><br><br>';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE category_id='$category_id' AND user='$userid'");

$all_text.='<table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;

$all_text.='<tr><td bgcolor="#dedede"><b>'.$title.'</b></td>
<td><a href="edit-database-number-field.php?number_field_id='.$ID.'">[Edit]</a></td>
<td><a href="javascript:void(window.open(\'delete-database-number-field.php?number_field_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table></td></tr></table><p>';

# get text fields

$all_text.='<table border=1 cellspacing=0 cellpadding=10 bgcolor="#ffffff" class="pastel"><tr><td>';

$all_text.='<strong>Text Fields</strong> <a href="add-database-text-field.php?category_id='.$category_id.'">[Add New]</a><br><br>';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE category_id='$category_id' AND user='$userid'");

$all_text.='<table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;

$all_text.='<tr><td bgcolor="#dedede"><b>'.$title.'</b></td>
<td><a href="edit-database-text-field.php?text_field_id='.$ID.'">[Edit]</a></td>
<td><a href="javascript:void(window.open(\'delete-database-text-field.php?text_field_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table></td></tr></table><p>';

$html_instance->add_parameter(
array(
'HEADER'=>'Edit Fields',
'TEXT_CENTER'=>"$all_text",
'BACK'=>1
));

$html_instance->process();

?>