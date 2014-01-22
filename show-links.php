<?php

require 'class.base.php';
require 'class.html.php';
require 'class.misc.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$datetime=date('Y-m-d H:i:s');

$base_instance->query("SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'"); // necessary for the overflow problem

$sort=''; $query=''; $param=''; $cat_header=''; $minus='';

if (empty($_GET['order_type'])) { $order_type='DESC'; }
else { $order_type=sql_safe($_GET['order_type']); $sort.='order_type='.$order_type.'&amp;'; }

if (empty($_GET['order_col'])) { $order_col='ttv'; }
else { $order_col=sql_safe($_GET['order_col']); $sort.='order_col='.$order_col.'&amp;'; }

if (isset($_REQUEST['text_search'])) {

$text_search=sql_safe($_REQUEST['text_search']);

$query.=" AND (subtitle LIKE '%$text_search%' OR url LIKE '%$text_search%' OR title LIKE '%$text_search%' OR notes LIKE '%$text_search%' OR keywords LIKE '%$text_search%') ";

$param.='text_search='.$text_search.'&amp;';

} else { $text_search=''; }

if (isset($_REQUEST['category_id'])) {

$category_id=(int)$_REQUEST['category_id'];

$cat_name=$misc_instance->get_link_category($category_id);
$cat_header=' &nbsp;&nbsp; (Category '.$cat_name.')';
$query.=" AND (category=$category_id) ";
$param.='category_id='.$category_id.'&amp;';

}

if (isset($_GET['bluebox'])) {

$where="WHERE ((DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime' AND frequency_mode=3) OR frequency_mode=1) AND user='$userid' $query";
$header='Bluebox (Links Due) &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?'.$sort.$param.'show_all=1">[Show all Links]</a>';
$param.='bluebox=1';

}

else {

$where="WHERE user='$userid' $query";
$header='All Links &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?'.$sort.$param.'bluebox=1">[Show Bluebox]</a>';
$param.='show_all=1';

}

#

if (isset($_GET['order_col'])) {

$order_col=$_GET['order_col'];
setcookie('oc_link',$_GET['order_col'],time()+2592000);

} else { $order_col=isset($_COOKIE['oc_link']) ? $_COOKIE['oc_link'] : 'datetime'; }

#

if (isset($_GET['order_type'])) {

$order_type=$_GET['order_type'];
setcookie('ot_link',$_GET['order_type'],time()+2592000);

} else { $order_type=isset($_COOKIE['ot_link']) ? $_COOKIE['ot_link'] : 'DESC'; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'LINK',
'ORDER_COL'=>$order_col,
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>100,
'WHERE'=>"$where",
'HEADER'=>"$header $cat_header",
'SORTBAR'=>10,
'SORTBAR_FIELD1'=>'title','SORTBAR_NAME1'=>'Title',
'SORTBAR_FIELD2'=>'bluebox','SORTBAR_NAME2'=>'Due for',
'SORTBAR_FIELD3'=>'last_visit','SORTBAR_NAME3'=>'Last Visit',
'SORTBAR_FIELD4'=>'ttv','SORTBAR_NAME4'=>'Speed Ranking',
'SORTBAR_FIELD5'=>'speed','SORTBAR_NAME5'=>'Speed',
'SORTBAR_FIELD6'=>'sequence','SORTBAR_NAME6'=>'Sequence',
'SORTBAR_FIELD7'=>'popularity','SORTBAR_NAME7'=>'Popularity',
'SORTBAR_FIELD8'=>'visits','SORTBAR_NAME8'=>'Visits',
'SORTBAR_FIELD9'=>'frequency','SORTBAR_NAME9'=>'Visit every',
'SORTBAR_FIELD10'=>'datetime','SORTBAR_NAME10'=>'Date added',
'INNER_TABLE_WIDTH'=>'96%',
'URL_PARAMETER'=>$param
));

if ($order_col=='ttv') { $html_instance->para['ORDER_COL']='(UNIX_TIMESTAMP("'.$datetime.'")-UNIX_TIMESTAMP(last_visit)-frequency)*(speed/100000)'; } # translate ttv ORDER_COL

