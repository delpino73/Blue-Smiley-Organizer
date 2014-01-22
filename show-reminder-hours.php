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
'SUBENTITY'=>'HOURS',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'HEADER'=>'Reminder (By Hours)',
'TEXT_CENTER'=>'<a href="add-reminder-hours.php">[Add Reminder]</a><p>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No reminder added yet','<a href="add-reminder-hours.php">[Add Reminder]</a>');

} else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><strong>Title</td><td><strong>Frequency</strong></td><td colspan=3 width="200">&nbsp;</td></tr>'; }

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

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td>'.$title.'</td>
<td>'.$number_of_hours.' hours '.$number_of_mins.' mins</td>
<td align="center">'.$notes_link.'</td>
<td align="center"><a href="javascript:void(window.open(\'edit-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=600,height=300,top=100,left=100\'))">[Edit]</a></td>
<td align="center"><a href="javascript:void(window.open(\'delete-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>