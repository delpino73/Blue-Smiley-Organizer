<?php

session_start();

require 'class.base.php';
$base_instance=new base();

$username=isset($_REQUEST['username']) ? $_REQUEST['username'] : exit;

if (_SHORT_URLS==1) { $about_me_link=_HOMEPAGE.'/user-'.$username; }
else { $about_me_link=_HOMEPAGE.'/show-about-me.php?username='.$username; }

$name=isset($_POST['name']) ? $_POST['name'] : '';
$email=isset($_POST['email']) ? $_POST['email'] : '';
$request=isset($_POST['request']) ? $_POST['request'] : '';
$number=isset($_POST['number']) ? (int)$_POST['number'] : '';

$data=$base_instance->get_data('SELECT ID,email FROM organizer_user WHERE username="'.sql_safe($username).'"');

if (!$data) { exit; }

$userid=$data[1]->ID;
$email_blogger=$data[1]->email;

#

$head='<meta name="robots" content="noindex,follow">
<link rel="alternate" type="application/rss+xml" title="Blog of '.$username.'" href="show-blog-public-rss.php?user='.$userid.'">';

$title='Contact me - Blog of '.$username;

#

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user=$userid");

$categories='';

for ($index=1; $index <= sizeof($data); $index++) {

	if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/blog-'.$username.'-'.$data[$index]->ID.'-1.html'; }
	else { $url=_HOMEPAGE.'/show-blog-public.php?username='.$username.'&cat='.$data[$index]->ID; }

	$categories.='<li><a href="'.$url.'">'.$data[$index]->title.'</a></li>';

}

if (_SHORT_URLS==1) { $url_blog=_HOMEPAGE.'/blog-'.$username; }
else { $url_blog=_HOMEPAGE.'/show-blog-public.php?username='.$username; }

$categories.='<li><a href="'.$url_blog.'">Show all</a></li>';

#

$search_form='<form action="show-blog-public.php" method="post">
<input type="hidden" name="username" value="'.$username.'">
<font size="1">Blog Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Go"></form><br><br>';

$rss_feed='<a href="show-blog-public-rss.php?user='.$userid.'"><img src="'._HOMEPAGE.'/pics/rss.jpg" border="0" alt="RSS Feed"> RSS Feed</a><p>';

$scrollbar='';

#

if (isset($_POST['save'])) {

	$error='';

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

	mail($email_blogger,'Blog Contact (Blue Smiley Organizer)',$msg,$mailheaders);

	$main='Thank you for your email.';

	require 'template-blog.html'; exit;

}

}

#

if (!empty($error)) { $msg='<font color="#ff0000"><ul>'.$error.'</ul></font>'; }
else { $msg='<h1>Contact Me</h1>'; }

$main=$msg.'

<form action="'.$_SERVER['PHP_SELF'].'" method="post">

<input type="Hidden" name="username" value="'.$username.'">

<table cellpadding="5" cellspacing="7" bgcolor="#ffffff" style="border:1px solid #dcdcdc">

<tr><td align="left"><b>Name:</b> &nbsp;<input type="text" name="name" size="35" value="'.$name.'"></td></tr>

<tr><td align="left"><b>Email:</b> &nbsp;<input type="text" name="email" size="35" value="'.$email.'"></td></tr>

<tr><td colspan=2 align="left">&nbsp;<textarea rows=10 cols=40 name="request" wrap>'.$request.'</textarea></td></tr>

<tr><td align="left"><b>Enter Number:</b> &nbsp; <input name="number" type="text" id="number" value=""></td></tr>

<tr><td colspan="2">

&nbsp;&nbsp; <img src="image-verification.php" border="1">

</td></tr>

<tr><td colspan=2 align="center"><input type="SUBMIT" value="Send Email" name="save"></td></tr></form></td></tr></table>

<br><br>';

if (_SHORT_URLS==1) { $about_me_link=_HOMEPAGE.'/user-'.$username; }
else { $about_me_link=_HOMEPAGE.'/show-about-me.php?username='.$username; }

require 'template-blog.html';

?>