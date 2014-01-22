<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$reminder_id=isset($_REQUEST['reminder_id']) ? (int)$_REQUEST['reminder_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];
	$freq_hours=(int)$_POST['freq_hours'];
	$freq_mins=(int)$_POST['freq_mins'];

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if ($text) {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if ($freq_hours < 1 && $freq_mins < 1) { $error.='<li> <strong>Hours / Mins</strong> cannot be left blank'; }

	if (!$error) {

	$freq_total=0;

	if (isset($_POST['freq_hours'])) { $freq_hours=$_POST['freq_hours']; $freq_total+=($freq_hours*3600); } else { $freq_hours=''; }
	if (isset($_POST['freq_mins'])) { $freq_mins=$_POST['freq_mins']; $freq_total+=($freq_mins*60); } else { $freq_mins=''; }

	$base_instance->query('UPDATE '.$base_instance->entity['REMINDER']['HOURS'].' SET title="'.sql_safe($title).'",text="'.sql_safe($text).'",frequency='.$freq_total.' WHERE user='.$userid.' AND ID='.$reminder_id);

	header('Location: close-me.php'); exit;

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);
	$text=stripslashes($text);

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['HOURS']} WHERE user='$userid' AND ID='$reminder_id'");

if (!$data) { $base_instance->show_message('Reminder not found'); exit; }

$title=$data[1]->title;
$frequency=$data[1]->frequency;
$text=$data[1]->text;

$freq_hours=floor($frequency / 3600);
$hours_in_second=$freq_hours * 3600;

$frequency-=$hours_in_second;

$freq_mins=floor($frequency / 60);

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Reminder',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update Reminder'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'reminder_id','VALUE'=>"$reminder_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$freq_text='Hours: <input type="text" name="freq_hours" size="2" value="'.$freq_hours.'"> &nbsp; Minutes: <input type="text" name="freq_mins" size="2" value="'.$freq_mins.'">';

 $html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Every:','TEXT2'=>"$freq_text",'SECTIONS'=>2));

$html_instance->process();

?>