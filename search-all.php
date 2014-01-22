<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_search=sql_safe($_POST['text_search']);
$all_text='';

# search blog

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM {$base_instance->entity['BLOG']['MAIN']} WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-blog.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Blog</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td colspan=2 width="150">&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td align="center" width="100"><a href="edit-blog.php?blog_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:void(window.open(\'delete-blog.php?blog_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search contact

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_contact WHERE (firstname LIKE '%$text_search%' OR lastname LIKE '%$text_search%' OR notes LIKE '%$text_search%' OR company LIKE '%$text_search%' OR email LIKE '%$text_search%' OR address LIKE '%$text_search%' OR url LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-contact.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Contact</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td colspan=2 width="150">&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$firstname=$data[$index]->firstname;
		$lastname=$data[$index]->lastname;
		$company=$data[$index]->company;

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$firstname.' '.$lastname.' '.$company.'</td><td align="center" width="100"><a href="edit-contact.php?contact_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:DelContact(\''.$ID.'\')">[Delete]</a></td></tr>';

	}

	$all_text.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelContact(item){if(confirm("Delete Contact?")){http.open(\'get\',\'delete-contact.php?item=\'+item); http.send(null);}}</script>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search link

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_link WHERE (subtitle LIKE '%$text_search%' OR title LIKE '%$text_search%' OR keywords LIKE '%$text_search%' OR notes LIKE '%$text_search%' OR url LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-links.php?text_search='.$text_search.'">[View all]</a><p>';


} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Link</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td colspan="3" width="200">&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;
		$url=$data[$index]->url;

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'visit-link.php?link_id='.$ID.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$title.'</span></td><td align="center" width="100"><a href="visit-link.php?link_id='.$ID.'" target="_blank">[Visit]</a></td><td align="center" width="100"><a href="edit-link.php?link_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:void(window.open(\'delete-link.php?link_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search knowledge

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_knowledge WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-knowledge.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Knowledge</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td colspan=2 width="150">&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;

		if (!$title) {

			$text=$data[$index]->text;
			$title=substr($text,0,45);

		}

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td align="center" width="100"><a href="edit-knowledge.php?knowledge_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:DelKnow(\''.$ID.'\')">[Delete]</a></td></tr>';

	}

	$all_text.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelKnow(item){if(confirm("Delete Knowledge?")){http.open(\'get\',\'delete-knowledge.php?item=\'+item); http.send(null);}}</script>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search diary

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_diary WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' ORDER BY date ASC LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-diary.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Diary</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Date</b></td><td><b>Title</b></td><td colspan=2 width="150">&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;
		$date=$data[$index]->date;

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$date.'</td><td>'.$title.'</td><td align="center" width="100"><a href="add-diary.php?diary_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:DelDiary(\''.$ID.'\')">[Delete]</a></td></tr>';

	}

	$all_text.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelDiary(item){if(confirm("Delete Diary?")){http.open(\'get\',\'delete-diary.php?item=\'+item); http.send(null);}}</script>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search to-do

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_to_do WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-to-do.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - To-Do</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td colspan=2 width="150">&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;

		if (!$title) {

			$text=$data[$index]->text;
			$title=substr($text,0,45);

		}

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td align="center" width="100"><a href="edit-to-do.php?to_do_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:DelToDo(\''.$ID.'\')">[Delete]</a></td></tr>';

	}

	$all_text.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelToDo(item){if(confirm("Delete To-Do?")){http.open(\'get\',\'delete-to-do.php?item=\'+item); http.send(null);}}</script>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search notes

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_note WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-note.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Notes</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 border=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td colspan=2 width="150">&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;

		if (!$title) {

			$text=$data[$index]->text;
			$title=substr($text,0,45);

		}

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td align="center" width="100"><a href="edit-note.php?note_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:DelNote(\''.$ID.'\')">[Delete]</a></td></tr>';

	}

	$all_text.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelNote(item){if(confirm("Delete Note?")){http.open(\'get\',\'delete-note.php?item=\'+item); http.send(null);}}</script>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search reminder (days)

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM {$base_instance->entity['REMINDER']['DAYS']} WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-reminder-days.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Reminder (By Days)</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><b>Title</b></td><td align="center"><b>Days due</b></td><td align="center"><strong>Done</strong></td><td><b>Time</b></td><td><strong>Every .. days</strong></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=3>&nbsp;</td></tr>';

	$timestamp=time();

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;
		$done=$data[$index]->done;
		$what_time=$data[$index]->what_time;
		$frequency=$data[$index]->frequency;
		$last_reminded=$data[$index]->last_reminded;
		$homepage=$data[$index]->homepage;
		$popup=$data[$index]->popup;

		if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
		if ($popup==1) { $popup='Yes'; } else { $popup='No'; }

		preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$last_reminded,$dd);
		$temp=mktime(0,0,0,$dd[2],$dd[3]+$frequency,$dd[1]);
		$days_rounded=round(($timestamp-$temp)/86400);

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td align="center">'.$days_rounded.'</td><td align=center>'.$done.'</td><td>'.$what_time.'</td><td>'.$frequency.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td align="center" width="100"><a href="count-reminder.php?reminder_id='.$ID.'" target="status">[Done]</a></td><td align="center" width="100"><a href="edit-reminder-days.php?reminder_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:void(window.open(\'delete-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</td></tr></table></div>';

# search reminder (date)

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_reminder_date WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-reminder-date.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Reminder (By Date)</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td><b>Time</b></td><td width="80"><b>Day</b></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=2>&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;
		$what_time=$data[$index]->what_time;
		$day=$data[$index]->day;
		$month=$data[$index]->month;
		$year=$data[$index]->year;
		$homepage=$data[$index]->homepage;
		$popup=$data[$index]->popup;

		if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
		if ($popup==1) { $popup='Yes'; } else { $popup='No'; }

		if ($day==0) { $day='*'; }
		if ($month==0) { $month='*'; }
		if ($year==0) { $year='*'; }

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td>'.$what_time.'</td><td>'.$day.' / '.$month.' / '.$year.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td align="center" width="100"><a href="edit-reminder-date.php?reminder_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:void(window.open(\'delete-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search reminder (weekday)

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_reminder_weekday WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-reminder-weekday.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Reminder (By Weekday)</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</b></td><td><b>Time</b></td><td><strong>Day of the Week</strong></td><td><strong>Popup / Homepage</strong></td><td colspan=2>&nbsp;</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

		$all_days=''; $day_of_the_week=''; $day_of_the_week_temp='';

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;
		$what_time=$data[$index]->what_time;
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

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td>'.$what_time.'</td><td>'.$all_days.'</td><td>'.$popup.' / '.$homepage.'</td><td align="center" width="100"><a href="edit-reminder-weekday.php?reminder_id='.$ID.'">[Edit]</a></td><td align="center" width="100"><a href="javascript:void(window.open(\'delete-reminder-weekday.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search sticky notes

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_sticky_note WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.'<p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Sticky Notes</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	$all_text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

	for ($index=1; $index <= sizeof($data); $index++) {

		$all_days=''; $day_of_the_week=''; $day_of_the_week_temp='';

		$ID=$data[$index]->ID;
		$title=$data[$index]->title;
		$text=$data[$index]->text;

		$text=convert_square_bracket($text);
		$text=$base_instance->insert_links($text);
		$text=nl2br($text);

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'<p>'.$text.'</td><td width="100" align="center" valign="top"><br><a href="edit-sticky-note.php?note_id='.$ID.'">[Edit Note]</a></td></tr>';

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search reminder (hours)

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_reminder_hours WHERE (text LIKE '%$text_search%' OR title LIKE '%$text_search%') AND user='$userid' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-reminder-hours.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Reminder (By Hours)</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 bgcolor="#ffffff"><tr><td>';

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

		if ($notes) { $notes_link='<a href="javascript:void(window.open(\'edit-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=600,height=300,top=100,left=100\'))">[Notes]</a>'; }
		else { $notes_link='&nbsp;'; }

		$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$title.'</td><td>'.$number_of_hours.' hours '.$number_of_mins.' mins</td><td>'.$notes_link.'</td><td width="100" align="center" valign="top"><a href="edit-reminder-hours.php?reminder_id='.$ID.'">[Edit]</a></td><td width="100" align="center" valign="top"><a href="javascript:void(window.open(\'delete-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

# search database

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM organizer_database_text_fields_values WHERE user='$userid' AND value LIKE '%$text_search%' ORDER BY category_id LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

if ($fnd_rows > 0) {

	$fnd_text='<u>Found items:</u> '.$fnd_rows.' &nbsp; <a href="show-database-search.php?text_search='.$text_search.'">[View all]</a><p>';

} else { $fnd_text=''; }

$all_text.='<div align="center"><h1>Result - Database</h1>'.$fnd_text.'<table width="80%" cellpadding=5 cellspacing=0 bgcolor="#ffffff"><tr><td>';

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

		if (!empty($category_id)) { $previous_cat=$category_id; } else { $previous_cat=''; }

		$ID=$data[$index]->ID;
		$value=$data[$index]->value;
		$data_id=$data[$index]->data_id;
		$category_id=$data[$index]->category_id;

		if ($category_id!=$previous_cat) {

			$data2=$base_instance->get_data('SELECT title FROM '.$base_instance->entity['DATABASE']['CATEGORY'].' WHERE ID='.$category_id);

			if ($data2) {

				$title=$data2[1]->title;
				$all_text.='<h1>'.$title.'</h1>';

			}

		}

		$all_text.=show_database_fields($category_id,$data_id);

	}

	$all_text.='</table>';

} else { $all_text.='Nothing found'; }

$all_text.='</table></div>';

#

$html_instance->add_parameter(
	array(
		'TEXT'=>"$all_text",
		'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelData(item){if(confirm("Delete Data?")){http.open(\'get\',\'delete-database-data.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
	));

$html_instance->process();

#

function show_database_fields($category_id,$data_id) {

	global $base_instance;

	$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><strong>Date</strong></td>';

# get title of number fields

	$data_number=$base_instance->get_data("SELECT title,ID FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

	$total_number_fields=sizeof($data_number);

	for ($index=1; $index <= $total_number_fields; $index++) {

		$all_text.='<td><strong>'.$data_number[$index]->title.'</strong></td>';

	}

# get title of text fields

	$data_text=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

	$total_text_fields=sizeof($data_text);

	for ($index=1; $index <= $total_text_fields; $index++) {

		$all_text.='<td><strong>'.$data_text[$index]->title.'</strong></td>';

	}

# get title of select fields

	$data_select=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

	$total_select_fields=sizeof($data_select);

	for ($index=1; $index <= $total_select_fields; $index++) {

		$all_text.='<td><strong>'.$data_select[$index]->title.'</strong></td>';

	}

# get title of checkbox fields

	$data_checkbox=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE category_id=$category_id ORDER BY ID");

	$total_checkbox_fields=sizeof($data_checkbox);

	for ($index=1; $index <= $total_checkbox_fields; $index++) {

		$all_text.='<td><strong>'.$data_checkbox[$index]->title.'</strong></td>';

	}

#

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['MAIN']} WHERE ID=$data_id");

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;
		$datetime=$data[$index]->datetime;
		$title=$data[$index]->title;
		$text=$data[$index]->text;

		$datetime_converted=$base_instance->convert_date($datetime);

		$values='';

# get values of number fields

		for ($index1=1; $index1 <= sizeof($data_number); $index1++) {

			$number_field_id=$data_number[$index1]->ID;

			$data2=$base_instance->get_data("SELECT value FROM {$base_instance->entity['DATABASE']['NUMBER_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND number_field_id=$number_field_id");

			if (empty($data2)) { $values.='<td>&nbsp;</td>'; } else {

				for ($index2=1; $index2 <= sizeof($data2); $index2++) {

					$value=$data2[$index2]->value;

					$values.='<td>'.$value.'</td>';

				}

			}

		}

# get values of text fields

		for ($index1=1; $index1 <= sizeof($data_text); $index1++) {

			$text_field_id=$data_text[$index1]->ID;

			$data2=$base_instance->get_data("SELECT value FROM {$base_instance->entity['DATABASE']['TEXT_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND text_field_id=$text_field_id");

			if (empty($data2)) { $values.='<td>&nbsp;</td>'; } else {

				for ($index2=1; $index2 <= sizeof($data2); $index2++) {

					$value=$data2[$index2]->value;

					if (empty($value)) { $values.='<td>&nbsp;</td>'; } else { $values.='<td>'.nl2br($value).'</td>'; }

				}

			}

		}

# get values of select fields

		for ($index1=1; $index1 <= sizeof($data_select); $index1++) {

			$select_field_id=$data_select[$index1]->ID;

			$data2=$base_instance->get_data("SELECT value FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND select_field_id=$select_field_id");

			if (empty($data2)) { $values.='<td>&nbsp;</td>'; } else {

				for ($index2=1; $index2 <= sizeof($data2); $index2++) {

					$value=$data2[$index2]->value;

					$data_title=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE ID=$value");
					$select_title=$data_title[1]->title;

					$values.='<td>'.$select_title.'</td>';

				}

			}

		}

# get values of checkbox fields

		for ($index1=1; $index1 <= sizeof($data_checkbox); $index1++) {

			$checkbox_field_id=$data_checkbox[$index1]->ID;

			$data2=$base_instance->get_data("SELECT value,checkbox_field_id FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE data_id=$ID AND category_id=$category_id AND checkbox_field_id=$checkbox_field_id");

			$values.='<td>';

			if (empty($data2)) { $values.='&nbsp;'; } else {

				for ($index2=1; $index2 <= sizeof($data2); $index2++) {

					$value=$data2[$index2]->value;

					$data_title=$base_instance->get_data("SELECT title FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE ID=$value");
					$checkbox_title=$data_title[1]->title;

					$values.=$checkbox_title.', ';

				}

				$values=substr($values,0,-2);

			}

			$values.='</td>';

		}

#

		if ($title or $text) { $comments=nl2br('<strong>'.$title.'</strong><br>'.$text); } else { $comments=''; }

		$all_text.='<td colspan=2>&nbsp;</td></tr><tr><td valign="top" width="80"><div id="item'.$ID.'">'.$datetime_converted.'<br>'.$comments.'</div></td>'.$values.'<td width="100" align="center" valign="top"><a href="edit-database-data.php?data_id='.$ID.'">[Edit]</a></td><td width="100" align="center" valign="top"><a href="javascript:DelData(\''.$ID.'\')">[Delete]</a></td></tr>';

	}

	$all_text.='</table><p>';

	return $all_text;

}

?>