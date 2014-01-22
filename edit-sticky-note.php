<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$note_id=isset($_REQUEST['note_id']) ? (int)$_REQUEST['note_id'] : exit;

if (isset($_POST['save'])) {

	$text=trim($_REQUEST['text']);

	if (empty($error)) {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['STICKY_NOTE']['MAIN']} WHERE user='$userid' AND note_id=$note_id");

	if (!$data) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['STICKY_NOTE']['MAIN'].' (user,note_id,text) VALUES ('.$userid.','.$note_id.',"'.sql_safe($text).'")');

	}

	else {

	$base_instance->query('UPDATE '.$base_instance->entity['STICKY_NOTE']['MAIN'].' SET text="'.sql_safe($text).'" WHERE user='.$userid.' AND note_id='.$note_id);

	}

	$base_instance->show_message('Sticky Note saved','<a href="edit-sticky-note.php?note_id='.$note_id.'">[Edit Sticky Note]</a>');

	}

	else { $html_instance->error_message=$error; }

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['STICKY_NOTE']['MAIN']} WHERE user='$userid' AND note_id='$note_id'");

if ($data) { $text=$data[1]->text; } else { $text=''; }

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Sticky Note '.$note_id,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Save Sticky Note'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'note_id','VALUE'=>$note_id));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>120,'ROWS'=>15));

$html_instance->process();

?>