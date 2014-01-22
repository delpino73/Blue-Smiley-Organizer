<?php

require 'class.base.php';
$base_instance=new base();

$username=isset($_REQUEST['username']) ? $_REQUEST['username'] : exit;
$page=isset($_GET['page']) ? (int)$_GET['page'] : 1;
$cat=isset($_GET['cat']) ? $_GET['cat'] : '';
$text_search=isset($_REQUEST['text_search']) ? $_REQUEST['text_search'] : '';

$data=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['USER']['MAIN'].' WHERE username="'.sql_safe($username).'"');

if (!$data) {

header('HTTP/1.1 404 Not Found');
header('Location: page-not-found.php'); exit;

}

$userid=$data[1]->ID;

#

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user=$userid");

$categories='';

for ($index=1; $index <= sizeof($data); $index++) {

	if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/blog-'.$username.'-'.$data[$index]->ID.'-1.html'; }
	else { $url=_HOMEPAGE.'/show-blog-public.php?username='.$username.'&cat='.$data[$index]->ID;

}

	if ($cat==$data[$index]->ID) { $categories.='<li><a href="'.$url.'"><strong>'.$data[$index]->title.'</strong></a></li>'; }

	else { $categories.='<li><a href="'.$url.'">'.$data[$index]->title.'</a></li>'; }

}

if (_SHORT_URLS==1) { $url_blog=_HOMEPAGE.'/blog-'.$username; }
else { $url_blog=_HOMEPAGE.'/show-blog-public.php?username='.$username; }

$categories.='<li><a href="'.$url_blog.'">Show all</a></li>';

#

if ($text_search) {

$query=' AND (text LIKE "%'.sql_safe($text_search).'%" OR title LIKE "%'.sql_safe($text_search).'%")';

$search_url=''; $param='username='.$username.'&text_search='.$text_search;

}

else if ($cat) {

if (_SHORT_URLS==1) { $search_url="blog-$username-$cat-".'$page2'; $param=''; }
else { $search_url=''; $param='username='.$username.'&cat='.$cat; }

$query=' AND category='.$cat;

} else {

if (_SHORT_URLS==1) { $search_url="blog-$username-".'$page2'; $param=''; }
else { $search_url=''; $param='username='.$username; }

$query='';

}

#

$search_form='<form action="show-blog-public.php" method="post">
<input type="hidden" name="username" value="'.$username.'">
<font size="1">Blog Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Go"></form><br><br>';

$rss_feed='<a href="show-blog-public-rss.php?user='.$userid.'"><img src="'._HOMEPAGE.'/pics/rss.jpg" border="0" alt="RSS Feed"> RSS Feed</a><p>';

#

$maxhits=10;

if (isset($page)) {

$offset=$maxhits*($page-1);

}

#

$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['MAIN']} WHERE user='$userid' $query ORDER BY datetime DESC LIMIT $offset, $maxhits");

$main='';

for ($index=1; $index <= sizeof($data2); $index++) {

$ID=$data2[$index]->ID;
$text=$data2[$index]->text;
$title=$data2[$index]->title;
$datetime=$data2[$index]->datetime;

#

$data3=$base_instance->get_data("SELECT COUNT(*) AS cnt FROM {$base_instance->entity['BLOG']['COMMENTS']} WHERE blog_id=$ID AND public=1");
$cnt=$data3[1]->cnt;

#

$datetime_converted=$base_instance->convert_date($datetime);

$title=convert_square_bracket($title);
$text=convert_square_bracket($text);

$text=nl2br($text);

if (!empty($text_search)) { $text=preg_replace("/($text_search)/i","<b>\\1</b>",$text); }

if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/permalink-'.$ID; }
else { $url=_HOMEPAGE.'/show-blog-public-permalink.php?blog_id='.$ID; }

$blog_len=strlen($text);

if ($blog_len > 3000) {

	for ($index2=1; $index2 <= 200; $index2++) {

	$pos=$index2+3000;

	if ($pos > $blog_len-1) { $end_at=$blog_len; break; }

	$current_char=$text{$pos};

	if ($current_char==' ') { $end_at=$pos; }
	else if ($current_char==']') { $end_at=$pos; }

	}

	$text=substr($text,0,$end_at);

	$text=preg_replace("/\[url=http:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!',: ]+)\]([a-zA-Z0-9_\-.\/~?=#&+%!',: ]+)\[\/url\]/e","'<a href=\"http://'.'\\1'.'\" target=\"_blank\">'.'\\2'.'</a>'",$text);

	$text=preg_replace("/\[image-([a-zA-Z0-9]+)\]/e","'<img src=\"get-image.php?id='.('\\1').'\" alt=\"\">'",$text);

	if ($end_at < $blog_len) { $text.=' <a href="'.$url.'">.. [click to continue]</a>'; }

} else {

$text=preg_replace("/\[url=http:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!',: ]+)\]([a-zA-Z0-9_\-.\/~?=#&+%!',: ]+)\[\/url\]/e","'<a href=\"http://'.'\\1'.'\" target=\"_blank\">'.'\\2'.'</a>'",$text);

$text=preg_replace("/\[image-([a-zA-Z0-9]+)\]/e","'<img src=\"get-image.php?id='.('\\1').'\" alt=\"\">'",$text);

}

$main.='<p><h2><a href="'.$url.'">'.$title.'</a></h2><font size=1>'.$datetime_converted.' | <a href="'.$url.'">Permalink</a> | <a href="'.$url.'">Comments ('.$cnt.')</a></font><p><!-- google_ad_section_start -->'.$text.'<!-- google_ad_section_end -->';

}

