<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'LINK',
'ORDER_COL'=>'datetime',
'MAXHITS'=>50,
'WHERE'=>'WHERE public=2',
'HEADER'=>'Public Links',
'SORTBAR'=>1,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'INNER_TABLE_WIDTH'=>'90%'
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No public links added yet'); }

else {

$all_text='<table width="100%" border=1 cellspacing=0 cellpadding=2 class="pastel">';

$datetime=date('Y-m-d');

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;
	$url=$data[$index]->url;
	$datetime=$data[$index]->datetime;
	$user=$data[$index]->user;

	$datetime_converted=$base_instance->convert_date($datetime);

	$url_encoded=urlencode($url);

	$username=$base_instance->get_username($user);

	if ($title) { $anchor=$title; } else { $anchor='http://'.substr($url,0,35); }

	$url2=base64_encode($url);

	$all_text.='

	<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>

<td>

<strong>'.$anchor.'</strong><br><a href="load-url.php?url_encoded='.$url2.'" target="_blank">http://'.$url.'</a>

</td>

<td width="25%" valign="top">

<font size="1">ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'<p>

User: <a href="show-user.php?username='.$username.'"><font size="1">'.$username.'</font></a> &nbsp;&nbsp;

<a href="add-link.php?url=http://'.$url_encoded.'"><font size="1">[Add Link]</font></a>

</font>

</td>

</tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>