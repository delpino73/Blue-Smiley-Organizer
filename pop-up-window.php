<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$all_days='';

$today=date('Y-m-d'); $time=date('H:i:s'); $day_of_the_week=date('w')+1;
$today_day=date('d'); $today_month=date('m'); $today_year=date('Y');

# reminder by weekday

$text_weekday='<h2>Reminder (by Weekday)</h2>';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['WEEKDAY']} WHERE '$time' > what_time AND last_reminded < '$today' AND (day_of_the_week LIKE '%$day_of_the_week%' OR day_of_the_week=0) AND user='$userid' AND popup=1");

$text_weekday.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><b>Time</td><td><strong>Day of the Week</strong></td><td><strong>Popup</strong></td><td><strong>Homepage</strong></td><td colspan=3>&nbsp;</td></tr>';

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

	//$day_of_the_week=split('~',$day_of_the_week_temp);
	$day_of_the_week=explode('~',$day_of_the_week_temp);

	while (list($key,$val)=each($day_of_the_week)) {
		$all_days.=$base_instance->day_of_the_week_array[$val].' / ';
	}

	$all_days=substr($all_days,0,-2);

	if (empty($day)) { $day='*'; }
	if (empty($month)) { $month='*'; }
	if (empty($year)) { $year='*'; }

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'edit-reminder-weekday.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$title=$base_instance->insert_links($title);

	$text_weekday.='<tr><td><div id="item'.$ID.'">'.$title.'</div></td><td>'.$what_time.'</td><td>'.$all_days.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td>'.$notes_link.'</td><td><a href="javascript:void(window.open(\'edit-reminder-weekday.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td><td><a href="javascript:DelReminderWeekday(\''.$ID.'\')">[Delete]</a></td></tr>';

	$base_instance->query("UPDATE {$base_instance->entity['REMINDER']['WEEKDAY']} SET last_reminded='$today' WHERE ID=$ID");

}

$text_weekday.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelReminderWeekday(item){if(confirm("Delete Reminder?")){http.open(\'get\',\'delete-reminder-by-weekday.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

# reminder by date

$text_date='<h2>Reminder (by Date)</h2>';

$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DATE']} WHERE '$time' > what_time AND last_reminded < '$today' AND (day='$today_day' OR day=0) AND (month='$today_month' OR month=0) AND (year='$today_year' OR year=0) AND user='$userid' AND popup=1");

$text_date.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><b>Time</b></td><td width="80"><b>Day</b></td><td><strong>Popup</strong><td><strong>Homepage</strong></td><td colspan=3>&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data2); $index++) {

	$ID=$data2[$index]->ID;
	$title=$data2[$index]->title;
	$what_time=$data2[$index]->what_time;
	$day=$data2[$index]->day;
	$month=$data2[$index]->month;
	$year=$data2[$index]->year;
	$notes=$data2[$index]->text;
	$homepage=$data2[$index]->homepage;
	$popup=$data2[$index]->popup;

	$title=$base_instance->insert_links($title);

	if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
	if ($popup==1) { $popup='Yes'; } else { $popup='No'; }

	if ($day==0) { $day='*'; }
	if ($month==0) { $month='*'; }
	if ($year==0) { $year='*'; }

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$text_date.='<tr><td><div id="item'.$ID.'">'.$title.'</div></td><td>'.$what_time.'</td><td>'.$day.' / '.$month.' / '.$year.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td>'.$notes_link.'</td><td><a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))">[Edit]</a></td><td><a href="javascript:DelReminderDate(\''.$ID.'\')">[Delete]</a></td></tr>';

	$base_instance->query("UPDATE {$base_instance->entity['REMINDER']['DATE']} SET last_reminded='$today' WHERE ID=$ID");

}

