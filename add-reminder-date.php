<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$day=isset($_POST['day']) ? (int)$_POST['day'] : '';
$month=isset($_POST['month']) ? (int)$_POST['month'] : '';
$year=isset($_POST['year']) ? (int)$_POST['year'] : '';
$email_alert=isset($_POST['email_alert']) ? 1 : 0;
$homepage=isset($_POST['homepage']) ? 1 : 0;
$popup=isset($_POST['popup']) ? 1 : 0;
$hour=isset($_POST['hour']) ? (int)$_POST['hour'] : 0;
$minute=isset($_POST['minute']) ? (int)$_POST['minute'] : 0;
$warning_homepage=isset($_POST['warning_homepage']) ? (int)$_POST['warning_homepage'] : '';

if (isset($_POST['save'])) {

	$error='';

	if (!empty($day) && !empty($month) && !empty($year)) {

	if (!checkdate($month,$day,$year)) { $error.='<li> Date is invalid'; }

	} else if (!empty($day) && !empty($month)) {

	if (!checkdate($month,$day,2000)) { $error.='<li> Date is invalid'; }

	}

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

	if ($homepage==0 && $popup==0 && $email_alert==0) { $error.='<li> Tick at least one of the following <strong>Send Email Alert</strong>, <strong>Show on Homepage</strong> or <strong>Show Pop-up Window</strong>'; }

	if (!$error) {

	if (!$minute) { $minute='00'; }

	$time="$hour:$minute:00";

	$datetime=$_POST['datetime'];

	$html_instance->check_for_duplicates('REMINDER','DATE',$datetime,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['REMINDER']['DATE'].' (datetime, what_time, day, month, year, title, user, last_reminded, warning_homepage, homepage, popup, email_alert, text) VALUES ("'.sql_safe($datetime).'","'.$time.'",'.$day.','.$month.','.$year.',"'.sql_safe($title).'",'.$userid.',"1970-01-01",'.$warning_homepage.','.$homepage.','.$popup.','.$email_alert.',"'.sql_safe($text).'")');

	$reminder_id=mysql_insert_id();

	$base_instance->show_message('Reminder saved','<a href="add-reminder-date.php">[Add more]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$reminder_id.'\',\'\',\'width=600,height=560,top=100,left=100\'))">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-reminder-date.php?reminder_id='.$reminder_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp; <a href="show-reminder-date.php">[Show all]</a><p>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);
	$text=stripslashes($text);

	}

}

# build year select box

$this_year=date('Y');
$max_year=$this_year+30;

$select_box='&nbsp;<select name="year">';

if ($year==0) { $select_box.='<option selected value=0>Every Year'; }
else { $select_box.='<option value=0>Every Year'; }

for ($index=$this_year; $index <= $max_year; $index++) {

if ($index==$year) { $select_box.="<option selected value=$index>$index"; }
else { $select_box.="<option value=$index>$index"; }

}

$select_box.='</select>';

if (empty($_POST['save']) && empty($_POST['homepage'])) { $homepage=1; }

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Reminder (By Date) &nbsp;&nbsp; <a href="help-reminder.php">[Help]</a>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Save Reminder',
'HEAD'=>'<script language="JavaScript" type="text/javascript">function tick_box(){document.form1.popup.checked=true;}</script>'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'day','VALUE'=>"$day",'OPTION'=>'day_array','TEXT'=>'Day','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'month','VALUE'=>"$month",'OPTION'=>'month_array','TEXT'=>'Month','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Year:','TEXT2'=>"$select_box",'SECTIONS'=>2));

if ($email_alert) { $cb='<input type="Checkbox" name="email_alert" value="1" checked id="tick_email_alert">'; }
else { $cb='<input type="Checkbox" name="email_alert" value="1" id="tick_email_alert">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb <label for=\"tick_email_alert\">Send Email Alert on that day</label>",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<hr color="#ececec" width="100%" size="1">'));

if ($homepage) { $cb='<input type="Checkbox" name="homepage" value="1" checked id="tick_homepage">'; }
else { $cb='<input type="Checkbox" name="homepage" value="1" id="tick_homepage">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb <label for=\"tick_homepage\">Show on Home Page</label>",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'warning_homepage','VALUE'=>"$warning_homepage",'OPTION'=>'warning_array_homepage','TEXT'=>'Show','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<hr color="#ececec" width="100%" size="1">'));

if ($popup) { $cb2='<input type="Checkbox" name="popup" value="1" checked id="tick_popup">'; }
else { $cb2='<input type="Checkbox" name="popup" value="1" id="tick_popup">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb2 <label for=\"tick_popup\">Show Pop-up Window at ..</label> ",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'hour','VALUE'=>"$hour",'OPTION'=>'hour_array','TEXT'=>'Hour','DO_NO_SORT_ARRAY'=>1,'ATTRIB'=>'onchange="javascript:tick_box()"'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'minute','VALUE'=>"$minute",'SIZE'=>3,'TEXT'=>'Minute','ATTRIB'=>'maxlength="2" onchange="javascript:tick_box()"'));

$html_instance->process();

?>