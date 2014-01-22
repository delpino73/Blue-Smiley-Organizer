<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'User Search',
'FORM_ACTION'=>'show-user.php',
'BODY'=>'onLoad="javascript:document.form1.email.focus()"',
'TD_WIDTH'=>'35%',
'BUTTON_TEXT'=>'Search User'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'email','VALUE'=>'','SIZE'=>30,'TEXT'=>'Email'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'username','VALUE'=>'','SIZE'=>30,'TEXT'=>'Username'));

$html_instance->process();

?>