<?php

require 'class.base.php';
$base_instance=new base();

$blog_id=isset($_GET['blog_id']) ? (int)$_GET['blog_id'] : '';

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
$title=$data[1]->title;

$data=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$username=$data[1]->username;

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

#

$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['MAIN']} WHERE ID=$blog_id");

$main='';

$ID=$data2[1]->ID;
$text=$data2[1]->text;
$title=$data2[1]->title;
$datetime=$data2[1]->datetime;

#

$datetime_converted=$base_instance->convert_date($datetime);

$title=convert_square_bracket($title);
$text=convert_square_bracket($text);

$text=nl2br($text);

$text=preg_replace_callback('/\[url=http:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!,: ]+)\]([a-zA-Z0-9_\-.\/~?=#&+%!,: ]+)\[\/url\]/',function($m) { return '<a href=\"http://'.$m[1].'\" target=\"_blank\">'.$m[2].'</a>';},$text);

$text=preg_replace_callback('/\[image-([a-zA-Z0-9]+)\]/',function($m) { return '<img src="get-image.php?id='.$m[1].'" alt="">';},$text);

$main.='<p><h1>'.$title.'</h1><font size=1>'.$datetime_converted.'</font><p><!-- google_ad_section_start -->'.$text.'<!-- google_ad_section_end -->';

# comments section

$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['COMMENTS']} WHERE blog_id=$blog_id AND public=1");

if ($data2) {

$main.='<br><br><strong><u>Comments:</u></strong><p>';

for ($index=1; $index <= sizeof($data2); $index++) {

$text=$data2[$index]->text;
$datetime=$data2[$index]->datetime;
$name=$data2[$index]->name;

$datetime_converted=$base_instance->convert_date($datetime);

if (!$name) { $name='anon'; }

$main.=$text.'<br><font size=1 color="#696969">By '.$name.' - '.$datetime_converted.'</font><br><br>';

}

} else { $comments=''; }

#

$main.='<br><br><a href="add-blog-comment.php?blog_id='.$ID.'" rel="nofollow">[Add Comment]</a>';

$scrollbar='';

if (_SHORT_URLS==1) { $about_me_link=_HOMEPAGE.'/user-'.$username; }
else { $about_me_link=_HOMEPAGE.'/show-about-me.php?username='.$username; }

$head='<meta name="robots" content="index,follow">
<link rel="alternate" type="application/rss+xml" title="Blog of '.$username.'" href="show-blog-public-rss.php?user='.$userid.'">';

require 'template-blog.html';

?>