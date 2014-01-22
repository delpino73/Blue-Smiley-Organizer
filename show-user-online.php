<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$user_instance=new user();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'SESSION',
'SUBENTITY'=>'MAIN',
'ORDER_COL'=>'last_active',
'MAXHITS'=>100,
'WHERE'=>"WHERE online_status=1",
'HEADER'=>'User Online',
'TEXT'=>'<div align="center"><a href="'.$_SERVER['PHP_SELF'].'">[Refresh List]</a></div><br>',
'INNER_TABLE_WIDTH'=>'90%'
));

$data=$html_instance->get_items();

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><strong>Username</strong></td><td><strong>Logins</strong></td><td><b>Time active ago</b></td><td><b>Online for</b></td><td colspan="2">&nbsp;</td></tr>';

$now=time();

for ($index=1; $index <= sizeof($data); $index++) {

	$user=$data[$index]->user;

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$user");

	$ID=$data2[1]->ID;
	$username=$data2[1]->username;
	$logins=$data2[1]->logins;

	$last_active=$data[$index]->last_active;
	$create_time=$data[$index]->create_time;

	$online_for=floor(($now-$create_time)/60);

	$active_seconds_ago=$now-$last_active;
	$active_minutes_ago=floor(($now-$last_active)/60);

	if ($active_minutes_ago==0) { $time_active_ago=$active_seconds_ago.' Sec'; } else { $time_active_ago=$active_minutes_ago.' Min'; }

	$online_for=$online_for.' Min';

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-user.php?username='.$username.'" onMouseOver="window.status=\'\'; return true">'.$username.'</a></td><td>'.$logins.'</td><td>'.$time_active_ago.'</td><td>'.$online_for.'</td><td align="center"><a href="add-instant-message.php?receiver='.$user.'">[Send Message]</a></td><td align="center"><a href="show-user.php?username='.$username.'" onMouseOver="window.status=\'\'; return true">[Show User Profile]</a></td></tr>';

	unset($online_for);

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>