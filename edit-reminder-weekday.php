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
	$day_of_the_week=$_POST['day_of_the_week'];
	$hour=(int)$_POST['hour'];
	$minute=(int)$_POST['minute'];

	$homepage=isset($_POST['homepage']) ? 1 : 0;
	$popup=isset($_POST['popup']) ? 1 : 0;

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (empty($day_of_the_week)) { $error.='<li> Tick at least one day of the week'; }

	if (empty($homepage) && empty($popup)==1) { $error.='<li> Tick <strong>Show on Home Page</strong> and / or <strong>Show Pop-up Window</strong>'; }

	if (!$error) {

	$all_days='';

	for ($hh=0; $hh < count($day_of_the_week); $hh++) { $all_days.="$day_of_the_week[$hh]~"; };
	$all_days=substr($all_days,0,-1);

	if (!$minute) { $minute='00'; }

	$what_time="$hour:$minute:00";

	$text=trim($text);

	$base_instance->query('UPDATE '.$base_instance->entity['REMINDER']['WEEKDAY'].' SET title="'.sql_safe($title).'",text="'.sql_safe($text).'",what_time="'.$what_time.'",day_of_the_week="'.sql_safe($all_days).'",last_reminded="1970-01-01",homepage='.$homepage.',popup='.$popup.' WHERE user='.$userid.' AND ID='.$reminder_id);

	header('Location: close-me.php'); exit;

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);
	$text=stripslashes($text);

	}

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['WEEKDAY']} WHERE user='$userid' AND ID=$reminder_id");

	if (!$data) { $base_instance->show_message('Reminder not found'); exit; }

	$title=$data[1]->title;
	$homepage=$data[1]->homepage;
	$popup=$data[1]->popup;
	$day_of_the_week_temp=$data[1]->day_of_the_week;
	$day_of_the_week=explode('~',$day_of_the_week_temp);

	$what_time=$data[1]->what_time;

	preg_match("/([0-9]+):([0-9]+):([0-9]+)/",$what_time,$ll);

	$hour=$ll[1];
	$minute=$ll[2];

	$text=$data[1]->text;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Reminder',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'400',
'BUTTON_TEXT'=>'Update Reminder',
'HEAD'=>'<script language="JavaScript" type="text/javascript">function tick_box(){document.form1.popup.checked=true;}</script>'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'reminder_id','VALUE'=>$reminder_id));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'checkbox','NAME'=>'day_of_the_week','VALUE'=>$day_of_the_week,'TEXT'=>'Days of the Week','FIELD_ARRAY'=>'day_of_the_week_array'));

if ($homepage) { $cb='<input type="Checkbox" name="homepage" value="1" checked id="tick_homepage">'; }
else { $cb='<input type="Checkbox" name="homepage" value="1" id="tick_homepage">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb <label for=\"tick_homepage\">Show on Home Page</label>",'SECTIONS'=>2));

if ($popup) { $cb2='<input type="Checkbox" name="popup" value="1" checked id="tick_popup">'; }
else { $cb2='<input type="Checkbox" name="popup" value="1" id="tick_popup">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb2 <label for=\"tick_popup\">Show Pop-up Window at .. </label>",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'hour','VALUE'=>$hour,'OPTION'=>'hour_array','TEXT'=>'Hour','DO_NO_SORT_ARRAY'=>1,'ATTRIB'=>'onchange="javascript:tick_box()"'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'minute','VALUE'=>$minute,'SIZE'=>3,'TEXT'=>'Minute','ATTRIB'=>'maxlength="2" onchange="javascript:tick_box()"'));

$html_instance->process();

?>