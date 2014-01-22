<?php

require 'class.base.php';
require 'class.html.php';
require 'class.home.php';

$base_instance=new base();
$html_instance=new html();
$home_instance=new home();

$userid=$base_instance->get_userid();

$home_id=isset($_REQUEST['home_id']) ? (int)$_REQUEST['home_id'] : '';

$base_instance->query("SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'"); // necessary for the overflow problem, see http://dev.mysql.com/doc/refman/5.6/en/out-of-range-and-overflow.html

$all_text='<div align="center">';

if ($userid==_GUEST_USERID) { $all_text.='<h3>Demo Login, do not save any relevant data.</h3>Please read the <a href="help-intro.php"><u>help section</u></a> to get started with the Organizer'; }

else { $all_text.='<br>'; }

#

if (empty($home_id)) {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['HOME']['MAIN']} WHERE user='$userid' ORDER BY ID LIMIT 1");

} else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['HOME']['MAIN']} WHERE ID='$home_id' AND user='$userid'");

}

if (isset($data)) {

	$title=$data[1]->title;

	$element1=$data[1]->element1;
	$element2=$data[1]->element2;
	$element3=$data[1]->element3;
	$element4=$data[1]->element4;
	$element5=$data[1]->element5;
	$element6=$data[1]->element6;
	$element7=$data[1]->element7;
	$element8=$data[1]->element8;
	$element9=$data[1]->element9;
	$element10=$data[1]->element10;
	$element11=$data[1]->element11;
	$element12=$data[1]->element12;

}

if (!empty($element1)) { $textblock1=$home_instance->get_element($element1); }
if (!empty($element2)) { $textblock2=$home_instance->get_element($element2); }
if (!empty($element3)) { $textblock3=$home_instance->get_element($element3); }
if (!empty($element4)) { $textblock4=$home_instance->get_element($element4); }
if (!empty($element5)) { $textblock5=$home_instance->get_element($element5); }
if (!empty($element6)) { $textblock6=$home_instance->get_element($element6); }
if (!empty($element7)) { $textblock7=$home_instance->get_element($element7); }
if (!empty($element8)) { $textblock8=$home_instance->get_element($element8); }
if (!empty($element9)) { $textblock9=$home_instance->get_element($element9); }
if (!empty($element10)) { $textblock10=$home_instance->get_element($element10); }
if (!empty($element11)) { $textblock11=$home_instance->get_element($element11); }
if (!empty($element12)) { $textblock12=$home_instance->get_element($element12); }

$all_text.='<table cellspacing=2 width="80%" border="0"><tr><td valign="top" width="50%">';

if (isset($textblock1)) { $all_text.=$textblock1.'<br>'; }
if (isset($textblock3)) { $all_text.=$textblock3.'<br>'; }
if (isset($textblock5)) { $all_text.=$textblock5.'<br>'; }
if (isset($textblock7)) { $all_text.=$textblock7.'<br>'; }
if (isset($textblock9)) { $all_text.=$textblock9.'<br>'; }
if (isset($textblock11)) { $all_text.=$textblock11.'<br>'; }

$all_text.='</td><td width="40">&nbsp;</td><td valign="top" width=350>';

if (isset($textblock2)) { $all_text.=$textblock2.'<br>'; }
if (isset($textblock4)) { $all_text.=$textblock4.'<br>'; }
if (isset($textblock6)) { $all_text.=$textblock6.'<br>'; }
if (isset($textblock8)) { $all_text.=$textblock8.'<br>'; }
if (isset($textblock10)) { $all_text.=$textblock10.'<br>'; }
if (isset($textblock12)) { $all_text.=$textblock12.'<br>'; }

$all_text.='</td></tr></table></div>';

$html_instance->add_parameter(
	array(
		'TEXT'=>$all_text
	));

$html_instance->process();

?>