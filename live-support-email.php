<?php

$flush=1;

require 'class.base.php';
$base_instance=new base();

$title='Bookmark Manager - Live Support Email';

$name=isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
$email=isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$request=isset($_REQUEST['request']) ? $_REQUEST['request'] : '';
$userid=isset($_REQUEST['userid']) ? (int)$_REQUEST['userid'] : 1;

if (isset($_POST['save'])) {

$error='';

if (!$email) { $error.='<li> Email cannot be left blank'; }
if (!$name) { $error.='<li> Name cannot be left blank'; }
if (!$request) { $error.='<li> Request cannot be left blank'; }

if (!$error) {

$data=$base_instance->get_data("SELECT * FROM organizer_user WHERE ID='$userid'");
$email_admin=$data[1]->email;

$IP=$base_instance->get_ip();

$mailsubject='Live Support Contact';

$mailheaders="From: $email\n";
$mailheaders.="Reply-To: $email\n";

$msg="Name: $name\nEmail: $email\n$IP\nRequest: $request\n";

mail($email_admin, $mailsubject, $msg, $mailheaders);

$header='Thank you for your email.'; require 'template.html'; exit;

}

}

$header='Contact by Email';

if (isset($error)) { $text=$error; }
else { $text='Sorry, Live Support is not available at the moment. Please try again later or send us an email here.'; }

$main=$text.'<p>

<form action="'.$_SERVER['PHP_SELF'].'" method="post">

<input type="Hidden" name="userid" value='.$userid.'>

<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff">

<tr><td align="right"><b>Your Name:</b></td><td align="left">&nbsp;<input type="text" name="name" size="35" value="'.$name.'"></td></tr>

<tr><td align="right"><b>Email:</b></td><td align="left">&nbsp;<input type="text" name="email" size="35" value="'.$email.'"></td></tr>

<tr><td colspan=2 align="left">&nbsp;<textarea rows=10 cols=50 name="request" wrap>'.$request.'</textarea></td></tr>

<tr><td colspan=2 align="center"><input type="Submit" value="Send Email" name="save"></td></tr></form></td></tr></table>

</form>';

require 'template.html';

?>