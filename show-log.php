<?php

require 'class.base.php';
require 'class.user.php';
require 'class.html.php';

$base_instance=new base();
$user_instance=new user();
$html_instance=new html();

$user_instance->check_for_admin();

$query=''; $param='';

if (isset($_GET['IP'])) {

$param='IP='.$_GET['IP'];
$IP=base64_decode($_GET['IP']);
$query='WHERE IP="'.$IP.'"';

}

if (isset($_GET['user'])) {

$param='user='.$_GET['user'];
$query='WHERE user="'.$_GET['user'].'"';

}

if (isset($_GET['browser'])) {

$param='browser='.$_GET['browser'];
$browser=base64_decode($_GET['browser']);
$query='WHERE user_agent="'.$browser.'"';

}

if (isset($_GET['delete_log'])) {

$last_id=$_GET['delete_log']+1;

$base_instance->query('DELETE FROM '.$base_instance->entity['LOG']['MAIN'].' WHERE ID < '.$last_id);

}

if (isset($_GET['referrer'])) {

$param='referrer='.$_GET['referrer'];
$referrer=base64_decode($_GET['referrer']);
$query='WHERE referrer="'.$referrer.'"';

}

if (isset($_GET['request_uri'])) {

$param='request_uri='.$_GET['request_uri'];
$request_uri=base64_decode($_GET['request_uri']);
$query='WHERE request_uri="'.$request_uri.'"';

}

if (isset($_GET['user_agent'])) {

$param='user_agent='.$_GET['user_agent'];
$user_agent=base64_decode($_GET['user_agent']);
$query='WHERE user_agent="'.$user_agent.'"';

}

$data=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['LOG']['MAIN'].' ORDER BY ID DESC LIMIT 1');

if ($data) { $last_id=$data[1]->ID; } else { $last_id=0; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'LOG',
'WHERE'=>"$query",
'MAXHITS'=>200,
'ORDER_COL'=>'ID',
'ORDER_TYPE'=>'DESC',
'INNER_TABLE_WIDTH'=>'90%',
'HEADER'=>'Activity Log',
'TEXT_CENTER'=>'<a href="show-log.php?delete_log='.$last_id.'"><font color="#FF0000">[Delete Activity Log]</font></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="show-log-summary.php">[Show Summary]</a><p>',
'URL_PARAMETER'=>$param
));

$data=$html_instance->get_items();

$today_date=date('Y-m-d');

$text='<table width="100%">';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$user_id=$data[$index]->user;
	$datetime=$data[$index]->datetime;
	$IP=$data[$index]->IP;
	$globals_request=$data[$index]->globals_request;
	$request_uri=$data[$index]->request_uri;
	$user_agent=$data[$index]->user_agent;
	$referrer=$data[$index]->referrer;

	$req_short=substr($request_uri,0,125);
	$ref_short=substr($referrer,0,125);
	$referrer2=base64_encode($referrer);
	$req_encoded2=base64_encode(_HOMEPAGE.'/'.$request_uri);

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$user_id'");
	if ($data2) { $username=$data2[1]->username; } else { $username=''; }

	if (substr($datetime,0,10)==$today_date) { $color='#f0f0f0'; } else { $color='#ffffff'; }

	$globals_request=htmlentities($globals_request);
	$user_agent_encoded=base64_encode($user_agent);
	$user_agent=htmlentities($user_agent);

	$text.='<tr bgcolor='.$color.'><td><b>User:</b> <a href="show-user.php?username='.$username.'">'.$username.'</a><br><b>URL:</b> <a href="load-url.php?url_encoded='.$req_encoded2.'" target="_blank">'.$req_short.'</a><br><b>Browser:</b> <a href="show-log.php?browser='.$user_agent_encoded.'">'.$user_agent.'</a><br>
<b>Referrer:</b> <a href="load-url.php?url_encoded='.$referrer2.'" target="_blank">'.$ref_short.'</a><br>
<b>Date:</b> '.$datetime.' <b>Page Load:</b> '.$ID.'<br><b>Vars:</b> '.$globals_request.'<b><br>IP:</b> <a href="show-log.php?IP='.base64_encode($IP).'">'.$IP.'</a></td></tr>';

}

$text.='</table>';

$content_array[1]=array('MAIN'=>$text);

$html_instance->content=$content_array;

$html_instance->process();

?>