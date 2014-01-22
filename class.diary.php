<?php

# PHP Calendar Class Version 1.4 (5th March 2001)
# URL: http://www.cascade.org.uk/software/php/calendar/

class diary {

var $start_day=0;
var $days_in_month=array(31,28,31,30,31,30,31,31,30,31,30,31);
var $day_names=array('S','M','T','W','T','F','S');
var $day_names2=array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

function get_days_in_month($month, $year) {

	if ($month < 1 || $month > 12) { return 0;}

	$d=$this->days_in_month[$month-1];

	if ($month==2) { # check for leap year if February

	if ($year%4==0) { if ($year%100==0) { if ($year%400==0) { $d=29; } }
	else { $d=29; } } }

	return $d;

}

function get_month_view($m, $y, $nav=1) {

	global $base_instance;

	$getdate=getdate(time());

	if (!$m) { $m=date('m'); } else if (strlen($m)==1) { $m='0'.$m; }
	if (!$y) { $y=$getdate['year']; }

	$userid=$base_instance->user;

	# find bold days

	$data=$base_instance->get_data("SELECT DATE_FORMAT(date,'%e') AS NN FROM {$base_instance->entity['DIARY']['MAIN']} WHERE date like '%$y-$m-%' AND user='$userid'");

	$array_bold=array();

	for ($index=1; $index <= sizeof($data); $index++) {
	$number=$data[$index]->NN;
	array_push($array_bold, $number);
	}

	unset($text);

	$a=$this->adjust_date($m, $y);
	$month=$a[0];
	$year=$a[1];

	$days_in_month=$this->get_days_in_month($month,$year);
	$date=getdate(mktime(12,0,0,$month,1,$year));

	$first=$date['wday'];
	$month_name=$base_instance->month_names[$month];

	$prev=$this->adjust_date($month-1, $year);
	$next=$this->adjust_date($month+1, $year);

	$prev=$this->adjust_date($month, $year-1);
	$next=$this->adjust_date($month, $year+1);

	$prev_year=$year-1;	$next_year=$year+1; $prev_month=$month-1;

	if ($prev_month==0) { $prev_month=12; $prev_year_month=$prev_year; } else { $prev_year_month=$year; }

	$next_month=$month+1;

	if ($next_month==13) { $next_month=1; $next_year_month=$next_year; } else { $next_year_month=$year; }

	$text='<table bgcolor="#ffffff" class="pastel2">';

	if ($nav==1) {

	$text.='<tr><td align="center" valign="top"><a href="add-diary.php?year='.$prev_year.'&month='.$month.'">&lt;&lt;</a></td><td align="center" valign="top" colspan="5"><strong><a href="show-diary-year.php?year='.$year.'">'.$year.'</a></strong></td><td align="center" valign="top"><a href="add-diary.php?year='.$next_year.'&month='.$month.'">&gt;&gt;</a></td></tr><tr><td align="center" valign="top"><a href="add-diary.php?month='.$prev_month.'&year='.$prev_year_month.'">&lt;&lt;</a></td><td align="center" valign="top" colspan="5"><strong>'.$month_name.'</strong></td><td align="center" valign="top"><a href="add-diary.php?month='.$next_month.'&year='.$next_year_month.'">&gt;&gt;</a></td></tr>';

	} else { $text.='<tr><td align="center" valign="top" colspan="7"><strong>'.$month_name.'</strong></td></tr>'; }

	$text.='<tr><td align="center" valign="top">'.$this->day_names[($this->start_day)%7].'</td><td align="center" valign="top">'.$this->day_names[($this->start_day+1)%7].'</td><td align="center" valign="top">'.$this->day_names[($this->start_day+2)%7].'</td><td align="center" valign="top">'.$this->day_names[($this->start_day+3)%7].'</td><td align="center" valign="top">'.$this->day_names[($this->start_day+4)%7].'</td><td align="center" valign="top">'.$this->day_names[($this->start_day+5)%7].'</td><td align="center" valign="top">'.$this->day_names[($this->start_day+6)%7].'</td></tr>';

	$d=$this->start_day+1-$first;
	while ($d > 1) { $d-=7; }

	$today=getdate(time());

	while ($d <= $days_in_month) { $text.='<tr>';

	for ($i=0; $i < 7; $i++) {

	if ($year==$today['year'] && $month==$today['mon'] && $d==$today['mday']) { $bg='bgcolor="#dfdfdf"'; } else { $bg=''; }

	$text.='<td align="right" valign="top" '.$bg.'>';

	if ($d > 0 && $d <= $days_in_month) {

	if (in_array($d,$array_bold)) { $bold='<b>'; } else { $bold=''; }

	$text.='<a href="add-diary.php?day='.$d.'&month='.$month.'&year='.$year.'">'.$bold.' '.$d.' </font></b></a>'; }

	else { $text.='&nbsp;'; }

	$text.='</td>';
	$d++;

	}

	$text.='</tr>';

	}

	$text.='</table>';

	return $text;

}

function get_month_view2($m, $y, $nav=1) {

	global $base_instance;

	$getdate=getdate(time());

	if (!$m) { $m=date('m'); } else if (strlen($m)==1) { $m='0'.$m; }
	if (!$y) { $y=$getdate['year']; }

	$userid=$base_instance->user;

	unset($text);

	$a=$this->adjust_date($m, $y);
	$month=$a[0];
	$year=$a[1];

	$days_in_month=$this->get_days_in_month($month,$year);
	$date=getdate(mktime(12,0,0,$month,1,$year));

	$first=$date['wday'];
	$month_name=$base_instance->month_names[$month];

	$prev=$this->adjust_date($month-1, $year);
	$next=$this->adjust_date($month+1, $year);

	$prev=$this->adjust_date($month, $year-1);
	$next=$this->adjust_date($month, $year+1);

	$prev_year=$year-1;	$next_year=$year+1; $prev_month=$month-1;

	if ($prev_month==0) { $prev_month=12; $prev_year_month=$prev_year; } else { $prev_year_month=$year; }

	$next_month=$month+1;

	if ($next_month==13) { $next_month=1; $next_year_month=$next_year; } else { $next_year_month=$year; }

	$text='<table bgcolor="#fbfbfb" border=1 cellspacing="0" width="90%">';

	if ($nav==1) {

	$text.='<tr><td align="center" valign="top"><a href="show-reminder-monthly-overview.php?year='.$prev_year.'&month='.$month.'">&lt;&lt;</a></td><td align="center" valign="top" colspan="5"><strong><font size="5" color="'._BLOCK_LINE_COLOR.'">'.$year.'</font></strong></td><td align="center" valign="top"><a href="show-reminder-monthly-overview.php?year='.$next_year.'&month='.$month.'">&gt;&gt;</a></td></tr><tr><td align="center" valign="top"><a href="show-reminder-monthly-overview.php?month='.$prev_month.'&year='.$prev_year_month.'">&lt;&lt;</a></td><td align="center" valign="top" colspan="5"><strong><font size="5" color="'._BLOCK_LINE_COLOR.'">'.$month_name.'</font></strong></td><td align="center" valign="top"><a href="show-reminder-monthly-overview.php?month='.$next_month.'&year='.$next_year_month.'">&gt;&gt;</a></td></tr>';

	}

	else {

	$text.='<tr><td align="center" valign="top" colspan="7"><strong><font size="5">'.$month_name.'</font></strong></td></tr>';

	}

	#$text.='<tr><td align="center" valign="top"><strong>'.$this->day_names2[($this->start_day)%7].'</strong></td><td align="center" valign="top"><strong>'.$this->day_names2[($this->start_day+1)%7].'</strong></td><td align="center" valign="top"><strong>'.$this->day_names2[($this->start_day+2)%7].'</strong></td><td align="center" valign="top"><strong>'.$this->day_names2[($this->start_day+3)%7].'</strong></td><td align="center" valign="top"><strong>'.$this->day_names2[($this->start_day+4)%7].'</strong></td><td align="center" valign="top"><strong>'.$this->day_names2[($this->start_day+5)%7].'</strong></td><td align="center" valign="top"><strong>'.$this->day_names2[($this->start_day+6)%7].'</strong></td></tr>';

	$d=$this->start_day+1-$first;

	while ($d > 1) { $d-=7; }

	$today=getdate(time());

	while ($d <= $days_in_month) { $text.='<tr>';

	for ($i=0; $i < 7; $i++) {

	if ($year==$today['year'] && $month==$today['mon'] && $d==$today['mday']) { $u1='<u>'; $u2='</u>'; } else { $u1=''; $u2=''; }

	$text.='<td valign="top" height="50">';

	if ($d > 0 && $d <= $days_in_month) {

	$all_reminder=$this->get_all_reminder($i+1,$d,$m,$y);

	$dotw=$this->day_names2[$i%7];

	$text.='&nbsp;<a name='.$d.'><font size="4">'.$u1.$d.$u2.'</font>&nbsp;&nbsp;<strong>'.$dotw.'</strong></a>'.$all_reminder; }

	else { $text.='&nbsp;'; }

	$text.='</td>';
	$d++;

	}

	$text.='</tr>';

	}

	$text.='</table>';

	return $text;

}

function get_all_reminder($day_of_the_week,$day,$month,$year) {

	global $base_instance;

	$userid=$base_instance->user;

	$data2=$base_instance->get_data("SELECT ID,title,what_time FROM {$base_instance->entity['REMINDER']['WEEKDAY']} WHERE day_of_the_week LIKE '%$day_of_the_week%' AND user=$userid");

	$dotw_total=sizeof($data2); $all='<table>';

	for ($index=1; $index <= $dotw_total; $index++) {

	$dotw_id=$data2[$index]->ID;
	$dotw_title=substr($data2[$index]->title,0,20);
	$dotw_time=$data2[$index]->what_time;

	$all.='<tr bgcolor="#ffffff"><td><a href="javascript:void(window.open(\'edit-reminder-weekday.php?reminder_id='.$dotw_id.'\',\'\',\'width=600,height=500,top=100,left=100\'))"><font size="1">-'.$dotw_title.' '.$dotw_time.'</font></a></td></tr>';

	}

	#

	$data=$base_instance->get_data("SELECT ID,title,what_time FROM {$base_instance->entity['REMINDER']['DATE']} WHERE user=$userid AND day=$day AND (month=$month OR month=0) AND (year=$year OR year=0)");

	for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;
	$what_time=$data[$index]->what_time;

	$all.='<tr bgcolor="#ffffff"><td><a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$ID.'\',\'\',\'width=600,height=620,top=100,left=100\'))"><font size="1">- '.$title.' '.$what_time.'</font></a></td></tr>';

	}