#

$scrollbar=build_scrollbar($page,$userid,$param,$maxhits,$query,$search_url);

function build_scrollbar($page,$userid,$url_parameter,$maxhits,$query,$search_engine_friendly) {

	global $base_instance;

	if (empty($page)) { $page=1; }

	if (empty($url_parameter)) { $url_parameter=''; } else { $url_parameter='&amp;'.$url_parameter; }

	# number of page links

	$data_cnt=$base_instance->get_data("SELECT COUNT(*) AS cnt FROM {$base_instance->entity['BLOG']['MAIN']} WHERE user='$userid' $query");
	$number_items=$data_cnt[1]->cnt;

	$page_links=ceil($number_items/$maxhits);
	if ($page_links < 2) { $page_links=0; return; }

	$scrollbar='<div class="pages" align="center">';

	if ($page!=1) {

	if ($search_engine_friendly) {

	$page2=$page-1;

	eval("\$search_engine_friendly2=\"$search_engine_friendly\";");

	$scrollbar.='<a href="'.$search_engine_friendly2.'.html">&laquo; Previous</a>';

	}

	else { $new_page=$page-1; $scrollbar.='<a href="show-blog-public.php?page='.$new_page.$url_parameter.'">&laquo; Previous</a>'; }

	}

	for ($index=1; $index <= $page_links; $index++) {

	if ($index==$page) { $scrollbar.='<span class="current">'.$index.'</span>'; }
	else {

	$off=abs($index-$page);

	if ($search_engine_friendly and ($off < 5 or $index==$page_links or $index==($page_links-1) or $index==1 or $index==2)) {

	$page2=$index;
	eval("\$search_engine_friendly2=\"$search_engine_friendly\";");
	$scrollbar.='<a href="'.$search_engine_friendly2.'.html">'.$index.'</a>';

	$dots=0;

	}

	else if ($off < 5 or $index==$page_links or $index==($page_links-1) or $index==1 or $index==2) { $scrollbar.='<a href="show-blog-public.php?page='.$index.$url_parameter.'">'.$index.'</a>'; $dots=0; }

	else if ($dots==0) { $scrollbar.='<span class="dots">...</span>'; $dots=1; }

	}

	}

	if ($page < $page_links) {

	if ($search_engine_friendly) {

	$page2=$page+1;
	eval("\$search_engine_friendly=\"$search_engine_friendly\";");
	$scrollbar.='<a href="'.$search_engine_friendly.'.html">Next &raquo;</a>';

	}

	else { $new_page=$page+1; $scrollbar.='<a href="show-blog-public.php?page='.$new_page.$url_parameter.'">Next &raquo;</a>'; }

	}

	$scrollbar.='</div><br>';

	return $scrollbar;

}

#

if (_SHORT_URLS==1) { $about_me_link=_HOMEPAGE.'/user-'.$username; }
else { $about_me_link=_HOMEPAGE.'/show-about-me.php?username='.$username; }

$head='<meta name="robots" content="noindex,follow">
<link rel="alternate" type="application/rss+xml" title="Blog of '.$username.'" href="show-blog-public-rss.php?user='.$userid.'">';

$title='Blog of '.$username;

require 'template-blog.html';

?>