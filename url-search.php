<?php

require 'class.base.php';
require 'class.misc.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$text_search=isset($_POST['text_search']) ? $_POST['text_search'] : '';

if ($text_search) {

$text_search=sql_safe($text_search);

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE (url LIKE '%$text_search%' OR title LIKE '%$text_search%' OR keywords LIKE '%$text_search%') AND user='$userid' ORDER BY popularity DESC LIMIT 1");

if ($data) {

$link_id=$data[1]->ID;
$url=$data[1]->url;
$user_of_link=$data[1]->user;
$popularity=$data[1]->popularity;
$frequency=$data[1]->frequency;
$last_visit=$data[1]->last_visit;
$visits=$data[1]->visits;

}

}

if (empty($link_id)) {

$html_instance->add_parameter(
array(
'HEADER'=>'Nothing found (Links)',
'TEXT'=>'<form action="url-search.php" method="post"><center><table cellpadding=10 cellspacing=0 border=0 bgcolor="#ffffff" class="pastel2"><tr><td align="right"><b>Text:</b> &nbsp;<input type="text" name="text_search" size="30" value="'.$text_search.'"></td></tr><tr><td align="center"><input type="SUBMIT" value="Search Links" name="save"></td></tr></table></center></form>'
));

$html_instance->process(); exit;

}

$datetime=date('Y-m-d H:i:s');

$new_popularity=$misc_instance->calculate_new_popularity($popularity);

$base_instance->query("UPDATE {$base_instance->entity['LINK']['MAIN']} SET visits=visits+1,popularity=$new_popularity,last_visit='$datetime' WHERE ID=$link_id");

if (preg_match('/https:\/\//',$url)) { $url2=$url; } elseif (preg_match('/ftp:\/\//',$url)) { $url2=$url; } else { $url2='http://'.$url; }

echo '<meta http-equiv="refresh" content="0; URL='.$url2.'">';

?>