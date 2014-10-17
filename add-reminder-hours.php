<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$freq_hours=isset($_POST['freq_hours']) ? (int)$_POST['freq_hours'] : '';
$freq_mins=isset($_POST['freq_mins']) ? (int)$_POST['freq_mins'] : '';

if (isset($_POST['save'])) {

	$error='';

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

	if ($freq_hours > 0) { $freq_total+=($freq_hours*3600); }
	if ($freq_mins > 0) { $freq_total+=($freq_mins*60); }

	$now=time();

	$datetime=$_POST['datetime'];

	$html_instance->check_for_duplicates('REMINDER','HOURS',$datetime,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['REMINDER']['HOURS'].' (datetime, title, user, frequency, last_reminded, text) VALUES ("'.sql_safe($datetime).'", "'.sql_safe($title).'",'.$userid.','.$freq_total.','.$now.',"'.sql_safe($text).'")');

	$reminder_id=mysqli_insert_id($base_instance->db_link);

	$base_instance->show_message('Reminder saved','<a href="add-reminder-hours.php">[Add more]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'edit-reminder-hours.php?reminder_id='.$reminder_id.'\',\'\',\'width=600,height=300,top=100,left=100\'))">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-reminder-hours.php?reminder_id='.$reminder_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp; <a href="show-reminder-hours.php">[Show all]</a><p>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);
	$text=stripslashes($text);

	}

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Reminder (By Hours) &nbsp;&nbsp; <a href="help-reminder.php">[Help]</a>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Save Reminder'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$freq_text='Hours: <input type="text" name="freq_hours" size="2" value="'.$freq_hours.'"> &nbsp; Minutes: <input type="text" name="freq_mins" size="2" value="'.$freq_mins.'">';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Every:','TEXT2'=>"$freq_text",'SECTIONS'=>2));

$html_instance->process();

?>