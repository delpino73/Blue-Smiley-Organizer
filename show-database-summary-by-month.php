<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_GET['year'])) { $year=(int)$_GET['year']; } else { $year=''; }
if (isset($_GET['month'])) { $month=(int)$_GET['month']; } else { $month=''; }
if (isset($_GET['category_id'])) { $category_id=(int)$_GET['category_id']; } else { $category_id=''; }

$period="$year-$month-__";
$text='';

# show select fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE category_id='$category_id' AND user='$userid'");

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	$data_total=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE category_id='$category_id' AND user='$userid' AND select_field_id='$ID' AND date LIKE '$period'");

	$total_number=$data_total[1]->total;

	$text.='<table border=1 cellspacing=0 cellpadding=10 bgcolor="#ffffff" class="pastel"><tr><td><table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel"><tr><td bgcolor="#dedede"><b>Field Name:</b></td><td>'.$title.'</td></tr><tr><td bgcolor="#dedede"><b>Total Number:</b></td><td>'.$total_number.'</td></tr></table>';

	$data2=$base_instance->get_data("SELECT value, count(value) as cnt FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE category_id='$category_id' AND user='$userid' AND select_field_id='$ID' AND date LIKE '$period' GROUP BY value");

	$text.='<br><table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel"><tr bgcolor="#dedede"><td><strong>Type</strong></td><td><strong>Number</strong></td><td><strong>Per Cent</strong></td></tr>';

	for ($index2=1; $index2 <= sizeof($data2); $index2++) {

	$value=$data2[$index2]->value;
	$number_items=$data2[$index2]->cnt;

	$data3=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE select_field_id=$ID AND ID=$value AND user='$userid'");
	$title=$data3[1]->title;

	$per_cent=round(($number_items/$total_number)*100);

	$text.='<tr><td>'.$title.'</td><td>'.$number_items.'</td><td>'.$per_cent.'%</td></tr>';

	}

	$text.='</table></td></tr></table><p>';

}

unset($data);

# show checkbox fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE category_id='$category_id' AND user='$userid'");

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	$data_total=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE category_id='$category_id' AND user='$userid' AND checkbox_field_id='$ID' AND date LIKE '$period'");

	$total_number=$data_total[1]->total;

	$text.='<table border=1 cellspacing=0 cellpadding=10 bgcolor="#ffffff" class="pastel"><tr><td><table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel"><tr><td bgcolor="#dedede"><b>Field Name:</b></td><td>'.$title.'</td></tr><tr><td bgcolor="#dedede"><b>Total Number:</b></td><td>'.$total_number.'</td></tr></table>';

	$data2=$base_instance->get_data("SELECT value, count(value) as cnt FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE category_id='$category_id' AND user='$userid' AND checkbox_field_id='$ID' AND date LIKE '$period' GROUP BY value");

	$text.='<br><table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel"><tr bgcolor="#dedede"><td><strong>Type</strong></td><td><strong>Number</strong></td><td><strong>Per Cent</strong></td></tr>';

	for ($index2=1; $index2 <= sizeof($data2); $index2++) {

	$value=$data2[$index2]->value;
	$number_items=$data2[$index2]->cnt;

	$data3=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE checkbox_field_id=$ID AND ID=$value AND user='$userid'");
	$title=$data3[1]->title;

	$per_cent=round(($number_items/$total_number)*100);

	$text.='<tr><td>'.$title.'</td><td>'.$number_items.'</td><td>'.$per_cent.'%</td></tr>';

	}

	$text.='</table></td></tr></table><p>';

}

unset($data);

# show number fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE category_id='$category_id' AND user='$userid'");

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	$data_total=$base_instance->get_data("SELECT COUNT(*) AS total, AVG(value) AS average, SUM(value) AS sum, MAX(value) AS max_value, MIN(value) AS min_value, STD(value) AS std FROM {$base_instance->entity['DATABASE']['NUMBER_VALUES']} WHERE category_id='$category_id' AND number_field_id='$ID' AND date LIKE '$period' AND user='$userid'");

	$total_number=$data_total[1]->total;
	$average_number=$data_total[1]->average;
	$min_value=$data_total[1]->min_value;
	$max_value=$data_total[1]->max_value;
	$sum_of_values=$data_total[1]->sum;
	$std=$data_total[1]->std;

	if (!$average_number) { $average_number='&nbsp;'; }
	if (!$min_value) { $min_value='&nbsp;'; }
	if (!$max_value) { $max_value='&nbsp;'; }
	if (!$sum_of_values) { $sum_of_values='&nbsp;'; }
	if (!$std) { $std='&nbsp;'; }

	$text.='<table border=1 cellspacing=0 cellpadding=10 bgcolor="#ffffff" class="pastel"><tr><td><table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel"><tr><td bgcolor="#dedede"><b>Field Name:</b></td><td>'.$title.'</td></tr><tr><td bgcolor="#dedede"><b>Total Number:</b></td><td>'.$total_number.'</td></tr><tr><td bgcolor="#dedede"><b>Average Number:</b></td><td>'.$average_number.'</td></tr><tr><td bgcolor="#dedede"><b>Min Value:</b></td><td>'.$min_value.'</td></tr><tr><td bgcolor="#dedede"><b>Max Value:</b></td><td>'.$max_value.'</td></tr><tr><td bgcolor="#dedede"><b>Sum:</b></td><td>'.$sum_of_values.'</td></tr><tr><td bgcolor="#dedede"><b>Standard Deviation:</b></td><td>'.$std.'</td></tr></table></table></td></tr></table><p>';

}

$html_instance->add_parameter(
array(
'HEADER'=>'Show Data (by Month)',
'TEXT_CENTER'=>"$text",
'BACK'=>1
));

$html_instance->process();

?>