	#

	$all.='</table>';

	return $all;

}

function get_year_view($year) {

	$prev_year=$year-1;	$next_year=$year+1;

	$text='<table border="0"><tr><td align="center" valign="top" align="left" class="pages"><a href="show-diary-year.php?year='.$prev_year.'">&laquo; Previous</a></td><td valign="top" align="center"><h1>'.$year.'</h1></td><td align="center" valign="top" align="right" class="pages"><a href="show-diary-year.php?year='.$next_year.'">Next &raquo;</a></td></tr><tr><td valign="top">'.$this->get_month_view(1,$year,0).'</td><td valign="top">'.$this->get_month_view(2,$year,0).'</td><td valign="top">'.$this->get_month_view(3,$year,0).'</td></tr><tr><td valign="top">'.$this->get_month_view(4,$year,0).'</td><td valign="top">'.$this->get_month_view(5,$year,0).'</td><td valign="top">'.$this->get_month_view(6,$year,0).'</td></tr><tr><td valign="top">'.$this->get_month_view(7,$year,0).'</td><td valign="top">'.$this->get_month_view(8,$year,0).'</td><td valign="top">'.$this->get_month_view(9,$year,0).'</td></tr><tr><td valign="top">'.$this->get_month_view(10,$year,0).'</td><td valign="top">'.$this->get_month_view(11,$year,0).'</td><td valign="top">'.$this->get_month_view(12,$year,0).'</td></tr></table>';

	return $text;

}

function adjust_date($month, $year) {

	$a=array();	$a[0]=$month; $a[1]=$year;

	while ($a[0] > 12) { $a[0]-=12; $a[1]++; }
	while ($a[0] <= 0) { $a[0]+=12; $a[1]--; }

	return $a;

}

}

?>