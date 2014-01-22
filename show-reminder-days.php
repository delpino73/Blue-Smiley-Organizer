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
'SUBENTITY'=>'DAYS',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'HEADER'=>'Reminder (By Days)',
'TEXT_CENTER'=>'<a href="add-reminder-days.php">[Add Reminder]</a> &nbsp;&nbsp; <a href="show-reminder-days-overview.php">[Detailed View]</a><p>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No reminder added yet','<a href="add-reminder-days.php">[Add Reminder]</a>');

} else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td align="center"><b>Days due</td><td align="center"><b>Done</td><td><b>Time</td><td><strong>Every .. days</strong></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=4>&nbsp;</td></tr>'; }

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

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td>'.$title.'</td><td align="center">'.$days_rounded.'</td>
<td align=center>'.$done.'</td><td>'.$what_time.'</td>
<td>'.$frequency.'</td><td>'.$popup.'</td><td>'.$homepage.'</td>
<td align="center">'.$notes_link.'</td>
<td align="center"><a href="javascript:void(window.open(\'edit-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td>
<td align="center"><a href="javascript:void(window.open(\'delete-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>