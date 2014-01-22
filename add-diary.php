<?php

require 'class.base.php';
require 'class.html.php';
require 'class.diary.php';

$base_instance=new base();
$html_instance=new html();
$diary_instance=new diary();

$userid=$base_instance->get_userid();

if (isset($_REQUEST['day'])) { $day=(int)$_REQUEST['day']; }
if (isset($_REQUEST['month'])) { $month=(int)$_REQUEST['month']; }
if (isset($_REQUEST['year'])) { $year=(int)$_REQUEST['year']; }
if (isset($_REQUEST['diary_id'])) { $diary_id=(int)$_REQUEST['diary_id']; }

$all_text='';

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$diary_text=$_POST['diary_text'];

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$diary_text) { $error.='<li> Text cannot be left blank'; }
	else {

	$diary_text=trim($diary_text);
	if (strlen($diary_text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

	$date=$year.'-'.$month.'-'.$day;

	$data=$base_instance->get_data("SELECT ID FROM {$base_instance->entity['DIARY']['MAIN']} WHERE date='$date' AND user='$userid'");

	if (isset($data)) {

	$diary_id=$data[1]->ID;

	$base_instance->query('UPDATE '.$base_instance->entity['DIARY']['MAIN'].' SET text="'.sql_safe($diary_text).'",title="'.sql_safe($title).'" WHERE user='.$userid.' AND ID='.$diary_id);

	}

	else {

	$today=date('Y-m-d'); $base_instance->query('INSERT INTO '.$base_instance->entity['DIARY']['MAIN'].' (date,text,title,user,last_shown) VALUES ("'.sql_safe($date).'","'.sql_safe($diary_text).'","'.sql_safe($title).'",'.$userid.',"'.$today.'")');

	$diary_id=mysql_insert_id(); }

	$base_instance->show_message('Diary saved','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelDiary(item){if(confirm("Delete Diary?")){http.open(\'get\',\'delete-diary.php?item=\'+item); http.send(null);}}</script>

<a href="add-diary.php?day='.$day.'&month='.$month.'&year='.$year.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelDiary(\''.$diary_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?diary_id='.$diary_id.'">[Send]</a> &nbsp;&nbsp; <a href="show-diary.php">[Show all]</a><p>');

	}

	else {

	$all_text='<blockquote><font color="#ff0000"><ul>'.$error.'</ul></font></blockquote>';

	$diary_text=stripslashes($diary_text);
	$title=stripslashes($title);

	}

}

elseif (isset($diary_id)) {

$data=$base_instance->get_data("SELECT ID,date,text,title FROM {$base_instance->entity['DIARY']['MAIN']} WHERE ID='$diary_id' AND user='$userid'");

if (!$data) { $base_instance->show_message('Diary entry not found','',1); }

$ID=$data[1]->ID;
$date=$data[1]->date;
$diary_text=$data[1]->text;
$title=$data[1]->title;

preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$date,$ll);
$year=$ll[1]; $month=$ll[2]; $day=$ll[3];

}

else {

$d=getdate(time());

if (empty($day)) { $day=date('d'); } else if (strlen($day)==1) { $day='0'.$day; }
if (empty($month)) { $month=date('m'); } else if (strlen($month)==1) { $month='0'.$month; }
if (empty($year)) { $year=$d['year']; }

$data=$base_instance->get_data("SELECT ID,text,title FROM {$base_instance->entity['DIARY']['MAIN']} WHERE date='$year-$month-$day' AND user='$userid'");

if ($data) {

$ID=$data[1]->ID;
$diary_text=$data[1]->text;
$title=$data[1]->title;

} else { $ID=''; $diary_text=''; $title=''; }

}

if (empty($ID)) { $ID_text=''; } else { $ID_text='<strong>ID:</strong> '.$ID; }

$current_month=$diary_instance->get_month_view($month, $year);

$weekday=date('l',mktime(0,0,0,$month,$day,$year));

$date_converted=$base_instance->convert_date($year.'-'.$month.'-'.$day.' 00:00:00');

$all_text.='<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="form1">
<input type="Hidden" name="day" value="'.$day.'">
<input type="Hidden" name="month" value="'.$month.'">
<input type="Hidden" name="year" value="'.$year.'">

<div align="center"><br>

<table><tr><td><table cellpadding=4 cellspacing=0 border=0 bgcolor="#ffffff" width=40% class="pastel2"><tr><td align="right"><b>Date:</b></td><td>'.$date_converted.' - '.$weekday.' &nbsp;&nbsp; '.$ID_text.'</td></tr><tr><td align="right"><b>Title:</b></td><td align="left">&nbsp;
<input type="text" name="title" size="65" value="'.$title.'"></td></tr><tr><td colspan=2 align=center><textarea rows=15 cols=100 name="diary_text" wrap>'.$diary_text.'</textarea></td></tr><tr><td colspan=2 align=center><input type="SUBMIT" value="Save Diary" name="save"></td></tr></form></td></tr></table>
</td><td valign="top">'.$current_month.'</td></tr></table>

</div>';

$html_instance->add_parameter(array('TEXT_CENTER'=>"$all_text",'BODY'=>'onLoad="javascript:document.form1.title.focus()"'));

$html_instance->process();

?>