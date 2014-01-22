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
'SUBENTITY'=>'WEEKDAY',
'MAXHITS'=>40,
'WHERE'=>"WHERE day_of_the_week<>0 AND user='$userid' $query",
'HEADER'=>'Reminder (By Weekday)',
'TEXT_CENTER'=>'<a href="add-reminder-weekday.php">[Add Reminder]</a><p>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No reminder added yet','<a href="add-reminder-weekday.php">[Add Reminder]</a>');

} else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><b>Time</td><td><strong>Days of the Week</strong></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=3>&nbsp;</td></tr>'; }

for ($index=1; $index <= sizeof($data); $index++) {

	unset($day_of_the_week,$day_of_the_week_temp);

	$all_days='';

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

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td>'.$title.'</td>
<td>'.$what_time.'</td>
<td>'.$all_days.'</td>
<td>'.$popup.'</td>
<td>'.$homepage.'</td>
<td align="center">'.$notes_link.'</td>
<td align="center"><a href="javascript:void(window.open(\'edit-reminder-weekday.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td>
<td align="center"><a href="javascript:void(window.open(\'delete-reminder-weekday.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>