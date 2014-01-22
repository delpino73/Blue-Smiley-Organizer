<?php

session_start();

require 'class.base.php';
$base_instance=new base();

$blog_id=isset($_REQUEST['blog_id']) ? (int)$_REQUEST['blog_id'] : '';

if (!$blog_id) {

header('HTTP/1.1 404 Not Found');
header('Location: page-not-found.php'); exit;

}

$data=$base_instance->get_data("SELECT user,title FROM {$base_instance->entity['BLOG']['MAIN']} WHERE ID='$blog_id'");

if (!$data) {

header('HTTP/1.1 404 Not Found');
header('Location: page-not-found.php'); exit;

}

$userid=$data[1]->user;
$blog_title=$data[1]->title;

$data=$base_instance->get_data("SELECT username,email FROM organizer_user WHERE ID='$userid'");

$username=$data[1]->username;
$email_of_blogger=$data[1]->email;

if (_SHORT_URLS==1) { $about_me_link=_HOMEPAGE.'/user-'.$username; }
else { $about_me_link=_HOMEPAGE.'/show-about-me.php?username='.$username; }

$text=isset($_POST['text']) ? $_POST['text'] : '';
$email=isset($_POST['email']) ? $_POST['email'] : '';
$name=isset($_POST['name']) ? $_POST['name'] : '';
$number=isset($_POST['number']) ? (int)$_POST['number'] : '';

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

$head='<meta name="robots" content="noindex,follow">
<link rel="alternate" type="application/rss+xml" title="Blog of '.$username.'" href="show-blog-public-rss.php?user='.$userid.'">';

$title='Blog Comment';

#

if (isset($_POST['save'])) {

	$error='';

	if (empty($name)) { $error.='<li> Please enter your name'; }
	if (empty($email)) { $error.='<li> Please enter your email'; }

	if (!$text) { $error='<li> Text cannot be left blank'; }
	else if ($text) {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!empty($number)) {

	if (isset($_SESSION['image_random_value'])) { $image_random_value=$_SESSION['image_random_value']; } else { $image_random_value=''; }

	if (md5($number)!=$image_random_value) { $error.='<li> You entered the wrong number, please try again'; }

	} else { $error.='<li> Please enter the shown number'; }

	if (!$error) {

	$msg='Hello '.$username.'!'."\n\n";
	$msg.='Somebody has left a comment on the following blog post:'."\n\n";
	$msg.=$blog_title."\n\n";
	$msg.='To approve the comment log into the Blue Smiley Organizer,'."\n".'under "Blog" click on Comments. Next to each comment is a "Publish" link.'."\n";

	$base_instance->send_email_from_admin('Blog Comment (Blue Smiley Organizer)',$msg,$email_of_blogger);

	$datetime=date('Y-m-d H:i:s');

	$base_instance->query('INSERT INTO '.$base_instance->entity['BLOG']['COMMENTS'].' (datetime,user,text,name,email,blog_id) VALUES ("'.sql_safe($datetime).'",'.$userid.',"'.sql_safe($text).'","'.sql_safe($name).'","'.sql_safe($email).'",'.$blog_id.')');

	$main='Thank you for your comment. To avoid spam the comments are approved manually before they are published.';

	require 'template-blog.html'; exit;

}

}

#

if (!empty($error)) { $msg='<font color="#ff0000"><ul>'.$error.'</ul></font>'; }
else { $msg='<h1>Add Blog Comment</h1>'; }

$main=$msg.'

<form action="'.$_SERVER['PHP_SELF'].'" method="post">

<input type="Hidden" name="blog_id" value="'.$blog_id.'">

<table cellpadding="5" cellspacing="7" bgcolor="#ffffff" style="border:1px solid #dcdcdc">

<tr><td align="left"><b>Name:</b> &nbsp;<input type="text" name="name" size="35" value="'.$name.'"></td></tr>

<tr><td align="left"><b>Email:</b> &nbsp;<input type="text" name="email" size="35" value="'.$email.'"></td></tr>

<tr><td colspan=2 align="left">&nbsp;<textarea rows=10 cols=40 name="text" wrap>'.$text.'</textarea></td></tr>

<tr><td align="left"><b>Enter Number:</b> &nbsp; <input name="number" type="text" id="number" value=""></td></tr>

<tr><td colspan="2">

&nbsp;&nbsp; <img src="image-verification.php" border="1">

</td></tr>

<tr><td colspan=2 align="center"><input type="SUBMIT" value="Save Comment" name="save"></td></tr></form></td></tr></table>

<br><br>';

require 'template-blog.html';

?>