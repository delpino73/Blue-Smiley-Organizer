<?php

require 'class.base.php';
$base_instance=new base();

$code=isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
$title='Reset Password'; $main='';

if (isset($_POST['save']) && isset($_POST['code'])) {

	$decoded=base64_decode($code);
	list($u,$a)=explode('/',$decoded);

	$data=$base_instance->get_data('SELECT ID,email FROM '.$base_instance->entity['PASSWORD']['MAIN'].' WHERE ID="'.sql_safe($u).'" AND password="'.sql_safe($a).'"');

	if (empty($data)) {

	$main='Unfortunately your password can\'t be reset.<p>Please go to the <a href="password-reminder.php">password reminder again</a>.<p>';

	require 'template.html'; exit;

	}

	$email_db=$data[1]->email;

	$new_password=sql_safe($_POST['new_password']);
	$new_password2=$_POST['new_password2'];

	if ($new_password) {

	if (strlen($new_password)<4) { $main.='<li> Password too short, at least 4 characters'; $error=1; }

	} else { $main.='<li> Password cannot be left blank'; $error=1; }

	if ($new_password<>$new_password2) { $main.='<li> Passwords are not the same'; $error=1; }

	if (empty($error)) {

	$secure_password=sha1($new_password);

	$base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET user_password='$secure_password' WHERE email='$email_db'");

	$base_instance->query("DELETE FROM {$base_instance->entity['PASSWORD']['MAIN']} WHERE email='$email_db'");

	#

	$data=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE email='$email_db'");

	$username=$data[1]->username;

	$url=$username.'/'.$secure_password;
	$encoded_url=base64_encode($url);

	if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/login-'.$encoded_url; }
	else { $url=_HOMEPAGE.'/autologin.php?code='.$encoded_url; }

	$main='Password has been reset, please login on the left-hand side or use the links below:<p><table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff"><tr><td>

To autologin with one click, drag and drop the following link onto your toolbar:<p>

<strong><a href="'.$url.'"><u>Organizer</u></a></strong><p>

Or bookmark the following link:<p>

<small>'.$url.'</small>

</td></tr><tr><td>

To quickly add links, drag and drop (or right mouse click and save) the following link onto your toolbar:<p></p>

<a href="javascript:void(window.open(\''._HOMEPAGE.'/autolink.php?code='.$encoded_url.'&url=\'+location.href,\'_blank\',\'width=550,height=525,status=no,resizable=yes,scrollbars=auto\'))"><u>Add Link</u></a><p>

If you want to bookmark the website you are currently on just click on "Add Link" on your toolbar.

</td></tr>

<tr><td align="center"><br>

<form action="'._HOMEPAGE.'/autologin.php?code='.$encoded_url.'" method="post">
<input type="SUBMIT" value="LOG IN NOW" name="save">
</form>

</td></tr></table>';

	require 'template.html'; exit;

	}

}

$header='Reset Password';

$main.='<p>Enter a new password for your account.<p>

<form action="reset-password.php" method="post">

<input type="Hidden" name="code" value="'.$code.'">

<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff">

<tr><td align="right"><b>New Password:</b></td><td align="left">&nbsp;<input type="password" name="new_password" size="35" value=""></td></tr>

<tr><td align="right"><b>Repeat new Password:</b></td><td align="left">&nbsp;<input type="password" name="new_password2" size="35" value=""></td></tr>

<tr><td colspan=2 align="center"><input type="SUBMIT" value="Save Password" name="save"></td></tr></form></td></tr></table>

<br><br>';

require 'template.html';

?>