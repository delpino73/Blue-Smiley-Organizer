<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$frequency=isset($_POST['frequency']) ? (int)$_POST['frequency'] : '';
$homepage=isset($_POST['homepage']) ? 1 : 0;
$popup=isset($_POST['popup']) ? 1 : 0;
$hour=isset($_POST['hour']) ? (int)$_POST['hour'] : 0;
$minute=isset($_POST['minute']) ? (int)$_POST['minute'] : 0;

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

	if ($homepage==0 && $popup==0) { $error.='<li> Tick <strong>Show on Home Page</strong> and / or <strong>Show Pop-up Window</strong>'; }

	if ($frequency < 1) { $error.='<li> <strong>Every .. days</strong> cannot be left blank'; }

	if (!$error) {

	if (!$minute) { $minute='00'; }

	$time="$hour:$minute:00";

	$today=date('Y-m-d');

	$datetime=$_POST['datetime'];

	$html_instance->check_for_duplicates('REMINDER','DAYS',$datetime,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['REMINDER']['DAYS'].' (datetime, what_time, title, user, frequency, homepage, popup, last_reminded, text) VALUES ("'.sql_safe($datetime).'","'.$time.'","'.sql_safe($title).'",'.$userid.','.$frequency.','.$homepage.','.$popup.',"'.$today.'","'.sql_safe($text).'")');

	$reminder_id=mysql_insert_id();

	$base_instance->show_message('Reminder saved','<a href="add-reminder-days.php">[Add more]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'edit-reminder-days.php?reminder_id='.$reminder_id.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-reminder-days.php?reminder_id='.$reminder_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp; <a href="show-reminder-days.php">[Show all]</a><p>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);
	$text=stripslashes($text);

	}

}

if (empty($_POST['save']) && empty($_POST['homepage'])) { $homepage=1; }

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Reminder (By Days) &nbsp;&nbsp; <a href="help-reminder.php">[Help]</a>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Save Reminder',
'HEAD'=>'<script language="JavaScript" type="text/javascript">function tick_box(){document.form1.popup.checked=true;}</script>'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'frequency','VALUE'=>"$frequency",'SIZE'=>5,'TEXT'=>'Every .. days'));

if ($homepage) { $cb='<input type="Checkbox" name="homepage" value="1" checked id="tick_homepage">'; }
else { $cb='<input type="Checkbox" name="homepage" value="1" id="tick_homepage">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb <label for=\"tick_homepage\">Show on Home Page</label>",'SECTIONS'=>2));

if ($popup) { $cb2='<input type="Checkbox" name="popup" value="1" checked id="tick_popup">'; }
else { $cb2='<input type="Checkbox" name="popup" value="1" id="tick_popup">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb2 <label for=\"tick_popup\">Show Pop-up Window</label> at .. ",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'hour','VALUE'=>"$hour",'OPTION'=>'hour_array','TEXT'=>'Hour','DO_NO_SORT_ARRAY'=>1,'ATTRIB'=>'onchange="javascript:tick_box()"'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'minute','VALUE'=>"$minute",'SIZE'=>3,'TEXT'=>'Minute','ATTRIB'=>'maxlength="2" onchange="javascript:tick_box()"'));

$html_instance->process();

?>