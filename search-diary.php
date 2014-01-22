<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Diary Search',
'FORM_ACTION'=>'show-diary.php',
'BODY'=>'onLoad="javascript:document.form1.text_search.focus()"',
'TD_WIDTH'=>'35%',
'BUTTON_TEXT'=>'Search Diary'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'text_search','VALUE'=>'','SIZE'=>30,'TEXT'=>'Text'));

$label='<input type="CHECKBOX" name="whole_words" id="tick_whole_words"> <label for="tick_whole_words">Whole Words Only</label>';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$label",'SECTIONS'=>2));

$html_instance->process();

?>