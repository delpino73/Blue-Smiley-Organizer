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
	$warning_homepage=(int)$_POST['warning_homepage'];
	$day=(int)$_POST['day'];
	$month=(int)$_POST['month'];
	$year=(int)$_POST['year'];
	$hour=(int)$_POST['hour'];
	$minute=(int)$_POST['minute'];

	$homepage=isset($_POST['homepage']) ? 1 : 0;
	$popup=isset($_POST['popup']) ? 1 : 0;
	$email_alert=isset($_POST['email_alert']) ? 1 : 0;

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

	if ($homepage==0 && $popup==0 && $email_alert==0) { $error.='<li> Tick at least one of the following <strong>Send Email Alert</strong>, <strong>Show on Home Page</strong> or <strong>Show Pop-up Window</strong>'; }

	if (!$error) {

	if (!$minute) { $minute='00'; }

	$what_time="$hour:$minute:00";

	$text=trim($text);

	$base_instance->query('UPDATE '.$base_instance->entity['REMINDER']['DATE'].' SET title="'.sql_safe($title).'",what_time="'.$what_time.'",day='.$day.',month='.$month.',year='.$year.',last_reminded="1970-01-01",homepage='.$homepage.',popup='.$popup.',email_alert='.$email_alert.',warning_homepage='.$warning_homepage.',text="'.sql_safe($text).'" WHERE user='.$userid.' AND ID='.$reminder_id);

	header('Location: close-me.php'); exit;

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);
	$text=stripslashes($text);

	}

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DATE']} WHERE user='$userid' AND ID=$reminder_id");

	if (!$data) { $base_instance->show_message('Reminder not found'); exit; }

	$title=$data[1]->title;
	$day=$data[1]->day;
	$month=$data[1]->month;
	$year=$data[1]->year;
	$what_time=$data[1]->what_time;
	$warning_homepage=$data[1]->warning_homepage;
	$homepage=$data[1]->homepage;
	$email_alert=$data[1]->email_alert;
	$popup=$data[1]->popup;
	$text=$data[1]->text;

	#ereg("([0-9]+):([0-9]+):([0-9]+)",$what_time,$ll);
	preg_match("/([0-9]+):([0-9]+):([0-9]+)/",$what_time,$ll);

	$hour=$ll[1];
	$minute=$ll[2];

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

#

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Reminder',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update Reminder',
'HEAD'=>'<script language="JavaScript" type="text/javascript">function tick_box(){document.form1.popup.checked=true;}</script>'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'reminder_id','VALUE'=>$reminder_id));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'day','VALUE'=>$day,'OPTION'=>'day_array','TEXT'=>'Day','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'month','VALUE'=>$month,'OPTION'=>'month_array','TEXT'=>'Month','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Year:','TEXT2'=>$select_box,'SECTIONS'=>2));

if ($email_alert) { $cb='<input type="Checkbox" name="email_alert" value="1" checked id="tick_email_alert">'; }
else { $cb='<input type="Checkbox" name="email_alert" value="1" id="tick_email_alert">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb <label for=\"tick_email_alert\">Send Email Alert on that day</label>",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<hr color="#ececec" width="95%">'));

if ($homepage) { $cb='<input type="Checkbox" name="homepage" value="1" checked id="tick_homepage">'; }
else { $cb='<input type="Checkbox" name="homepage" value="1" id="tick_homepage">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb <label for=\"tick_homepage\">Show on Home Page</label>",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'warning_homepage','VALUE'=>"$warning_homepage",'OPTION'=>'warning_array_homepage','TEXT'=>'Show','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<hr color="#ececec" width="95%">'));

if ($popup) { $cb2='<input type="Checkbox" name="popup" value="1" checked id="tick_popup">'; }
else { $cb2='<input type="Checkbox" name="popup" value="1" id="tick_popup">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb2 <label for=\"tick_popup\">Show Pop-up Window at ..</label> ",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'hour','VALUE'=>$hour,'OPTION'=>'hour_array','TEXT'=>'Hour','DO_NO_SORT_ARRAY'=>1,'ATTRIB'=>'onchange="javascript:tick_box()"'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'minute','VALUE'=>$minute,'SIZE'=>3,'TEXT'=>'Minute','ATTRIB'=>'maxlength="2" onchange="javascript:tick_box()"'));

$html_instance->process();

?>