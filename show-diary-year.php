<?php

require 'class.base.php';
require 'class.html.php';
require 'class.diary.php';

$base_instance=new base();
$html_instance=new html();
$diary_instance=new diary();

if (isset($_GET['year'])) { $year=(int)$_GET['year']; } else { exit; }

$year_overview=$diary_instance->get_year_view($year);

$html_instance->add_parameter(array('TEXT_CENTER'=>'<p>'.$year_overview));

$html_instance->process();

?>