$text_date.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelReminderDate(item){if(confirm("Delete Reminder?")){http.open(\'get\',\'delete-reminder-by-date.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

# reminder by days

$text_days='<h2>Reminder (by Days)</h2>';

$data3=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DAYS']} WHERE (DATE_ADD(last_reminded, INTERVAL frequency DAY)='$today' OR DATE_ADD(last_reminded, INTERVAL frequency DAY)<'$today') AND what_time<'$time' AND user='$userid' AND popup=1");

$text_days.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><b>Time</td><td><strong>Every .. days</strong></td><td><strong>Popup<strong></td><td><strong>Homepage</strong></td><td colspan=3>&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data3); $index++) {

	$ID=$data3[$index]->ID;
	$title=$data3[$index]->title;
	$what_time=$data3[$index]->what_time;
	$frequency=$data3[$index]->frequency;
	$notes=$data3[$index]->text;
	$homepage=$data3[$index]->homepage;
	$popup=$data3[$index]->popup;

	if ($homepage==1) { $homepage='Yes'; } else { $homepage='No'; }
	if ($popup==1) { $popup='Yes'; } else { $popup='No'; }

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'edit-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$title=$base_instance->insert_links($title);

	$text_days.='<tr><td><div id="item'.$ID.'">'.$title.'</div></td><td>'.$what_time.'</td><td>'.$frequency.'</td><td>'.$popup.'</td><td>'.$homepage.'</td><td>'.$notes_link.'</td><td><a href="javascript:void(window.open(\'edit-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td><td><a href="javascript:DelReminderDays(\''.$ID.'\')">[Delete]</a></td></tr>';

	$base_instance->query("UPDATE {$base_instance->entity['REMINDER']['DAYS']} SET last_reminded='$today' WHERE ID=$ID");

}

$text_days.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelReminderDays(item){if(confirm("Delete Reminder?")){http.open(\'get\',\'delete-reminder-by-days.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

# reminder by hours

$now=time();

$text_hours='<h2>Reminder (by Hours)</h2>';

$data4=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['HOURS']} WHERE ($now-last_reminded) > frequency AND user='$userid'");

$text_hours.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><b>Title</td><td><strong>Frequency</strong></td><td colspan=3>&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data4); $index++) {

	$ID=$data4[$index]->ID;
	$title=$data4[$index]->title;
	$frequency=$data4[$index]->frequency;
	$notes=$data4[$index]->text;

	# calculate frequency format

	$number_of_hours=floor($frequency / 3600);
	$hours_in_second=$number_of_hours * 3600;

	$frequency-=$hours_in_second;

	$number_of_mins=floor($frequency / 60);

	#

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'edit-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Notes]</a>'; }
	else { $notes_link='&nbsp;'; }

	$title=$base_instance->insert_links($title);

	$text_hours.='<tr><td><div id="item'.$ID.'">'.$title.'</div></td><td>'.$number_of_hours.' hours '.$number_of_mins.' mins</td><td>'.$notes_link.'</td><td><a href="javascript:void(window.open(\'edit-reminder-hours.php?reminder_id='.$ID.'\',\'\',\'width=600,height=300,top=100,left=100\'))">[Edit]</a></td><td><a href="javascript:DelReminderHours(\''.$ID.'\')">[Delete]</a></td></tr>';

	$base_instance->query("UPDATE {$base_instance->entity['REMINDER']['HOURS']} SET last_reminded='$now' WHERE ID=$ID");

}

$text_hours.='</table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelReminderHours(item){if(confirm("Delete Reminder?")){http.open(\'get\',\'delete-reminder-by-hours.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

# live support

$text_chat='<h2>Live Support Request</h2>';

$data5=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LIVE_CHAT']['REQUEST']} WHERE user='$userid' AND accepted=0 AND popup=0 LIMIT 1");

$text_chat.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data5); $index++) {

	$chatid=$data5[$index]->ID;
	$name=$data5[$index]->name;
	$email=$data5[$index]->email;
	$question=$data5[$index]->question;
	$token=$data5[$index]->token;
	$referrer=$data5[$index]->referrer;
	$IP=$data5[$index]->IP;

	if (!$name) { $name='unknown'; }
	if (!$email) { $email='unknown'; }
	if (!$question) { $question='unknown'; }
	if (!$IP) { $IP='unknown'; }
	if (!$referrer) { $referrer='unknown'; }

	$usertoken=md5(uniqid(rand(),true));

	$text_chat.='<tr><td><strong>Name:</strong> '.$name.'</td><td><strong>Email:</strong> '.$email.'</td></tr><tr><td colspan="2"><strong>Question:</strong> '.$question.'</td></tr><tr><td colspan="2"><strong>IP:</strong> '.$IP.'</td></tr><tr><td colspan="2"><strong>Referrer:</strong> '.$referrer.'</td></tr><tr><td colspan="2" align="center">

<form action="chat-advanced.php">
<input type="hidden" name="token" value="'.$token.'">
<input type="hidden" name="usertoken" value="'.$usertoken.'">
<input type="submit" value="Accept Chat">
</form>

</td></tr>';

	$base_instance->query("UPDATE {$base_instance->entity['LIVE_CHAT']['REQUEST']} SET popup=1 WHERE token='$token'");

}

