<?php

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml; charset=utf-8');

require 'class.base.php';
$base_instance=new base();

$code=isset($_GET['code']) ? $_GET['code'] : exit;

$decoded=base64_decode($code);
list($username,$password)=explode('/',$decoded);

$data=$base_instance->get_data("SELECT ID FROM organizer_user WHERE username='$username' AND user_password='$password'");

$user=$data[1]->ID;

if (empty($user)) { exit; }

#

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE user=$user ORDER BY ID DESC LIMIT 50");

if (!$data) { $all_text='No To-do found'; }
else {

echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
<title>My To-Do List</title>
<link>'._HOMEPAGE.'/show-to-do.php</link>
<description>To-Do List - Powered by Bookmark-Manager.com</description>
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

echo '<item><title>'.htmlspecialchars($title).'</title>
<description>'.htmlspecialchars($text).'</description>
<pubDate>'.$date.'</pubDate></item>';

}

echo '</channel>
</rss>';

}

?>