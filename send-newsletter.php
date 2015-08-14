<?php

$flush=1;

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

$newsletter_id=isset($_GET['newsletter_id']) ? (int)$_GET['newsletter_id'] : exit;
$test=isset($_GET['test']) ? 1 : 0;
$subscribed=isset($_GET['subscribed']) ? (int)$_GET['subscribed'] : 0;
$all=isset($_GET['all']) ? 1 : 0;

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NEWSLETTER']['MAIN']} WHERE ID=$newsletter_id");

$newsletter_id=$data[1]->ID;
$newsletter_subject=trim($data[1]->subject);
$newsletter_text=trim($data[1]->text);

if ($test==1) { $query='WHERE ID='._ADMIN_USERID.' AND newsletter_opt_in=1'; }
else if ($subscribed==1) { $query="WHERE newsletter<>$newsletter_id AND newsletter_opt_in=1"; }
else if ($all==1) { $query="WHERE newsletter<>$newsletter_id"; }
else { echo 'Error'; exit; }

$period_11=date('Y-m-d H:i:s',mktime(0,0,0, date('m')-11, date('d'),date('Y')));

$html_instance->add_parameter(
	array('ENTITY'=>'USER',
		'WHERE'=>$query,
		'ORDER_COL'=>'ID',
		'ORDER_TYPE'=>'DESC',
		'MAXHITS'=>'199'
	));

$data=$html_instance->get_items();

if (empty($data)) { echo 'All done!'; exit; }
elseif ($test==1) { echo '<a href="edit-newsletter.php?newsletter_id='.$newsletter_id.'">Edit Newsletter</a><br><br>'; }
else { echo '<head><meta http-equiv="refresh" content="3;URL='.$_SERVER['PHP_SELF'].'?all='.$all.'&newsletter_id='.$newsletter_id.'&subscribed='.$subscribed.'"></head>'; }

for ($index=1; $index <= sizeof($data); $index++) {

	unset($msg);

	$ID=$data[$index]->ID;
	$username=$data[$index]->username;
	$email=$data[$index]->email;
	$lastlogin=$data[$index]->lastlogin;

	if ($lastlogin < "$period_11") { $warning=1; } else { $warning=0; }

	echo "$ID: (warn: $warning) $username ($email)<br>";

	$mailheaders='From: '._ADMIN_SENDER_NAME.' <'._ADMIN_EMAIL.'>'."\n";
	$mailheaders.='Reply-To: '._ADMIN_EMAIL."\n";
	$mailheaders.='Return-Path: '._RETURNS_EMAIL."\n";
	$mailheaders.='Precedence: bulk'."\n";
	$mailheaders.='Auto-Submitted: auto-generated'."\n";

	$msg='Hello '.$username.'!'."\n\n";

	$mailsubject=$newsletter_subject;

	/*
	if ($warning==0) { $mailsubject=$newsletter_subject; }
	else if ($warning==1) {

		$password=$data[$index]->password;

		$mailsubject='Important Message about your Account';
		$msg.=_SEPARATOR.'

You haven\'t logged in for almost a year. Your account
might be deleted if you fail to do so. Please login
as soon as possible. Your login details are:

Login: '.$username.'
Password: '.$password.'

Login at '._HOMEPAGE.'/

'._SEPARATOR."\n\n"; }*/

	$msg.=$newsletter_text;

	if ($all!=1) {

		$msg.="\n\n"._SEPARATOR.'

You receive this email because you signed up for our newsletter.

The URL of the website is '._HOMEPAGE.'/

To unsubscribe from this newsletter please go to
"Misc > Settings > Newsletter".';

	}

	$msg.="\n\n";
	$msg.=_SEPARATOR."\n";
	$msg.=_EMAIL_ADVERT_TEXT."\n";
	$msg.=_SEPARATOR."\n";
	$msg.=_SLOGAN."\n";
	$msg.=_HOMEPAGE."\n";
	$msg.='Email: '._ADMIN_EMAIL."\n";

	mail($email, $mailsubject, $msg, $mailheaders);

	$base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET newsletter=$newsletter_id WHERE ID=$ID");

}

?>