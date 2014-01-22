<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_GET['category_id']) ? $_GET['category_id'] : '';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Search Reminder',
'FORM_ACTION'=>'show-reminder-all.php',
'BODY'=>'onLoad="javascript:document.form1.text_search.focus()"',
'TD_WIDTH'=>'35%',
'BUTTON_TEXT'=>'Search Reminder'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'text_search','VALUE'=>'','SIZE'=>30,'TEXT'=>'Text'));

$label='<input type="CHECKBOX" name="whole_words" id="tick_whole_words"> <label for="tick_whole_words">Whole Words Only</label>';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>"",'TEXT2'=>"$label",'SECTIONS'=>2));

$html_instance->process();

?>