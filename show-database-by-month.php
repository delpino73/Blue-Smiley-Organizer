<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (!empty($_GET['category_id'])) { $category_id=(int)$_GET['category_id']; } else { exit; }

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");

$title=$data[1]->title;

$data=$base_instance->get_data("SELECT year, month, COUNT(*) AS cnt FROM {$base_instance->entity['DATABASE']['MAIN']} WHERE user='$userid' AND category_id='$category_id' GROUP BY year, month");

$all_text='<table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$year=$data[$index]->year;
$month=$data[$index]->month;
$cnt=$data[$index]->cnt;

if ($month < 10) { $month='0'.$month; }

$all_text.='<tr><td bgcolor="#dedede"><b>'.$year.' ('.$month.')</td><td>'.$cnt.'</td><td><a href="show-database-summary-by-month.php?year='.$year.'&month='.$month.'&category_id='.$category_id.'">Summary</a></td></tr>';

}

$all_text.='</table>';

$html_instance->add_parameter(
array(
'HEADER'=>'Data by Month ('.$title.')',
'TEXT_CENTER'=>"$all_text",
'BACK'=>1
));

$html_instance->process();

?>