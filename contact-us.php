<?php

session_start();

require 'config.php';

$title='Contact'; $error='';

$name=isset($_POST['name']) ? $_POST['name'] : '';
$email=isset($_POST['email']) ? $_POST['email'] : '';
$request=isset($_POST['request']) ? $_POST['request'] : '';
$number=isset($_POST['number']) ? (int)$_POST['number'] : '';

if (isset($_POST['save'])) {

	if (empty($name)) { $error.='<li> Please enter your name'; }
	if (empty($email)) { $error.='<li> Please enter your email'; }
	if (empty($request)) { $error.='<li> No text to send'; }

	if (!empty($number)) {

	if (isset($_SESSION['image_random_value'])) { $image_random_value=$_SESSION['image_random_value']; } else { $image_random_value=''; }

	if (md5($number)!=$image_random_value) { $error.='<li> You entered the wrong number, please try again'; }

	} else { $error.='<li> Please enter the shown number'; }

	if (!$error) {

	$mailheaders="From: $email\n";
	$mailheaders.="Reply-To: $email\n";

	$msg="Full Name: $name\nEmail: $email\nRequest: $request\n";

	mail(_ADMIN_EMAIL,'Contact Form (Blue Smiley Organizer)',$msg,$mailheaders);

	$header='Thank you for your email. We will get in touch with you shortly.'; require 'template.html'; exit;

	} else { $request=stripslashes($request); $name=stripslashes($name); }

}

$header='Contact';

if (!empty($error)) { $error='<font color="#ff0000"><ul>'.$error.'</ul></font>'; }

$main=$error.'<br>

<form action="contact-us.php" method="post">

<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff">

<tr><td align="right"><b>Name:</b></td><td align="left">&nbsp;<input type="text" name="name" size="35" value="'.$name.'"></td></tr>

<tr><td align="right"><b>Email:</b></td><td align="left">&nbsp;<input type="text" name="email" size="35" value="'.$email.'"></td></tr>

<tr><td colspan=2 align="left">&nbsp;<textarea rows=10 cols=50 name="request">'.$request.'</textarea></td></tr>

<tr><td align="right"><b>Enter Number:</b></td><td valign="top"><input name="number" type="text" id="number" value=""></td></tr>

<tr><td colspan="2">

&nbsp;&nbsp; <img src="image-verification.php" border="1">

</td></tr>

<tr><td colspan=2 align="center"><input type="SUBMIT" value="Send Email" name="save"></td></tr></form></td></tr></table>

<br><br>';

require 'template.html';

?>