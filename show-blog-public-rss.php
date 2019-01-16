<?php

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml; charset=utf-8');

require 'class.base.php';
$base_instance=new base();

$user=isset($_REQUEST['user']) ? (int)$_REQUEST['user'] : exit;

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['MAIN']} WHERE user=$user ORDER BY ID DESC LIMIT 10");

if (!$data) { $all_text='No Blog found'; }
else {

$data2=$base_instance->get_data("SELECT username FROM organizer_user WHERE ID='$user'");
$username=$data2[1]->username;

echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
<title>Blog from '.$username.'</title>
<link>'._HOMEPAGE.'/show-blog-public.php?username='.$username.'</link>
<description>Blog from '.$username.' - Powered by Bookmark-Manager.com</description>
<language>en-us</language>
';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$text=$data[$index]->text;
$datetime=$data[$index]->datetime;

$timestamp=strtotime($datetime);

$date=date('r',$timestamp);

$text=substr($text,0,200);

$text=preg_replace_callback('/\[url=http:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!,: ]+)\]([a-zA-Z0-9_\-.\/~?=#&+%!,: ]+)\[\/url\]/',function($m) { return '<a href=\"http://'.$m[1].'\" target=\"_blank\">'.$m[2].'</a>';},$text);

if (_SHORT_URLS==1) { $link=_HOMEPAGE.'/permalink-'.$ID; }
else { $link=_HOMEPAGE.'/show-blog-public-permalink.php?blog_id='.$ID; }

echo '<item><title>'.htmlspecialchars($title).'</title>
<link>'.htmlspecialchars($link).'</link>
<description>'.htmlspecialchars($text).'</description>
<pubDate>'.$date.'</pubDate></item>';

}

echo '</channel>
</rss>';

}

?>