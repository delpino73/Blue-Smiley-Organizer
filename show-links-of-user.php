<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$user=isset($_GET['user']) ? (int)$_GET['user'] : exit;

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'LINK',
'ORDER_COL'=>'popularity',
'MAXHITS'=>50,
'WHERE'=>"WHERE user=$user AND public=2",
'HEADER'=>'Links',
'SORTBAR'=>4,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'SORTBAR_FIELD2'=>'last_visit','SORTBAR_NAME2'=>'Last Visit',
'SORTBAR_FIELD3'=>'visits','SORTBAR_NAME3'=>'Visits',
'SORTBAR_FIELD4'=>'popularity','SORTBAR_NAME4'=>'Popularity',
'INNER_TABLE_WIDTH'=>'90%',
'URL_PARAMETER'=>"user=$user"
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('User has no public links'); }

else {

$all_text='<table width="100%"><tr><td width=200><b>Link</b></td><td align="center"><b>Date Added</td><td align="center"><b>Last Visit</td><td align="center"><b>Visits</td><td align="center"><b>Popularity</td></tr>';

$datetime2=date('Y-m-d H:i:s',mktime(date('H')-24,date('i'),date('s'),date('m'),date('d'),date('Y')));

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$url=$data[$index]->url;
	$last_visit=$data[$index]->last_visit;
	$date_added=$data[$index]->datetime;

	preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$last_visit,$ll);
	$last_visit="$ll[3].$ll[2].$ll[1]";

	preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$date_added,$ll);
	$date_added="$ll[3].$ll[2].$ll[1]";

	$url_short='http://'.substr($url,0,30);

	$visits=$data[$index]->visits;
	$popularity=$data[$index]->popularity;
	$title=$data[$index]->title;

	if ($title) { $url_short=$title; }

	$url2=base64_encode($url);

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="add-link.php?url=http://'.$url.'"><b>Add Link</b></a> <a href="load-url.php?url_encoded='.$url2.'" target="_blank" class="link">'.$url_short.'</a></td><td align="center">&nbsp; '.$date_added.' &nbsp;</td><td align="center">&nbsp; '.$last_visit.' &nbsp;</td><td align=center><b>'.$visits.'</b></td><td align=center><b>'.$popularity.'</b></td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>