$text_chat.='</table>';

# instant message

$text_im='<h2>New Instant Message</h2>';

$data6=$base_instance->get_data("SELECT * FROM {$base_instance->entity['INSTANT_MESSAGE']['MAIN']} WHERE receiver='$userid' AND popup=0 LIMIT 1");

$text_im.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data6); $index++) {

	$ID=$data6[$index]->ID;
	$user=$data6[$index]->user;
	$text=$data6[$index]->text;
	$answer_id=$data6[$index]->answer_id;

	#

	if ($answer_id > 0) {

		$data7=$base_instance->get_data("SELECT * FROM {$base_instance->entity['INSTANT_MESSAGE']['MAIN']} WHERE ID=$answer_id");
		$text2=$data7[1]->text;

		$answer_to='<strong>From:</strong> You<p>'.$text2.'<p>';

	} else { $answer_to=''; }

	#

	$text=convert_square_bracket($text);
	$text=$base_instance->insert_links($text);
	$text=nl2br($text);

	$data_user=$base_instance->get_data("SELECT username FROM organizer_user WHERE ID=$user");

	if (!empty($data_user)) { $username=$data_user[1]->username; } else { $username='[deleted]'; }

	$datetime=date('Y-m-d H:i:s');

	$text_im.='<tr><td>'.$answer_to.'<p><strong>From:</strong> <a href="show-user.php?username='.$username.'" target="_blank">'.$username.'</a><p>'.$text.'<div align="center">
<form action="add-instant-message.php" method="post">
<input type="Hidden" name="datetime" value="'.$datetime.'">
<input type="Hidden" name="receiver" value="'.$user.'">
<input type="Hidden" name="answer_id" value="'.$ID.'">';

	if (empty($_GET['manual'])) { $text_im.='<input type="Hidden" name="close" value="1">'; }

	$text_im.='<textarea rows=3 cols=60 name=text wrap></textarea><br>
<input type="SUBMIT" value="Reply Instant Message" name="save">
</form></div></td></tr>';

	$base_instance->query("UPDATE {$base_instance->entity['INSTANT_MESSAGE']['MAIN']} SET popup=1 WHERE ID='$ID'");

}

$text_im.='</table>';

#

$all_text='<br><table border=0 cellspacing=0 cellpadding=15 bgcolor="#ffffff" class="pastel"><tr><td>';

if ($data or $data2 or $data3 or $data4 or $data5 or $data6) {

	if (isset($_GET['manual'])) { $all_text.='To automatically open new messages by pop-up window please deactivate pop-up blockers.<p>'; }

	if ($data) { $all_text.=$text_weekday; }
	if ($data2) { $all_text.=$text_date; }
	if ($data3) { $all_text.=$text_days; }
	if ($data4) { $all_text.=$text_hours; }
	if ($data5) { $all_text.=$text_chat; }
	if ($data6) { $all_text.=$text_im; }

} else { $all_text.='No new messages, Pop-up Window has been successfully opened.'; }

$all_text.='</td></tr></table>';

$js='<script language="JavaScript" type="text/javascript">self.focus();';

if (stristr($_SERVER['HTTP_USER_AGENT'],'firefox')) { $js.='
setTimeout("show_alert()",5000);
var seen=2;
function show_alert() { if (seen==1) {} else { alert("Attention!"); }  }
document.onmousemove=seen_it;
function seen_it() { seen=1; }
'; }

$js.='</script>';

$html_instance->add_parameter(
	array(
		'HEAD'=>$js,
		'TEXT_CENTER'=>$all_text
	));

$html_instance->process();

?>