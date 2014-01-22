<?php

require 'class.base.php';
$base_instance=new base();

$main='';

if (isset($_POST['save'])) {

	$error='';

	$email=$_POST['email'];

	if ($email) {

		$data=$base_instance->get_data('SELECT email,username FROM '.$base_instance->entity['USER']['MAIN'].' WHERE email="'.sql_safe($email).'"');

		if (!$data) { $main.='Email Address could not be found, please try again.'; $error=1; }
		else { $username=$data[1]->username; }

	}

	else { $main.='Email address cannot be left blank'; $error=1; }

	if (!$error) {

	$password=mt_rand(1,100000);

	$now=time();

	$base_instance->query('INSERT INTO '.$base_instance->entity['PASSWORD']['MAIN'].' (create_time,email,password) VALUES ("'.$now.'","'.sql_safe($email).'","'.$password.'")');

	$insert_id=mysql_insert_id();

	# delete old entries

	$auth_time=604800; # 7 days in seconds
	$time=time()-$auth_time;

	$base_instance->query("DELETE FROM {$base_instance->entity['PASSWORD']['MAIN']} WHERE create_time < $time");

	#

	$url=$insert_id.'/'.$password;
	$encoded_url=base64_encode($url);

	$msg='Hello!'."\n\n";
	$msg.='Your Login is: '.$username."\n\n";
	$msg.='Please set a new password by clicking on the link below:'."\n\n";
	$msg.=_HOMEPAGE."/reset-password.php?code=$encoded_url\n\n";
	$msg.='If you encounter any problems please send an email to '._ADMIN_EMAIL."\n\n";

	$base_instance->send_email_from_admin('Password Reminder',$msg,$email);

	$main.='An email has been sent to <strong>'.$email.'</strong> with details on how to reset your password.<p>'; require 'template.html'; exit;

	}

}

$header='Password Reminder';

$main.='<p>

<form action="password-reminder.php" method="post">

<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff">

<tr><td align="right"><b>Email:</b></td><td align="left">&nbsp;<input type="text" name="email" size="35" value=""></td></tr>

<tr><td colspan=2 align="center"><input type="SUBMIT" value="Send Reminder" name="save"></td></tr></form></td></tr></table>

<br><br>';

$title='Password Reminder';

require 'template.html';

?>