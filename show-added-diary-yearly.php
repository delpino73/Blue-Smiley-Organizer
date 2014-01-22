<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$data=$base_instance->get_data("SELECT left(date,4) as odate,COUNT(*) AS number FROM {$base_instance->entity['DIARY']['MAIN']} WHERE user='$userid' GROUP BY odate ORDER BY odate DESC");

if (!$data) { $base_instance->show_message('No diary entries added yet',''); }

$all_text='<div align="center"><table border=1 cellspacing=0 cellpadding=5 bgcolor="#ffffff" class="pastel"><tr bgcolor="#dedede"><td><b>Year</b></td><td><strong>Entries</strong></td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

$number=$data[$index]->number;
$odate=$data[$index]->odate;

$all_text.='<tr><td><b><a href="show-diary-year.php?year='.$odate.'">'.$odate.'</a></b></td><td align="center">'.$number.'</td></tr>';

}

$all_text.='</table></div>';

$html_instance->add_parameter(
array(
'HEADER'=>'Added Diary Entries by Year',
'TEXT'=>"$all_text",
'BACK'=>1
));

$html_instance->process();

?>