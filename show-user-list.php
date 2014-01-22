<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$user_instance=new user();
$html_instance=new html();

$user_instance->check_for_admin();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'USER',
'ORDER_COL'=>'lastlogin',
'MAXHITS'=>100,
'HEADER'=>'All User',
'SORTBAR'=>3,
'SORTBAR_FIELD1'=>'logins','SORTBAR_NAME1'=>'Logins',
'SORTBAR_FIELD2'=>'lastlogin','SORTBAR_NAME2'=>'Last Login',
'SORTBAR_FIELD3'=>'datetime','SORTBAR_NAME3'=>'Sign up Date',
'INNER_TABLE_WIDTH'=>'90%'
));

$data=$html_instance->get_items();

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">
<tr><td><strong>Lastlogin</strong></td><td><strong>Username</strong></td><td><strong>Firstname</strong></td><td><strong>Lastname</strong></td><td><strong>Logins</strong></td><td><strong>Sign up Date</strong></td><td><b>Time active ago</b></td><td><b>Online for</b></td><td><b>Newsletter</b></td></tr>';

$now=time();

for ($index=1; $index <= sizeof($data); $index++) {

	$datetime=$data[$index]->datetime;
	$ID=$data[$index]->ID;
	$username=$data[$index]->username;
	$lastlogin=$data[$index]->lastlogin;
	$logins=$data[$index]->logins;
	$firstname=$data[$index]->firstname;
	$lastname=$data[$index]->lastname;
	$newsletter_opt_in=$data[$index]->newsletter_opt_in;

	if ($newsletter_opt_in==1) { $newsletter='Yes'; } else { $newsletter='No'; }

	if (!$firstname) { $firstname='&nbsp;'; }
	if (!$lastname) { $lastname='&nbsp;'; }

	$lastlogin_converted=$base_instance->convert_date($lastlogin);
	$datetime_converted=$base_instance->convert_date($datetime);

	$data2=$base_instance->get_data("SELECT last_active,create_time FROM {$base_instance->entity['SESSION']['MAIN']} WHERE user='$ID'");

	if ($data2) {

	$last_active=$data2[1]->last_active;
	$create_time=$data2[1]->create_time;

	$online_for=floor(($now-$create_time)/60);

	$active_seconds_ago=$now-$last_active;
	$active_minutes_ago=floor(($now-$last_active)/60);

	if ($active_minutes_ago==0) { $time_active_ago=$active_seconds_ago.' Sec'; } else { $time_active_ago=$active_minutes_ago.' Min'; }

	$online_for=$online_for.' Min';

	} else { $time_active_ago='-'; $online_for='-'; }

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$lastlogin_converted.'</td><td><a href="show-user.php?username='.$username.'" onMouseOver="window.status=\'\'; return true">'.$username.'</a></td><td>'.$firstname.'</td><td>'.$lastname.'</td><td>'.$logins.'</td><td>'.$datetime_converted.'</td><td>'.$time_active_ago.'</td><td>'.$online_for.'</td><td>'.$newsletter.'</td></tr>';

	unset($online_for);

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>