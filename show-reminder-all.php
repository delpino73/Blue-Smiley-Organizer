<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_search=isset($_REQUEST['text_search']) ? sql_safe($_REQUEST['text_search']) : '';
$whole_words=isset($_POST['whole_words']) ? 1 : '';

# search by days

if ($whole_words) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DAYS']} WHERE (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') AND user='$userid' LIMIT 100");

} else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DAYS']} WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 100");

}

$all_text='<br><center><span class="header">Result - Reminder (By Days)</span></center><p>';

$all_text.='<div align="center"><table width=80% cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td align="center"><b>Days due</td><td align="center"><b>Done</td><td><b>Time</td><td><strong>Every .. days</strong></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=4>&nbsp;</td></tr>';

$timestamp=time();

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;
	$done=$data[$index]->done;
	$what_time=$data[$index]->what_time;
	$frequency=$data[$index]->frequency;
	$notes=$data[$index]->text;
	$last_reminded=$data[$index]->last_reminded;
	$homepage=$data[$index]->homepage;
	$popup=$data[$index]->popup;

	if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
	if ($popup==1) { $popup='Yes'; } else { $popup='No'; }

	preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$last_reminded,$dd);
	$temp=mktime(0,0,0,$dd[2],$dd[3]+$frequency,$dd[1]);
	$days_rounded=round(($timestamp-$temp)/86400);

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'show-notes-text.php?days_reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td align="center">'.$days_rounded.'</td><td align=center>'.$done.'</td><td>'.$what_time.'</td><td>'.$frequency.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td align="center">'.$notes_link.'</td><td align="center"><a href="count-reminder.php?reminder_id='.$ID.'" target="status">[Done]</a></td><td align="center"><a href="javascript:void(window.open(\'edit-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td><td align="center"><a href="javascript:void(window.open(\'delete-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</td></tr></table></div>';

# search by date

if ($whole_words) {

$data=$base_instance->get_data("SELECT * FROM organizer_reminder_date WHERE (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') AND user='$userid' LIMIT 100");

} else {

$data=$base_instance->get_data("SELECT * FROM organizer_reminder_date WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 100");

}

$all_text.='<p><center><span class="header">Result - Reminder (By Date)</span></center><p>

<div align="center"><table width=80% cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><b>Time</b></td><td width="80"><b>Day</b></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=3>&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;
	$what_time=$data[$index]->what_time;
	$day=$data[$index]->day;
	$month=$data[$index]->month;
	$year=$data[$index]->year;
	$notes=$data[$index]->text;
	$homepage=$data[$index]->homepage;
	$popup=$data[$index]->popup;

	if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
	if ($popup==1) { $popup='Yes'; } else { $popup='No'; }

	if ($day==0) { $day='*'; }
	if ($month==0) { $month='*'; }
	if ($year==0) { $year='*'; }

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'show-notes-text.php?date_reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td>'.$what_time.'</td><td>'.$day.' / '.$month.' / '.$year.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td align="center">'.$notes_link.'</td><td align="center"><a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))">[Edit]</a></td><td align="center"><a href="javascript:void(window.open(\'delete-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search by weekday

if ($whole_words) {

$data=$base_instance->get_data("SELECT * FROM organizer_reminder_weekday WHERE (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') AND user='$userid' LIMIT 100");

} else {

$data=$base_instance->get_data("SELECT * FROM organizer_reminder_weekday WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 100");

}

$all_text.='<p><center><span class="header">Result - Reminder (By Weekday)</span></center><p>

<div align="center"><table width=80% cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><b>Time</td><td><strong>Day of the Week</strong></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=3>&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

	$all_days=''; $day_of_the_week=''; $day_of_the_week_temp='';

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;
	$what_time=$data[$index]->what_time;
	$notes=$data[$index]->text;
	$homepage=$data[$index]->homepage;
	$popup=$data[$index]->popup;

	if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
	if ($popup==1) { $popup='Yes'; } else { $popup='No'; }

	$day_of_the_week_temp=$data[$index]->day_of_the_week;
	$day_of_the_week=explode('~',$day_of_the_week_temp);

	while (list($key,$val)=each($day_of_the_week)) {
	$all_days.=$base_instance->day_of_the_week_array[$val].' / ';
	}

	$all_days=substr($all_days,0,-2);

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'show-notes-text.php?weekday_reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td>'.$what_time.'</td><td>'.$all_days.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td align="center">'.$notes_link.'</td><td align="center"><a href="javascript:void(window.open(\'edit-reminder-weekday.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td><td align="center"><a href="javascript:void(window.open(\'delete-reminder-weekday.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search by hours

if ($whole_words) {

$data=$base_instance->get_data("SELECT * FROM organizer_reminder_hours WHERE (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') AND user='$userid' LIMIT 100");

} else {

$data=$base_instance->get_data("SELECT * FROM organizer_reminder_hours WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 100");

}

$all_text.='<p><center><span class="header">Result - Reminder (By Hours)</span></center><p>

<div align="center"><table width=80% cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><strong>Title</td><td><strong>Frequency</strong></td><td colspan=3>&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;
	$frequency=$data[$index]->frequency;
	$notes=$data[$index]->text;

	# calculate frequency format

	$number_of_hours=floor($frequency / 3600);
	$hours_in_second=$number_of_hours * 3600;

	$frequency-=$hours_in_second;

	$number_of_mins=floor($frequency / 60);

	#

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'show-notes-text.php?hours_reminder_id='.$ID.'\',\'\',\'width=600,height=300,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td>'.$number_of_hours.' hours '.$number_of_mins.' mins</td><td align="center">'.$notes_link.'</td><td align="center"><a href="javascript:void(window.open(\'edit-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=600,height=300,top=100,left=100\'))">[Edit]</a></td><td align="center"><a href="javascript:void(window.open(\'delete-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

$html_instance->add_parameter(
array(
'TEXT'=>"$all_text"
));

$html_instance->process();

?>