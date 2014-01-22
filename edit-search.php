<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_REQUEST['save'])) {

	if (isset($_GET['default'])) {

	$element1=1;
	$element2=7;
	$element3=13;
	$element4=20;
	$element5=23;
	$element6=59;
	$element7=22;
	$element8=0;
	$element9=0;
	$element10=0;
	$element11=0;
	$element12=0;
	$element13=0;
	$element14=0;
	$element15=0;
	$element16=0;
	$element17=0;
	$element18=0;
	$element19=0;
	$element20=0;

	} else {

	$element1=(int)$_POST['element1'];
	$element2=(int)$_POST['element2'];
	$element3=(int)$_POST['element3'];
	$element4=(int)$_POST['element4'];
	$element5=(int)$_POST['element5'];
	$element6=(int)$_POST['element6'];
	$element7=(int)$_POST['element7'];
	$element8=(int)$_POST['element8'];
	$element9=(int)$_POST['element9'];
	$element10=(int)$_POST['element10'];
	$element11=(int)$_POST['element11'];
	$element12=(int)$_POST['element12'];
	$element13=(int)$_POST['element13'];
	$element14=(int)$_POST['element14'];
	$element15=(int)$_POST['element15'];
	$element16=(int)$_POST['element16'];
	$element17=(int)$_POST['element17'];
	$element18=(int)$_POST['element18'];
	$element19=(int)$_POST['element19'];
	$element20=(int)$_POST['element20'];

	}

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['SEARCH']['MAIN']} WHERE user='$userid'");

	if (!$data) {

	$base_instance->query("INSERT INTO {$base_instance->entity['SEARCH']['MAIN']} (user,element1,element2,element3,element4,element5,element6,element7,element8,element9,element10,element11,element12,element13,element14,element15,element16,element17,element18,element19,element20) VALUES ($userid,'$element1','$element2','$element3','$element4','$element5','$element6','$element7','$element8','$element9','$element10','$element11','$element12','$element13','$element14','$element15','$element16','$element17','$element18','$element19','$element20')");

	}

	else {

	$base_instance->query("UPDATE {$base_instance->entity['SEARCH']['MAIN']} SET element1='$element1',element2='$element2',element3='$element3',element4='$element4',element5='$element5',element6='$element6',element7='$element7',element8='$element8',element9='$element9',element10='$element10',element11='$element11',element12='$element12',element13='$element13',element14='$element14',element15='$element15',element16='$element16',element17='$element17',element18='$element18',element19='$element19',element20='$element20' WHERE user='$userid'");

	}

	header('Location: start.php'); exit;

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['SEARCH']['MAIN']} WHERE user='$userid'");

if ($data) {

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
$element13=$data[1]->element13;
$element14=$data[1]->element14;
$element15=$data[1]->element15;
$element16=$data[1]->element16;
$element17=$data[1]->element17;
$element18=$data[1]->element18;
$element19=$data[1]->element19;
$element20=$data[1]->element20;

} else {

$element1='';
$element2='';
$element3='';
$element4='';
$element5='';
$element6='';
$element7='';
$element8='';
$element9='';
$element10='';
$element11='';
$element12='';
$element13='';
$element14='';
$element15='';
$element16='';
$element17='';
$element18='';
$element19='';
$element20='';

}

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Search',
'TEXT_CENTER'=>'For the standard settings click <a href="'.$_SERVER['PHP_SELF'].'?save=1&amp;default=1" target="_top">here</a>.<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'FORM_ATTRIB'=>'target="_top"',
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Save'
));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element1','VALUE'=>$element1,'OPTION'=>'search_array','TEXT'=>'1. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element2','VALUE'=>$element2,'OPTION'=>'search_array','TEXT'=>'2. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element3','VALUE'=>$element3,'OPTION'=>'search_array','TEXT'=>'3. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element4','VALUE'=>$element4,'OPTION'=>'search_array','TEXT'=>'4. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element5','VALUE'=>$element5,'OPTION'=>'search_array','TEXT'=>'5. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element6','VALUE'=>$element6,'OPTION'=>'search_array','TEXT'=>'6. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element7','VALUE'=>$element7,'OPTION'=>'search_array','TEXT'=>'7. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element8','VALUE'=>$element8,'OPTION'=>'search_array','TEXT'=>'8. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element9','VALUE'=>$element9,'OPTION'=>'search_array','TEXT'=>'9. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element10','VALUE'=>$element10,'OPTION'=>'search_array','TEXT'=>'10. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element11','VALUE'=>$element11,'OPTION'=>'search_array','TEXT'=>'11. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element12','VALUE'=>$element12,'OPTION'=>'search_array','TEXT'=>'12. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element13','VALUE'=>$element13,'OPTION'=>'search_array','TEXT'=>'13. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element14','VALUE'=>$element14,'OPTION'=>'search_array','TEXT'=>'14. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element15','VALUE'=>$element15,'OPTION'=>'search_array','TEXT'=>'15. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element16','VALUE'=>$element16,'OPTION'=>'search_array','TEXT'=>'16. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element17','VALUE'=>$element17,'OPTION'=>'search_array','TEXT'=>'17. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element18','VALUE'=>$element18,'OPTION'=>'search_array','TEXT'=>'18. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element19','VALUE'=>$element19,'OPTION'=>'search_array','TEXT'=>'19. Slot'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'element20','VALUE'=>$element20,'OPTION'=>'search_array','TEXT'=>'20. Slot'));

$html_instance->process();

?>