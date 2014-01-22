<?php

require 'class.base.php';
require 'class.user.php';
require 'class.html.php';

$base_instance=new base();
$user_instance=new user();
$html_instance=new html();

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Search Forum',
'FORM_ACTION'=>'show-forum-search-result.php',
'BODY'=>'onLoad="javascript:document.form1.text_search.focus()"',
'BUTTON_TEXT'=>'Search Forum'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'text_search','VALUE'=>'','SIZE'=>30,'TEXT'=>'Text'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'user_search','VALUE'=>'','SIZE'=>30,'TEXT'=>'User'));

$html_instance->process();

?>