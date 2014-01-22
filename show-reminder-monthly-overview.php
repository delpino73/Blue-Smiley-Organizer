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

$days=$diary_instance->get_month_view2($month,$year);

$all='<br><div align="center" class="header">Monthly Reminder Overview &nbsp;&nbsp; <a href="show-reminder-monthly-list-overview.php">[List View]</a></div><p>';

$all.=$days;

$html_instance->add_parameter(array('TEXT_CENTER'=>"$all"));

$html_instance->process();

?>