if ($order_col=='bluebox') { $html_instance->para['ORDER_COL']='(UNIX_TIMESTAMP("'.$datetime.'")-UNIX_TIMESTAMP(last_visit)-frequency)'; } # translate bluebox ORDER_COL

$data=$html_instance->get_items();

if ($order_col=='ttv') { $html_instance->para['ORDER_COL']='ttv'; } # translate back (workaround to show red arrow down and up)

if ($order_col=='bluebox') { $html_instance->para['ORDER_COL']='bluebox'; } # translate back (workaround to show red arrow down and up)

if (!$data) {

if ($text_search) {

$html_instance->add_parameter(
array(
'HEADER'=>'Nothing found (Links)',
'TEXT'=>'<form action="show-links.php" method="post"><center><table cellpadding=10 cellspacing=0 border=0 bgcolor="#ffffff" class="pastel2"><tr><td align="right"><b>Text:</b> &nbsp;<input type="text" name="text_search" size="30" value="'.$text_search.'"></td></tr><tr><td align="center"><input type="SUBMIT" value="Search Links" name="save"></td></tr></table></center></form>'
));

$html_instance->process();

}

else { $base_instance->show_message('No links added yet','<a href="add-link.php">[Add Link]</a> or <a href="import-bookmarks-start.php">[Upload your Bookmarks]</a>'); }

}

