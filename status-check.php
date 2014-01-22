<?php

require 'config.php';

@mysql_connect(_DB_HOST,_DB_USER,_DB_PW) or die('Could not connect to database');
mysql_select_db(_DB_NAME) or die('Could not find database');

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

if (isset($_COOKIE['sid'])) { $sid=(int)$_COOKIE['sid']; } else { echo '<font color="#ff0000">No Session ID</font><p><a href="login.php" target="_top">Click here to login again</a>'; exit; }

$res=mysql_query("SELECT * FROM organizer_session WHERE session_id='$sid'");
$data=mysql_fetch_object($res);

if ($data) {

	$userid=$data->user;
	$timezone=$data->timezone;

} else { echo 'no session id found!'; exit; }

if (empty($userid)) { echo '<font color="#ff0000">No Session ID</font><p><a href="login.php" target="_top">Click here to login again</a>'; exit; }

if ($timezone > 0 && $timezone < 13) { date_default_timezone_set('Etc/GMT+'.$timezone); }
else if ($timezone < 0 or $timezone==0) { date_default_timezone_set('Etc/GMT'.$timezone); }
else if ($timezone==13) { date_default_timezone_set('Europe/London'); }
else if ($timezone==14) { date_default_timezone_set('Europe/Berlin'); }
else if ($timezone==15) { date_default_timezone_set('US/Pacific'); }
else if ($timezone==16) { date_default_timezone_set('US/Mountain'); }
else if ($timezone==17) { date_default_timezone_set('US/Central'); }
else if ($timezone==18) { date_default_timezone_set('US/Eastern'); }
else if ($timezone==19) { date_default_timezone_set('Asia/Jakarta'); }
else if ($timezone==20) { date_default_timezone_set('Hongkong'); }
else if ($timezone==21) { date_default_timezone_set('Japan'); }
else if ($timezone==22) { date_default_timezone_set('Israel'); }
else { date_default_timezone_set('Europe/London'); }

# update session id

$now=time();

mysql_query("UPDATE organizer_session SET last_active=$now WHERE session_id=$sid");

$today=date('Y-m-d'); $time=date('H:i:s'); $day_of_the_week=date('w')+1;
$today_day=date('d'); $today_month=date('m'); $today_year=date('Y');

# check reminder (weekday)

$res=mysql_query("SELECT ID FROM organizer_reminder_weekday WHERE '$time' > what_time AND last_reminded < '$today' AND (day_of_the_week LIKE '%$day_of_the_week%' OR day_of_the_week=0) AND user='$userid' AND popup=1 LIMIT 1");

$data=mysql_fetch_object($res);

if ($data) { $found=1; }

# check reminder (date)

if (empty($found)) {

	$res=mysql_query("SELECT ID FROM organizer_reminder_date WHERE '$time' > what_time AND last_reminded < '$today' AND (day='$today_day' OR day=0) AND (month='$today_month' OR month=0) AND (year='$today_year' OR year=0) AND user='$userid' AND popup=1 LIMIT 1");

	$data=mysql_fetch_object($res);

	if ($data) { $found=1; }

}

# check reminder (days)

if (empty($found)) {

	$res=mysql_query("SELECT ID FROM organizer_reminder_days WHERE (DATE_ADD(last_reminded, INTERVAL frequency DAY)='$today' OR DATE_ADD(last_reminded, INTERVAL frequency DAY)<'$today') AND what_time<'$time' AND user='$userid' AND popup=1 LIMIT 1");

	$data=mysql_fetch_object($res);

	if ($data) { $found=1; }

}

# check reminder (hours)

if (empty($found)) {

	$now=time();

	$res=mysql_query("SELECT ID FROM organizer_reminder_hours WHERE
($now-last_reminded) > frequency AND user='$userid' LIMIT 1");

	$data=mysql_fetch_object($res);

	if ($data) { $found=1; }

}

# check live support requests

if (empty($found)) {

	$now=time();

	$res=mysql_query("SELECT ID FROM organizer_chat_request WHERE user='$userid' AND popup=0 LIMIT 1");

	$data=mysql_fetch_object($res);

	if ($data) { $found_live_request=1; }

}

# check instant messages

if (empty($found)) {

	$now=time();

	$res=mysql_query("SELECT ID FROM organizer_instant_message WHERE receiver='$userid' AND popup=0 LIMIT 1");

	$data=mysql_fetch_object($res);

	if ($data) { $found_instant_message=1; }

}

$time=time()-600; # in seconds

mysql_query("DELETE FROM organizer_session WHERE last_active < $time");

$res=mysql_query('SELECT COUNT(*) AS cnt FROM organizer_session WHERE online_status=1');
$data=mysql_fetch_object($res);
$user_online=$data->cnt;

#

if (isset($found)) { echo '1'; }
else if (isset($found_live_request)) { echo '2'; }
else if (isset($found_instant_message)) { echo '3'; }
else { echo '<table cellpadding="2"><tr><td><b><font size="3">'.date ('G:i').'</b></font></b></td></tr>
<tr><td><font size="1">'.date('D, j. M').'</td></tr>
<tr><td><font size="2"><a href="show-user-online.php" target="main">User Online: '.$user_online.'</a></font></td></tr></table>'; }

?>