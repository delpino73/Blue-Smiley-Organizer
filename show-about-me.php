<?php

require 'class.base.php';
$base_instance=new base();

$username=isset($_GET['username']) ? $_GET['username'] : exit;

$data=$base_instance->get_data("SELECT ID,about_me FROM organizer_user WHERE username='$username'");

if (!$data) { exit; }

$userid=$data[1]->ID;
$about_me=$data[1]->about_me;

#

$data=$base_instance->get_data("SELECT * FROM organizer_blog_category WHERE user=$userid");

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

#

$about_me=convert_square_bracket($about_me);

$about_me=nl2br($about_me);

if (!empty($text_search)) { $text=preg_replace("/($text_search)/i","<b>\\1</b>",$text); }

$about_me=$base_instance->insert_links($about_me);

$main='<p>'.$about_me;

if (_SHORT_URLS==1) { $about_me_link=_HOMEPAGE.'/user-'.$username; }
else { $about_me_link=_HOMEPAGE.'/show-about-me.php?username='.$username; }

$head='<meta name="robots" content="index,follow">
<link rel="alternate" type="application/rss+xml" title="Blog of '.$username.'" href="show-blog-public-rss.php?user='.$userid.'">';

$scrollbar='';

$title='About me - Blog of '.$username;

require 'template-blog.html';

?>