else {

if (isset($_GET['page'])) { $page=(int)$_GET['page']; } else { $page=1; }

$all_text='<table width="100%" border=0 cellspacing=0 cellpadding=2 class="pastel"><tr><td align="center"><a href="'.$_SERVER['PHP_SELF'].'?'.$param.'&amp;page='.$page.'&amp;order_col='.$order_col.'&amp;order_type='.$order_type.'">[Refresh]</a></td><td align="center"><table class="no_border"><tr><td><strong>Search:</strong>&nbsp;</td><td><form method="post" action="url-search.php" target="_blank"><input type="Text" name="text_search" size=10><input type=submit value="Go!"></form></td></tr></table></td><td colspan="6" align="center"><b>Actions</b></td>';

if ($order_col=='frequency') { $all_text.='<td align="center"><u><b>Visit every</b></u></td>'; }
else { $all_text.='<td align="center"><b>Visit every</b></td>'; }

if ($order_col=='speed') { $all_text.='<td align="center"><u><b>Ascent Speed</b></u></td>'; }
else { $all_text.='<td align="center"><b>Ascent Speed</b></td>'; }

if ($order_col=='last_visit' or $order_col=='bluebox') { $all_text.='<td align="center"><u><b>Last Visit [Due for]</b></u></td>'; }
else { $all_text.='<td align="center"><b>Last Visit (Due for)</b></td>'; }

if ($order_col=='sequence') { $all_text.='<td align="center"><u><b>Sequence</b></u></td>'; }
else { $all_text.='<td align="center"><b>Sequence</b></td>'; }

if ($order_col=='popularity') { $all_text.='<td align="center"><u><b>Popularity</b></u></td>'; }
else { $all_text.='<td align="center"><b>Popularity</b></td>'; }

if ($order_col=='visits') { $all_text.='<td align="center"><u><b>Visits</b></u></td>'; }
else { $all_text.='<td align="center"><b>Visits</b></td>'; }

$all_text.='</tr>';

$datetime2=date('Y-m-d H:i:s',mktime(date('H')-24,date('i'),date('s'),date('m'),date('d'),date('Y')));

$now=time();

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$subtitle=$data[$index]->subtitle;
$url=$data[$index]->url;
$last_visit=$data[$index]->last_visit;
$date_added=$data[$index]->datetime;
$frequency=$data[$index]->frequency;
$visits=$data[$index]->visits;
$speed=$data[$index]->speed;
$title=$data[$index]->title;
$notes=$data[$index]->notes;
$popularity=$data[$index]->popularity;
$sequence=$data[$index]->sequence;
$public=$data[$index]->public;

if ($public==1) { $public='&nbsp;'; } else { $public='<a href="javascript:void(window.open(\'edit-link.php?link_id='.$ID.'\',\'\',\'width=550,height=650,top=100,left=100\'))">P</a>'; }

# calculate last visit

$last_active_converted=strtotime($last_visit);
$seconds=$now-$last_active_converted;

if ($seconds < 60) { $lastvisit=$seconds.' secs'; }
else if ($seconds < 3600) { $lastvisit=round($seconds/60).' mins'; }
else if ($seconds < 86400) { $lastvisit=round($seconds/3600).' hours'; }
else { $lastvisit=round($seconds/86400).' days'; }

# calculate "Speed Ranking" value

if ($frequency > 0) { $ttv=round(($seconds-$frequency)*$speed); }
else { $ttv='-'; }

# calculate time in Bluebox

if ($frequency==0) { $time_in_BB='-'; }
else {

$seconds_since_last_active=$now-$last_active_converted;
$seconds_in_BB=$seconds_since_last_active-$frequency;

if ($seconds_in_BB < 0) { $minus=1; $seconds_in_BB=abs($seconds_in_BB); }

if ($seconds_in_BB < 60) { $time_in_BB=$seconds_in_BB.' secs'; }
else if ($seconds_in_BB < 3600) { $time_in_BB=round($seconds_in_BB/60).' mins'; }
else if ($seconds_in_BB < 86400) { $time_in_BB=round($seconds_in_BB/3600).' hours'; }
else { $time_in_BB=round($seconds_in_BB/86400).' days'; }

if ($minus==1) { $time_in_BB='-'.$time_in_BB; }

$minus='';

}

# calculate frequency format "days:hours:minutes"

$number_of_days=floor($frequency / 86400);
$days_in_second=$number_of_days * 86400;

$frequency-=$days_in_second;

$number_of_hours=floor($frequency / 3600);
$hours_in_second=$number_of_hours * 3600;

$frequency-=$hours_in_second;

$number_of_mins=floor($frequency / 60);

# determine visit_every var

if ($number_of_days==0 && $number_of_hours==0 && $number_of_mins==0) {

$visit_every='-';

}

else if ($number_of_days>0 && $number_of_hours==0 && $number_of_mins==0) {

$visit_every=$number_of_days.' days';

}

else if ($number_of_days==0 && $number_of_hours>0 && $number_of_mins==0) {

$visit_every=$number_of_hours.' hours';

}

else if ($number_of_days==0 && $number_of_hours==0 && $number_of_mins>0) {

$visit_every=$number_of_mins.' mins';

}

else { $visit_every=$number_of_days.':'.$number_of_hours.':'.$number_of_mins; }

#

if ($title) { $url_short=$title; }
else { $url_short='http://'.substr($url,0,30); }

if ($notes) { $notes_reminder='<a href="javascript:void(window.open(\'edit-link.php?link_id='.$ID.'\',\'\',\'width=550,height=650,top=100,left=100\'))">N</a>'; } else { $notes_reminder='&nbsp;'; }

if ($subtitle) {

$subtitle=convert_square_bracket($subtitle);

$subtitle2='<br><font size="1">'.$subtitle.'</font>';

}

else { $subtitle2=''; }

$speed=$base_instance->speed_array[$speed];

$all_text.='<tr bgcolor="#ffffff" onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'visit-link.php?link_id='.$ID.'\',\'_blank\'); this.style.fontWeight=\'bold\';" colspan=2><span class="fakelink">'.$url_short.'</span>'.$subtitle2.'</td><td>'.$notes_reminder.'</td><td><a href="visit-link.php?link_id='.$ID.'" target="_blank">V</a></td><td><a href="javascript:void(window.open(\'edit-link.php?link_id='.$ID.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td><a href="send-content.php?link_id='.$ID.'">S</a></td><td><a href="javascript:void(window.open(\'delete-link.php?link_id='.$ID.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td><td>'.$public.'</td><td align="center">'.$visit_every.'</td><td align="center">'.$speed.'</td><td align="center">'.$lastvisit.' ('.$time_in_BB.')</td><td align="center">'.$sequence.'</td><td align="center">'.round($popularity).' </td><td align="center">'.$visits.'</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>