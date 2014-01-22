<?php

require 'class.base.php';
require 'class.html.php';
require 'class.diary.php';

$base_instance=new base();
$html_instance=new html();
$diary_instance=new diary();

$userid=$base_instance->get_userid();

if (empty($_GET['month'])) { $month=date('n'); } else { $month=(int)$_GET['month']; }
if (empty($_GET['year'])) { $year=date('Y'); } else { $year=(int)$_GET['year']; }

$days=$diary_instance->get_days_in_month($month,$year);

$all='<br><div align="center" class="header">Monthly Reminder Overview &nbsp;&nbsp; <a href="show-reminder-monthly-overview.php">[Normal View]</a></div><p>';

for ($day=1; $day <= $days; $day++) {

$timestamp=mktime(0,0,0,$month,$day,$year);

$day_of_the_week=date('w',$timestamp)+1;
$day_of_the_week_text=date('l',$timestamp);

$all.='<table width="80%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>'.$day_of_the_week_text.', '.$day.'.'.$month.'.'.$year.'</b></td></tr>';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DATE']} WHERE user=$userid AND day=$day AND (month=$month OR month=0) AND (year=$year OR year=0)");

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;

$all.='<tr bgcolor="#ffffff"><td width="40"><a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))">[Edit]</a></td><td width="50"><a href="javascript:void(window.open(\'delete-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td><td>'.$title.'</td></tr>';

}

#

$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['WEEKDAY']} WHERE day_of_the_week LIKE '%$day_of_the_week%' AND user=$userid");

$dotw_total=sizeof($data2);

for ($index=1; $index <= $dotw_total; $index++) {

$dotw_id=$data2[$index]->ID;
$dotw_title=$data2[$index]->title;
$dotw_time=$data2[$index]->what_time;

$all.='<tr bgcolor="#ffffff"><td width="40"><a href="javascript:void(window.open(\'edit-reminder-weekday.php?reminder_id='.$dotw_id.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td><td width="50"><a href="javascript:void(window.open(\'delete-reminder-weekday.php?reminder_id='.$dotw_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td><td>'.$dotw_title.' - '.$dotw_time.'</td></tr>';

}

$all.='</table><p>';

}

# next year / next month

$prev_year=$year-1;
$next_year=$year+1;
$prev_month=$month-1;

if ($prev_month==0) { $prev_month=12; $prev_year_month=$prev_year; }
else { $prev_year_month=$year; }

$next_month=$month+1;

if ($next_month==13) { $next_month=1; $next_year_month=$next_year; }
else { $next_year_month=$year; }

$all.='<table border="0"><tr><td align="center" valign="top" align="left" class="pages"><a href="'.$_SERVER['PHP_SELF'].'?month='.$prev_month.'&year='.$prev_year_month.'">&laquo; Previous</a> &nbsp;&nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?month='.$next_month.'&year='.$next_year_month.'">Next &raquo;</a></td></tr></table>';

$html_instance->add_parameter(array('TEXT_CENTER'=>"$all"));

$html_instance->process();

?>