<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$datetime=date('Y-m-d H:i:s');

if (empty($_GET['order_type'])) { $order_type='DESC'; } else { $order_type=sql_safe($_GET['order_type']); }
if (empty($_GET['order_col'])) { $order_col='ID'; } else { $order_col=sql_safe($_GET['order_col']); }

if (empty($_GET['show_all'])) {

$where="WHERE DATE_ADD(last_reminded, INTERVAL frequency DAY)<'$datetime' AND user='$userid'";
$header='Reminders To Do &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?show_all=1">[Show all Reminders]</a>';
$show_all=0;

}

else {

$where="WHERE user='$userid'";
$header='All Reminders &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?show_all=0">[Show Reminders To Do]</a>';
$show_all=1;

}

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'REMINDER',
'SUBENTITY'=>'DAYS',
'MAXHITS'=>50,
'WHERE'=>"$where AND homepage=1",
'ORDER_COL'=>"$order_col",
'ORDER_TYPE'=>"$order_type",
'HEADER'=>"$header",
'SORTBAR'=>6,
'SORTBAR_FIELD1'=>'title','SORTBAR_NAME1'=>'Title',
'SORTBAR_FIELD2'=>'bluebox','SORTBAR_NAME2'=>'Days due',
'SORTBAR_FIELD3'=>'last_reminded','SORTBAR_NAME3'=>'Last Time Done',
'SORTBAR_FIELD4'=>'done','SORTBAR_NAME4'=>'Done',
'SORTBAR_FIELD5'=>'frequency','SORTBAR_NAME5'=>'Do every',
'SORTBAR_FIELD6'=>'datetime','SORTBAR_NAME6'=>'Date added',
'INNER_TABLE_WIDTH'=>'95%',
'URL_PARAMETER'=>"show_all=$show_all"
));

if ($order_col=='bluebox') { $html_instance->para['ORDER_COL']='(UNIX_TIMESTAMP("'.$datetime.'")-UNIX_TIMESTAMP(last_reminded)-(frequency*86400))'; } # translate bluebox ORDER_COL

$data=$html_instance->get_items();

if ($order_col=='bluebox') { $html_instance->para['ORDER_COL']='bluebox'; } # translate back (workaround to show red arrow down and up)

$all_text='<table width="100%" border=1 cellspacing=0 cellpadding=2 class="pastel"><tr><td align="center"><a href="'.$_SERVER['PHP_SELF'].'?order_col='.$order_col.'&order_type='.$order_type.'&show_all='.$show_all.'">[Refresh]</a></td>';

if ($order_col=='bluebox') { $all_text.='<td align="center"><u><b>Days due</b></u></td>'; }
else { $all_text.='<td align="center"><b>Days due</b></td>'; }

if ($order_col=='last_reminded') { $all_text.='<td align="center"><u><b>Last Time Done</b></u></td>'; }
else { $all_text.='<td align="center"><b>Last Time Done</b></td>'; }

if ($order_col=='done') { $all_text.='<td align="center"><u><b>Done</b></u></td>'; }
else { $all_text.='<td align="center"><b>Done</b></td>'; }

if ($order_col=='frequency') { $all_text.='<td align="center"><u><b>Do every</b></u></td>'; }
else { $all_text.='<td align="center"><b>Do every</b></td>'; }

$all_text.='<td colspan="3" align="center">&nbsp;</td></tr>';

$timestamp=time();

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$done=$data[$index]->done;
$frequency=$data[$index]->frequency;
$last_reminded=$data[$index]->last_reminded;

preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$last_reminded,$dd);
$last_reminded_converted="$dd[3].$dd[2].$dd[1]";

preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$last_reminded,$dd);
$temp=mktime(0,0,0,$dd[2],$dd[3]+$frequency,$dd[1]);
$days_rounded=round(($timestamp-$temp)/86400);

$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'count-reminder.php?reminder_id='.$ID.'\',\'status\')"><span class="fakelink">'.$title.'</span></td><td align="center">'.$days_rounded.'</td><td align=center>'.$last_reminded_converted.'</td><td align=center>'.$done.'</td><td align=center>'.$frequency.' days</td><td align="center"><a href="count-reminder.php?reminder_id='.$ID.'" target="status">[Done]</a></td><td align="center"><a href="javascript:void(window.open(\'edit-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=600,height=500,top=100,left=100\'))">[Edit]</a></td><td align="center"><a href="javascript:void(window.open(\'delete-reminder-days.php?reminder_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>