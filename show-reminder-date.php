<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_search=isset($_REQUEST['text_search']) ? sql_safe($_REQUEST['text_search']) : '';
$whole_words=isset($_POST['whole_words']) ? 1 : '';

if ($text_search && $whole_words) { $query=" AND (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') "; $param='text_search='.$text_search.'&amp;'; }

else if ($text_search) { $query=" AND (text LIKE '%$text_search%' OR title LIKE '%$text_search%') "; $param='text_search='.$text_search.'&amp;'; }

else { $query=''; $param=''; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'REMINDER',
'SUBENTITY'=>'DATE',
'ORDER_COL'=>'year,month,day',
'ORDER_TYPE'=>'ASC',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'HEADER'=>'Reminder (By Date)',
'TEXT_CENTER'=>'<a href="add-reminder-date.php">[Add Reminder]</a><p>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No reminder added yet','<a href="add-reminder-date.php">[Add Reminder]</a>');

} else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><b>Time</b></td><td width="80"><b>Date</b></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td><strong>Email</strong></td><td colspan=3>&nbsp;</td></tr>'; }

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
	$email_alert=$data[$index]->email_alert;

	if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
	if ($popup==1) { $popup='Yes'; } else { $popup='No'; }
	if ($email_alert==1) { $email_alert='Yes'; } else { $email_alert='No'; }

	if ($day==0) { $day='*'; }
	if ($month==0) { $month='*'; }
	else if ($month==1) { $month='Jan'; }
	else if ($month==2) { $month='Feb'; }
	else if ($month==3) { $month='Mar'; }
	else if ($month==4) { $month='Apr'; }
	else if ($month==5) { $month='May'; }
	else if ($month==6) { $month='Jun'; }
	else if ($month==7) { $month='Jul'; }
	else if ($month==8) { $month='Aug'; }
	else if ($month==9) { $month='Sept'; }
	else if ($month==10) { $month='Oct'; }
	else if ($month==11) { $month='Nov'; }
	else if ($month==12) { $month='Dec'; }
	else { $month=''; }
	if ($year==0) { $year='*'; }

	if ($base_instance->dateformat==3) { $date=$month.' / '.$day.' / '.$year; }
	else { $date=$day.' / '.$month.' / '.$year; }

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'show-notes-text.php?date_reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td>'.$title.'</td>
<td>'.$what_time.'</td><td>'.$date.'</td><td>'.$popup.'</td>
<td>'.$homepage.'</td><td>'.$email_alert.'</td>
<td align="center">'.$notes_link.'</td>
<td align="center"><a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))">[Edit]</a></td>
<td align="center"><a href="javascript:void(window.open(\'delete-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>